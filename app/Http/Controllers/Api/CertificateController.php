<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCertificateRequest;
use App\Http\Requests\Api\UpdateCertificateRequest;
use App\Models\Api\Certificate;
use App\Models\Assets;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all certificate with their associated relationships
        $certificates = Certificate::with(['user', 'addedBy'])->get();

        if (!$certificates) {
            return $this->sendError([], 'unable to load certificates', 500);
        }

        return $this->sendSuccess($certificates, 'successful', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCertificateRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $data['added_by'] = $user->id;

        // Upload data
        // $upload =  $this->uploadImage($request, 'certificate');
        $upload = $this->uploadToImageKit($request,'certificate');


        // Add assets
        $asset = Assets::create($upload);
        $data['asset_id'] = $asset->id;

        // Add certificate
        $certificate = Certificate::create($data);
        $certificate->load(['user', 'added_by']);

        if (!$certificate) {
            return $this->sendError([], 'unable to create certificate', 500);
        }

        return $this->sendSuccess($certificate, 'certificate created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        $certificate->load(['addedBy']);

        if(!$certificate){
            return $this->sendError([], 'certificate not found', 404);
        }

        return $this->sendSuccess($certificate, 'successful', 200);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCertificateRequest $request, Certificate $certificate)
    {

        $user = $request->user();
        $data = $request->validated();
        $data['added_by'] = $user->id;

        if($request->certificate){

            // Update the code to delete the previously uploaded banner
            $upload = $this->uploadToImageKit($request,'certificate');

            // Add assets
            $banner = Assets::create($upload);
            $data['banner_id'] = $banner->id;

            // Delete previously uploaded file
            $fileId = $certificate->banner->file_id;
            $previousFile = $this->deleteImageKitFile($fileId);
            Assets::where('file_id', $fileId)->delete();
        }

        // Add certificate
        $certificate->update($data);
        $certificate->load(['user', 'addedBy', 'certificate']);

        if (!$certificate) {
            return $this->sendError([], 'unable to update certificate', 500);
        }

        return $this->sendSuccess($certificate, 'certificate updated', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    // show certificates added by users
    public function personal(Request $request)
    {

        $user = $request->user();

        $certificate = Certificate::where('belong_to', $user->id)->with(['user', 'certificate', 'addedBy'])->get();

        if (!$certificate) {
            return $this->sendError([], 'unable to load your certificate', 500);
        }

        return $this->sendSuccess($certificate, 'successful', 200);
    }

    public function approved(Request $request)
    {

        $user = $request->user();

        $certificate = Certificate::where('added_by', $user->id)->with(['user', 'certificate', 'addedBy'])->get();

        if (!$certificate) {
            return $this->sendError([], 'unable to load your approved certificate', 500);
        }

        return $this->sendSuccess($certificate, 'successful', 200);
    }

}
