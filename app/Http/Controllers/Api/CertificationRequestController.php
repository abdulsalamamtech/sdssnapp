<?php

namespace App\Http\Controllers\Api;

use App\Events\CertificationRequestedProceedEvent;
use App\Helpers\ApiResponse;
use App\Helpers\CustomGenerator;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCertificationRequestRequest;
use App\Http\Requests\UpdateCertificationRequestRequest;
use App\Http\Resources\CertificationRequestResource;
use App\Models\Api\CertificationRequest;
use App\Models\Api\Membership;
use App\Models\Assets;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;

class CertificationRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $certificationRequests = CertificationRequest::with(['user'])->get();
        $certificationRequests = CertificationRequest::latest()->paginate();

        // Check if there are any certification requests
        if ($certificationRequests->isEmpty()) {
            return ApiResponse::error([], 'No certification requests found', 404);
        }
        $data = CertificationRequestResource::collection($certificationRequests);
        // Return the certification requests resource
        return ApiResponse::success($data, 'certification requests retrieved successfully.');
    
    }

    /**
     * [login user] Store a newly created resource in storage.
     */
    public function store(StoreCertificationRequestRequest $request)
    {
        $data = $request->validated();
        try {
            // Begin a database transaction
            DB::beginTransaction();
            // Set the user_id to the authenticated user's ID
            $user = auth()?->user();
            $data['user_id'] = $user?->id ?? 1;
            $data['full_name'] = $user->fullName;

            // Set the status to 'pending' by default
            $data['status'] = 'pending';

            // Handle file upload if signature is provided
            // upload the credential if it exists
            // Upload Asset if exists
            if ($request->hasFile('signature')) {
                $cloudinaryImage = $request->file('signature')->storeOnCloudinary('sdssn-app/signatures');
                // Check if the file was uploaded successfully
                if (!$cloudinaryImage) {
                    return ApiResponse::error('Failed to upload signature image.', 500);
                }
                // Get the secure URL and public ID from the uploaded file
                $url = $cloudinaryImage->getSecurePath();
                $public_id = $cloudinaryImage->getPublicId();

                info('User signature image uploaded to Cloudinary: ' . $url);

                $asset = Assets::create([
                    'original_name' => 'user signature image',
                    'path' => 'image',
                    'hosted_at' => 'cloudinary',
                    'name' =>  $cloudinaryImage->getOriginalFileName(),
                    'description' => 'user signature file upload',
                    'url' => $url,
                    'file_id' => $public_id,
                    'type' => $cloudinaryImage->getFileType(),
                    'size' => $cloudinaryImage->getSize(),
                ]);

                $data['user_signature_id'] = $asset->id;
            }

            // upload the credential if it exists
            // Upload Asset if exists
            if ($request->hasFile('credential')) {
                $cloudinaryImage = $request->file('credential')->storeOnCloudinary('sdssn-app/credentials');
                // Check if the file was uploaded successfully
                if (!$cloudinaryImage) {
                    return ApiResponse::error('Failed to upload credential file.', 500);
                }
                // Get the secure URL and public ID from the uploaded file
                $url = $cloudinaryImage->getSecurePath();
                $public_id = $cloudinaryImage->getPublicId();

                info('User credential file uploaded to Cloudinary: ' . $url);

                $asset = Assets::create([
                    'original_name' => 'user credential file',
                    'path' => 'file',
                    'hosted_at' => 'cloudinary',
                    'name' =>  $cloudinaryImage->getOriginalFileName(),
                    'description' => 'user credential file upload',
                    'url' => $url,
                    'file_id' => $public_id,
                    'type' => $cloudinaryImage->getFileType(),
                    'size' => $cloudinaryImage->getSize(),
                ]);

                $data['credential_id'] = $asset->id;
            }

            // Create the certification request
            $certificationRequest = CertificationRequest::create($data);
            // Log the successful creation of the certification request
            info('Certification request created successfully: ' . $certificationRequest->id);
            $response = new CertificationRequestResource($certificationRequest);

            // Send an event to notify the admin about the certification request
            // event(new CertificationRequestedProceedEvent($certificationRequest));
            // dispatch the event to notify the admin about the certification request
            CertificationRequestedProceedEvent::dispatch($certificationRequest);
            
            DB::commit(); // Commit the transaction if everything is successful
            // Return the created certification request resource
            return ApiResponse::success($response, 'Certification request created successfully.', 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of an error
            // Log the error and return an error response
            info('Failed to create certification request: ' . $e->getMessage());
            // Return an error response with a 500 status code
            return ApiResponse::error($e->getMessage(), 'Failed to create certification request', 500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(CertificationRequest $certificationRequest)
    {
        $certificationRequest->load(['user', 'userSignature', 'credential']);
        // Check if the certification request exists
        if (!$certificationRequest) {
            return ApiResponse::error([], 'Certification request not found', 404);
        }
        // Return the certification request resource
        return ApiResponse::success(new CertificationRequestResource($certificationRequest), 'Certification request retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCertificationRequestRequest $request, CertificationRequest $certificationRequest)
    {
        $data = $request->validated();
        // approved can't be change
        if ($certificationRequest->status === 'approved' && $data['status'] !== 'approved') {
            return ApiResponse::error([], 'Cannot update an approved certification request', 403);
        }
        // rejected can be approved
        if ($certificationRequest->status === 'rejected' && $data['status'] !== 'approved') {
            return ApiResponse::error([], 'You can only update a rejected certification request to approved', 403);
        }
        // pending can be approved or rejected
        if ($certificationRequest->status === 'pending' && !in_array($data['status'], ['approved', 'rejected'])) {
            return ApiResponse::error([], 'You can only update a pending certification request to approved or rejected', 403);
        }

        try {
            // Begin a database transaction
            DB::beginTransaction();
            // Check if the certification request is rejected send a mail to the user
            if (($certificationRequest->status === 'pending' || $certificationRequest->status === 'rejected') 
            && $data['status'] === 'approved') {
                // Here you can send an email to the user notifying them of the approval
                // Mail::to($certificationRequest->user->email)->send(new CertificationRequestApprovedMail($certificationRequest));

                $serial_no = CustomGenerator::generateCertificateSerialNo();
                $req = (object) [
                    'user_id' => $certificationRequest->user_id,
                    'full_name' => $certificationRequest->full_name,
                    'certification_request_id' => $certificationRequest->id,
                    'serial_no' => $certificationRequest->user_id,
                    'qr_code' => config('app.frontend_certificate_verify_url') . $serial_no,
                    'issued_on' => Carbon::today()->format('Y-m-d'),
                    'expires_on' => 
                        date('Y-m-d', 
                        strtotime('+ ' . $certificationRequest->certification->duration . '' . $certificationRequest->certification->duration_unit)),
                ];
                return $mem = (new MembershipController)->store($req);
            }
            // Check if the certification request is approved send a mail to the user
            if ($certificationRequest->status === 'pending' && $data['status'] === 'rejected') {
                // Here you can send an email to the user notifying them of the rejection
                // Mail::to($certificationRequest->user->email)->send(new CertificationRequestRejectedMail($certificationRequest));
            }
            // Update the certification request with the validated data
            $certificationRequest->update($data);
            $certificationRequest->load(['user', 'userSignature', 'credential']);
            // Log the successful update of the certification request
            info('Certification request updated successfully: ' . $certificationRequest->id);
            $response = new CertificationRequestResource($certificationRequest);
            DB::commit(); // Commit the transaction if everything is successful
            // Return the updated certification request resource
            return ApiResponse::success($response, 'Certification request updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of an error
            // Log the error and return an error response
            info('Failed to update certification request: ' . $e->getMessage());
            // Return an error response with a 500 status code
            return ApiResponse::error($e->getMessage(), 'Failed to update certification request', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CertificationRequest $certificationRequest)
    {
        try {
            // Begin a database transaction
            DB::beginTransaction();
            // Delete the certification request
            $certificationRequest->delete();
            // Log the successful deletion of the certification request
            info('Certification request deleted successfully: ' . $certificationRequest->id);
            DB::commit(); // Commit the transaction if everything is successful
            // Return a success response
            return ApiResponse::success([], 'Certification request deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of an error
            // Log the error and return an error response
            info('Failed to delete certification request: ' . $e->getMessage());
            // Return an error response with a 500 status code
            return ApiResponse::error($e->getMessage(), 'Failed to delete certification request', 500);
        }
    }

    /**
     * Approve the specified certification request.
     */
    public function approve(CertificationRequest $certificationRequest)
    {
        try {
            // Begin a database transaction
            DB::beginTransaction();
            // Update the status to 'approved'
            $certificationRequest->status = 'approved';
            $certificationRequest->save();
            // Log the successful approval of the certification request
            info('Certification request approved successfully: ' . $certificationRequest->id);
            DB::commit(); // Commit the transaction if everything is successful
            // Return a success response
            return ApiResponse::success(new CertificationRequestResource($certificationRequest), 'Certification request approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of an error
            // Log the error and return an error response
            info('Failed to approve certification request: ' . $e->getMessage());
            // Return an error response with a 500 status code
            return ApiResponse::error($e->getMessage(), 'Failed to approve certification request', 500);
        }
    }
    /**
     * Reject the specified certification request.
     */
    public function reject(CertificationRequest $certificationRequest)
    {
        try {
            // Begin a database transaction
            DB::beginTransaction();
            // Update the status to 'rejected'
            $certificationRequest->status = 'rejected';
            $certificationRequest->save();
            // Log the successful rejection of the certification request
            info('Certification request rejected successfully: ' . $certificationRequest->id);
            DB::commit(); // Commit the transaction if everything is successful
            // Return a success response
            return ApiResponse::success(new CertificationRequestResource($certificationRequest), 'Certification request rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of an error
            // Log the error and return an error response
            info('Failed to reject certification request: ' . $e->getMessage());
            // Return an error response with a 500 status code
            return ApiResponse::error($e->getMessage(), 'Failed to reject certification request', 500);
        }
    }
    /**
     * Restore the specified certification request.
     */
    public function restore(CertificationRequest $certificationRequest)
    {
        try {
            // Begin a database transaction
            DB::beginTransaction();
            // Restore the certification request
            $certificationRequest->restore();
            // Log the successful restoration of the certification request
            info('Certification request restored successfully: ' . $certificationRequest->id);
            DB::commit(); // Commit the transaction if everything is successful
            // Return a success response
            return ApiResponse::success(new CertificationRequestResource($certificationRequest), 'Certification request restored successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of an error
            // Log the error and return an error response
            info('Failed to restore certification request: ' . $e->getMessage());
            // Return an error response with a 500 status code
            return ApiResponse::error($e->getMessage(), 'Failed to restore certification request', 500);
        }
    }

    /**
     * Display a listing of the trashed certification requests.
     */
    public function trashed()
    {
        // Get all trashed certification requests
        $certificationRequests = CertificationRequest::onlyTrashed()->with(['user'])->get();
        // Check if there are any trashed certification requests
        if ($certificationRequests->isEmpty()) {
            return ApiResponse::error([], 'No trashed certification requests found', 404);
        }
        // Return the trashed certification requests resource
        $data = CertificationRequestResource::collection($certificationRequests);
        return ApiResponse::success($data, 'Trashed certification requests retrieved successfully.');
    }

    /**
     * Force delete the specified certification request.
     */
    public function forceDelete(CertificationRequest $certificationRequest)
    {
        try {
            // Begin a database transaction
            DB::beginTransaction();
            // Force delete the certification request
            $certificationRequest->forceDelete();
            // Log the successful force deletion of the certification request
            info('Certification request force deleted successfully: ' . $certificationRequest->id);
            DB::commit(); // Commit the transaction if everything is successful
            // Return a success response
            return ApiResponse::success([], 'Certification request force deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of an error
            // Log the error and return an error response
            info('Failed to force delete certification request: ' . $e->getMessage());
            // Return an error response with a 500 status code
            return ApiResponse::error($e->getMessage(), 'Failed to force delete certification request', 500);
        }
    }
    /**
     * Search for certification requests by user name.
     */
    public function searchByUserName(string $name)
    {
        // Search for certification requests by user name
        $certificationRequests = CertificationRequest::whereHas('user', function ($query) use ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        })->with(['user'])->get();

        // Check if there are any certification requests found
        if ($certificationRequests->isEmpty()) {
            return ApiResponse::error([], 'No certification requests found for the specified user name', 404);
        }

        // Return the certification requests resource
        $data = CertificationRequestResource::collection($certificationRequests);
        return ApiResponse::success($data, 'Certification requests retrieved successfully.');
    }
    /**
     * Search for certification requests by user email.
     */
    public function searchByUserEmail(string $email)
    {
        // Search for certification requests by user email
        $certificationRequests = CertificationRequest::whereHas('user', function ($query) use ($email) {
            $query->where('email', 'like', '%' . $email . '%');
        })->with(['user'])->get();

        // Check if there are any certification requests found
        if ($certificationRequests->isEmpty()) {
            return ApiResponse::error([], 'No certification requests found for the specified user email', 404);
        }

        // Return the certification requests resource
        $data = CertificationRequestResource::collection($certificationRequests);
        return ApiResponse::success($data, 'Certification requests retrieved successfully.');
    }
}
