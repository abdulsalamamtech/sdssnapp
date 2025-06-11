<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Helpers\Paystack;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipPaymentRequest;
use App\Http\Requests\UpdateMembershipPaymentRequest;
use App\Http\Resources\MembershipPaymentResource;
use App\Models\Api\Membership;
use App\Models\Api\MembershipPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembershipPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $membershipPayments = MembershipPayment::latest()->paginate();

        // Check if there are any membership payments
        if ($membershipPayments->isEmpty()) {
            return ApiResponse::error([], 'No membership payments found', 404);
        }
        $data = MembershipPaymentResource::collection($membershipPayments);
        // Return the membership payments resource
        return ApiResponse::success($data, 'Membership payments retrieved successfully.');
    }

    /**
     * [Login user] Make payment for membership.
     */
    public function store(StoreMembershipPaymentRequest $request)
    {
        try {
            //code...
            DB::beginTransaction();
            $data = $request->validated();
            $membership = Membership::findOrFail($data['membership_id']);
            // certificate
            // $cert = $membership->certificationRequest->certification->amount;
            $totalPayAmount = $membership?->certificationRequest?->certification?->amount;
            if (!$totalPayAmount) {
                return ApiResponse::error([], 'Error: unable to retrieve membership payment amount!', 500);
            }

            if ($membership->status == 'paid') {
                return ApiResponse::success([], "you have paid for this membership certification!");
            }

            // Check if the user has already paid for this membership
            $existingPayment = $membership->membershipPayments()
                ->where('user_id', $membership->user->id)
                ->where('status', 'successful')
                ->first();
            if ($existingPayment) {
                return ApiResponse::error([], 'Error: you have already paid for this membership!', 400);
            }

            // Create the payment data
            $payment_data = [
                'name' => $membership->user->full_name,
                'email' => $membership->user->email,
                'amount' => round($totalPayAmount, 2),
                'payment_id' => $membership->id,
                // 'redirect_url' => URL('account/orders'),
                // 'redirect_url' => config('app.frontend_url'),
                'redirect_url' => route('transactions.verify'),
            ];

            $PSP = Paystack::make($payment_data);
            if ($PSP['success']) {
                // Record The transaction
                // 'user_id',
                // 'order_id',
                // 'amount',
                // 'status',
                // 'reference',
                // 'payment_method',
                // 'data'
                $membership->membershipPayments()->create([
                    'user_id' => $membership->user->id,
                    'amount' => $totalPayAmount,
                    // 'status',
                    'reference' => $PSP['reference'],
                    'payment_method' => $PSP['gateway'],
                    'data' => json_encode($PSP)
                ]);

                // Payment link
                $response = [
                    'membership_id' => $membership->id,
                    'payment_link' => $PSP['authorization_url'],
                ];
                // Commit the transaction
                DB::commit();
                // Return the payment link
                info('payment link created: ' . $PSP['authorization_url']);
                return ApiResponse::success($response, 'Payment link created, please make payment to validate your membership!');
            } else {
                info('payment initialization error: ' . $PSP['message']);
                return ApiResponse::error([], 'Error: unable to initialize payment process!', 500);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            info('payment initialization error: ' . $th->getMessage());
            return ApiResponse::error([], 'Error: unable to initialize payment process!', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MembershipPayment $membershipPayment)
    {
        return $membershipPayment->load(['user', 'membership']);
        $response = new MembershipPaymentResource($membershipPayment);
        return ApiResponse::success($response, 'Membership payment retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMembershipPaymentRequest $request, MembershipPayment $membershipPayment)
    {
        // $data = $request->validated();
        // $membershipPayment->update($data);
        $membershipPayment->load(['user', 'membership']);
        $response = new MembershipPaymentResource($membershipPayment);
        return ApiResponse::success($response, 'Membership payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(MembershipPayment $membershipPayment)
    // {
    //     $membershipPayment->delete();
    //     return ApiResponse::success([], 'Membership payment deleted successfully.');
    // }

    /**
     * [User] My membership payments.
     */
    public function myMembershipPayments()
    {
        $user = request()->user();

        $membershipPayments = MembershipPayment::where('user_id', $user->id)
            ->latest()->paginate();

        // Check if there are any membership payments
        if ($membershipPayments->isEmpty()) {
            return ApiResponse::error([], 'No membership payments found', 404);
        }
        // load the user and membership relationships
        // $membershipPayments->load(['user', 'membership']);
        // Transform the membership payments into a resource collection
        $data = MembershipPaymentResource::collection($membershipPayments);
        // Return the membership payments resource
        return ApiResponse::success($data, 'Membership payments retrieved successfully.', 200,  $membershipPayments);
    }

    // verify transaction
    /**
     * Verify payment domain.com?reference=oo5ihug1qm
     * @param reference=oo5ihug1qm
     */
    public function verifyTransaction(Request $request)
    {
        // http://localhost:3000/?trxref=oo5ihug1qm&reference=oo5ihug1qm
        // http://127.0.0.1:8000/events/8?trxref=soq9s7fxmf&reference=soq9s7fxmf


        try {
            // Verify payment transaction
            $redirectUrl = config('app.frontend_url') . '/payment/error';
            if ($request?->filled('trxref') || $request?->filled('reference')) {
                $reference = $request?->reference ?? $request?->trxref;
                $PSP = Paystack::verify($reference);
                info('paystack validation response: ', $PSP);
                // return $PSP;
                $message = $PSP['message'];
                info('verify payment message: ', [$message]);
                if ($PSP['success']) {
                    $membershipPayment = MembershipPayment::where('reference', $reference)->first();
                    if ($membershipPayment) {
                        $membershipPayment->status = 'successful';
                        $membershipPayment->save();

                        // redirect to success page
                        $redirectUrl = config('app.frontend_url') . '/payment/success?trxref=' . $membershipPayment->reference;

                        // If payment type for membership is new
                        if ($membershipPayment->payment_type == 'new' && $membershipPayment->membership_id) {
                            // update membership
                            $membership = Membership::where('id', $membershipPayment->membership_id)->first();
                            // certification request
                            $membership->certificationRequest->status = 'paid';
                            $membership->certificationRequest->save();
                            // update membership
                            $membership->certificate_status = 'generated';
                            $membership->status = 'paid';
                            $membership->save();

                            // Send mail to user you can now generate your certification


                        }

                        // Testing
                        // $membership->issued_on = now();
                        // $membership->expires_on = now()->addYear();
                        // $membership->serial_no = 'SDSSN' . strtoupper(uniqid());
                        // $membership->qr_code = config('app.frontend_certificate_verify_url') . $membership->serial_no;
                        // $membership->save();

                        // If payment type for membership is renewal
                        // if ($membershipPayment->payment_type == 'renewal' && $membershipPayment->membership_id) {
                        //     // update membership
                        //     $membership = Membership::where('id', $membershipPayment->membership_id)->first();
                        //     // certification request
                        //     $membership->certificationRequest->status = 'paid';
                        //     $membership->certificationRequest->expires_on = date(
                        //         'Y-m-d',
                        //         strtotime('+ ' . $membership->certificationRequest->certification->duration . '' . $membership->certificationRequest->certification->duration_unit)
                        //     );
                        //     $membership->certificationRequest->save();
                        //     // update membership
                        //     $membership->certificate_status = 'generated';
                        //     $membership->status = 'paid';
                        //     $membership->save();
                        // }

                        // return redirect($redirectUrl);
                        return $membership;
                    }
                } else {
                    // log error and return error response
                    info('Membership Payment Transaction verification failed: ', [$message]);
                    // Redirect to error page
                    // $redirectUrl = config('app.frontend_url') . '/payment/error?trxref=' . $reference;
                    return $message;
                }
            }

            // return response
            return redirect($redirectUrl);
        } catch (\Exception $e) {
            // log error and return error response
            // return response()->json(['message' => 'Transaction verification failed', 'error' => $e->getMessage()], 500);
            info('Transaction verification failed: ', [$e->getMessage()]);
            return redirect($redirectUrl);
        }
    }
}
