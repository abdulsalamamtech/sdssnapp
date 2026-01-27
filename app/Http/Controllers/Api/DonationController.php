<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Helpers\Paystack;
use App\Http\Controllers\Controller;
use App\Models\Api\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    /**
     * [public] Make Donation
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'amount' => ['required', 'numeric', 'min:10000'],
            'reason_for_donation' => ['required', 'string'],
            'note' => ['nullable', 'string'],
        ]);

        $data['user_id'] = $request?->user()?->id ?? null;
        $donation = Donation::create($data);

        // Process the donation
        try {
            //code...
            DB::beginTransaction();
            // donation
            if ($donation->status == 'paid') {
                return ApiResponse::success([], "you have paid for this donation!");
            }

            // Check if the user has already paid for this donation
            $existingPayment = $donation->donationPayments()
                ->where('user_id', $donation->user?->id)
                ->where('status', 'successful')
                ->first();
            info('Existing Successful Payment: ', $existingPayment?->toArray() ?? []);
            if ($existingPayment) {
                return ApiResponse::error([], 'Error: you have already paid for this donation!', 400);
            }

            // Get the last payment if it hasn't expired
            $lastPayment = $donation?->donationPayments()
                ->where('user_id', $donation->user?->id)
                ->where('status', 'pending')
                ->where('created_at', '>=', now()->subMinutes(20)) // assuming 20 minutes expiry
                ->latest()
                ->first();

            // Log the last payment info
            info('Last Created Pending Payment: ', $lastPayment?->toArray() ?? []);

            // If there is a last payment, use its PSP data to get the payment link
            if ($lastPayment) {
                $PSP = $lastPayment?->data ? json_decode($lastPayment->data, true) : null;
                if ($lastPayment && $PSP && isset($PSP['authorization_url']) && isset($PSP['reference'])) {
                    // Payment link
                    info('Last Payment Generated PSP: ', $PSP ?? []);
                    $response = [
                        'donation_id' => $donation->id,
                        'payment_link' => $PSP['authorization_url'],
                        'reference' => $PSP['reference'],
                        'access_code' => $PSP['access_code'],
                    ];

                    info('Payment link created from last payment: ' . json_encode($response));
                    return ApiResponse::success($response, 'You have a pending payment. Please complete the payment using the link provided, your payment validate your donation!');
                }
                $PSP = null; // reset PSP to null so that a new payment link is created below
            }


            // Create the payment data
            $payment_data = [
                'name' => $donation->full_name,
                'email' => $donation?->email,
                'amount' => round($donation->amount, 2),
                'payment_id' => $donation->id,
                // 'redirect_url' => URL('account/orders'),
                // 'redirect_url' => config('app.frontend_url'),
                'redirect_url' => route('donations.verify'),
            ];

            $PSP = Paystack::make($payment_data);
            info('Paystack Response: ', $PSP);
            if ($PSP['success']) {
                // Record The transaction
                // 'user_id',
                // 'order_id',
                // 'amount',
                // 'status',
                // 'reference',
                // 'payment_method',
                // 'data'
                $donation->donationPayments()->create([
                    'user_id' => $donation?->user?->id,
                    'amount' => $donation->amount,
                    // 'status',
                    'reference' => $PSP['reference'],
                    'payment_method' => $PSP['gateway'],
                    'data' => json_encode($PSP)
                ]);

                // Payment link
                $response = [
                    'donation_id' => $donation->id,
                    'payment_link' => $PSP['authorization_url'],
                    'reference' => $PSP['reference'],
                    'access_code' => $PSP['access_code'],
                ];
                // Commit the transaction
                DB::commit();
                // Return the payment link
                info('payment link created: ' . $PSP['authorization_url']);
                return ApiResponse::success($response, 'Payment link created, please make payment to validate your donation!');
            } else {
                info('payment initialization failed: ' . $PSP['message']);
                return ApiResponse::error([], 'Error: failed to initialize payment process!', 500);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            info('payment initialization error: ' . $th->getMessage());
            return ApiResponse::error([], 'Error: unable to initialize payment process!', 500);
        }
    }
}
