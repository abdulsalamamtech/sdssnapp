<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCertificationRequestRequest;
use App\Http\Requests\UpdateCertificationRequestRequest;
use App\Models\Api\CertificationRequest;

class CertificationRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCertificationRequestRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CertificationRequest $certificationRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCertificationRequestRequest $request, CertificationRequest $certificationRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CertificationRequest $certificationRequest)
    {
        //
    }
}
