<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreCredentialRequest;
use App\Http\Requests\UpdateCredentialRequest;
use App\Http\Resources\CredentialResource;
use App\Models\Credential;

class CredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $credentials = Credential::with(['file', 'createdBy'])->get();
        // Check if there are any credentials
        if($credentials->isEmpty()){
            return ApiResponse::error([], 'No credentials found', 404);
        }
        $data = CredentialResource::collection($credentials);
        return ApiResponse::success($data, 'Credential retrieved successfully.');
    
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
    public function show(Credential $credential)
    {
        return ApiResponse::success(new CredentialResource($credential), 'Credential retrieved successfully.');
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
