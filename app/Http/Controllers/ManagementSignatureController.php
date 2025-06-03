<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreManagementSignatureRequest;
use App\Http\Requests\UpdateManagementSignatureRequest;
use App\Http\Resources\ManagementSignatureResource;
use App\Models\Assets;
use App\Models\ManagementSignature;
use Illuminate\Support\Facades\DB;

class ManagementSignatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $managementSignatures = ManagementSignature::with(['signature', 'createdBy'])->get();
                // Check if there are any management signatures
        if($managementSignatures->isEmpty()){
            return ApiResponse::error([], 'No management signatures found', 404);
        }
        $data = ManagementSignatureResource::collection($managementSignatures);
        return ApiResponse::success($data, 'Management signatures retrieved successfully.');
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreManagementSignatureRequest $request)
    {
        $data = $request->validated();
        try {
            //code...
            DB::beginTransaction();
            // Handle file upload if signature is provided
            // Upload Asset if exists
            if ($request->hasFile('signature')) {
                $cloudinaryImage = $request->file('signature')->storeOnCloudinary('sdssn-app');
    
                $url = $cloudinaryImage->getSecurePath();
                $public_id = $cloudinaryImage->getPublicId();
    
                info('Management signature image uploaded to Cloudinary: ' . $url);
    
                $asset = Assets::create([
                    'original_name' => 'management signature image',
                    'path' => 'image',
                    'hosted_at' => 'cloudinary',
                    'name' =>  $cloudinaryImage->getOriginalFileName(),
                    'description' => 'management signature file upload',
                    'url' => $url,
                    'file_id' => $public_id,
                    'type' => $cloudinaryImage->getFileType(),
                    'size' => $cloudinaryImage->getSize(),
                ]);
    
                $data['signature_id'] = $asset->id;
            }
            // Create the management signature
            $data['created_by'] = auth()?->user()?->id; // Set the created_by field to the authenticated user
            $managementSignature = ManagementSignature::create($data);

            // Commit the transaction
            DB::commit();

            // Return the created management signature resource
            $response = new ManagementSignatureResource($managementSignature->load('signature', 'createdBy'));
            return ApiResponse::success($response, 'Management signature created successfully.');
            
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            info('Error creating management signature: ' . $th->getMessage());
            return ApiResponse::error('Failed to create management signature: ' . $th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ManagementSignature $managementSignature)
    {
        // Load the signature relationship
        $managementSignature->load('signature', 'createdBy');
        $response = new ManagementSignatureResource($managementSignature);
        return ApiResponse::success($response, 'Management signature retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateManagementSignatureRequest $request, ManagementSignature $managementSignature)
    {
        $data = $request->validated();
        try {
            //code...
            DB::beginTransaction();
            // Handle file upload if signature is provided
            if ($request->hasFile('signature')) {
                $cloudinaryImage = $request->file('signature')->storeOnCloudinary('sdssn-app');

                $url = $cloudinaryImage->getSecurePath();
                $public_id = $cloudinaryImage->getPublicId();

                // Update the asset or create a new one
                if ($managementSignature->signature) {
                    $managementSignature->signature->update([
                        'original_name' => 'management signature image',
                        'path' => 'image',
                        'hosted_at' => 'cloudinary',
                        'name' =>  $cloudinaryImage->getOriginalFileName(),
                        'description' => 'management signature file upload',
                        'url' => $url,
                        'file_id' => $public_id,
                        'type' => $cloudinaryImage->getFileType(),
                        'size' => $cloudinaryImage->getSize(),
                    ]);
                } else {
                    $asset = Assets::create([
                        'original_name' => 'management signature image',
                        'path' => 'image',
                        'hosted_at' => 'cloudinary',
                        'name' =>  $cloudinaryImage->getOriginalFileName(),
                        'description' => 'management signature file upload',
                        'url' => $url,
                        'file_id' => $public_id,
                        'type' => $cloudinaryImage->getFileType(),
                        'size' => $cloudinaryImage->getSize(),
                    ]);
                    $data['signature_id'] = $asset->id;
                }
            }
                    
            // Update the management signature
            $data['updated_by'] = auth()?->user()?->id; // Set the updated_by field to the authenticated user
            
            $managementSignature->update($data);
            // Reload the management signature to include the updated signature
            $managementSignature->load('signature');
            DB::commit();
            // Return the updated management signature resource
            $response = new ManagementSignatureResource($managementSignature);
            // Return success response
            return ApiResponse::success($response, 'Management signature updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            info('Error updating management signature: ' . $th->getMessage());
            return ApiResponse::error('Failed to update management signature: ' . $th->getMessage(), 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManagementSignature $managementSignature)
    {
        $managementSignature->delete();
        return ApiResponse::success(null, 'Management signature deleted successfully.');
    }
}
