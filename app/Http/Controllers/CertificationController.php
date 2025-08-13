<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreCertificationRequest;
use App\Http\Requests\UpdateCertificationRequest;
use App\Http\Resources\CertificationResource;
use App\Models\Certification;
use Illuminate\Support\Facades\DB;

class CertificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certifications = Certification::with([
            'managementSignature.signature',
            'secretarySignature.signature',
            'createdBy'
        ])->latest()->paginate();
        // Check if there are any certifications
        if ($certifications->isEmpty()) {
            return ApiResponse::error([], 'coming soon', 404);
        }
        $data = CertificationResource::collection($certifications);
        // Return the certifications resource
        return ApiResponse::success($data, 'certifications retrieved successfully.', 200, $certifications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCertificationRequest $request)
    {
        $data = $request->validated();
        // check if the management and secretary is the same
        if ($data['management_signature_id'] === $data['secretary_signature_id']) {
            return ApiResponse::error([], 'The president and secretary signature cannot be the same!, please reselect the secretary signature', 400);
        }
        try {
            //code...
            DB::beginTransaction();
            // create by the authenticated user
            $data['created_by'] = auth()?->user()->id; // Set the created_by field to the authenticated user
            // initial_amount is 10% of the amount
            // if (!isset($data['initial_amount']) && !$data['initial_amount']) {
                // If initial_amount is provided, use it
                // $data['initial_amount'] = number_format($data['initial_amount'], 2, '.', '');
                // If initial_amount is not provided, calculate it as 10% of the amount
                $data['initial_amount'] = $data['amount'] + (($data['amount'] * 10) / 100); // Set initial_amount if not provided
            // }
            $certification = Certification::create($data);
            $certification->load(['managementSignature.signature', 'secretarySignature.signature', 'createdBy']);
            // Log the successful creation of the certification
            info('Certification created successfully: ' . $certification->id);
            $response = new CertificationResource($certification);
            DB::commit(); // Commit the transaction if everything is successful
            // Return the created certification resource
            return ApiResponse::success($response, 'Certification created successfully.', 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of an error
            // Log the error and return an error response
            info('Failed to create certification: ' . $e->getMessage());
            // Return an error response with a 500 status code
            return ApiResponse::error($e->getMessage(), 'Failed to create certification', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Certification $certification)
    {
        $certification->load(['managementSignature.signature', 'secretarySignature.signature', 'createdBy']);
        // response resource
        $response = new CertificationResource($certification);
        return ApiResponse::success($response, 'Certification retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCertificationRequest $request, Certification $certification)
    {
        $data = $request->validated();
        // check if the management and secretary is the same
        if ($data['management_signature_id'] === $data['secretary_signature_id']) {
            return ApiResponse::error([], 'The president and secretary signature cannot be the same!, please reselect the secretary signature', 400);
        }
        try {
            //code...
            DB::beginTransaction();
            // update by the authenticated user
            $data['updated_by'] = auth()?->user()?->id; // Set the updated_by field to the authenticated user
            // Update the certification
            if (isset($data['management_signature_id']) && $data['management_signature_id'] === null) {
                // If management_signature_id is null, remove it from the data array
                unset($data['management_signature_id']);
            }
            $certification->update($data);
            $certification->load(['managementSignature.signature', 'secretarySignature.signature', 'createdBy']);
            $response = new CertificationResource($certification);
            DB::commit(); // Commit the transaction if everything is successful
            // Return the updated certification resource
            info('Certification updated successfully: ' . $certification->id);
            return ApiResponse::success($response, 'Certification updated successfully.');
        } catch (\Exception $e) {
            // Log the error and return an error response
            info('Error updating certification: ' . $e->getMessage());
            return ApiResponse::error($e->getMessage(), 'Failed to update certification', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certification $certification)
    {
        try {
            //code...
            // update the deleted_by field
            $certification->deleted_by = auth()?->user()?->id; // Set the deleted_by field to the authenticated user
            $certification->save();
            // Soft delete the certification
            $certification->delete();
            // Return a success response
            return ApiResponse::success([], 'Certification deleted successfully.', 200);
        } catch (\Exception $e) {
            // Log the error and return an error response
            info('Error deleting certification: ' . $e->getMessage());
            return ApiResponse::error($e->getMessage(), 'Failed to delete certification', 500);
        }
    }

    /**
     * [public] Display all available certification to user.
     */
    public function available()
    {
        // $certifications = Certification::with(['ManagementSignature.signature'])->get();
        $certifications = Certification::latest()->limit(6)->paginate(6);
        // Check if there are any certifications
        if ($certifications->isEmpty()) {
            return ApiResponse::error([], 'coming soon', 404);
        }
        $data = CertificationResource::collection($certifications);
        // Return the certifications resource
        return ApiResponse::success($data, 'certifications retrieved successfully.', 200, $certifications);
    }
}
