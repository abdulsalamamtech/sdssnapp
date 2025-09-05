<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreCredentialRequest;
use App\Http\Requests\UpdateCredentialRequest;
use App\Http\Resources\CredentialResource;
use App\Models\Assets;
use App\Models\Credential;

class CredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $credentials = Credential::with(['file', 'createdBy'])->get();
        // // Check if there are any credentials
        // if($credentials->isEmpty()){
        //     return ApiResponse::error([], 'No credentials found', 404);
        // }
        // $data = CredentialResource::collection($credentials);
        // return ApiResponse::success($data, 'Credential retrieved successfully.');

        $credentials = Assets::paginate(40);
        if ($credentials->isEmpty()) {
            return ApiResponse::error([], 'No asset found', 404);
        }
        $data = CredentialResource::collection($credentials);
        return ApiResponse::success($data, 'assets retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StoreCredentialRequest $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function show($credential)
    {
        $response = Assets::where('id', $credential)->first();
        if (!$response) {
            return ApiResponse::error([], 'Credential not found', 404);
        }
        // This mistake was in the previous code at credentialRequest controller too
        // $credential->load(['file', 'createdBy']);
        // $response = new CredentialResource($credential);
        return ApiResponse::success($response, 'Credential retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateCredentialRequest $request, Credential $credential)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Credential $credential)
    // {
    //     //
    // }
}
