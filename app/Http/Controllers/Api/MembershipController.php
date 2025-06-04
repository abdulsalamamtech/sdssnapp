<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipRequest;
use App\Http\Requests\UpdateMembershipRequest;
use App\Http\Resources\MembershipResource;
use App\Models\Api\Membership;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $memberships = Membership::latest()->paginate();

        // Check if there are any memberships
        if ($memberships->isEmpty()) {
            return ApiResponse::error([], 'No memberships found', 404);
        }
        $data = MembershipResource::collection($memberships);
        // Return the memberships resource
        return ApiResponse::success($data, 'memberships retrieved successfully.');
            
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMembershipRequest $request)
    {
        // 'user_id',
        // 'full_name',
        // 'certification_request_id',
        // 'issued_on',
        // 'expires_on',
        // 'serial_no',
        // 'qr_code',
        // Create a new membership
        $membership = Membership::create($request->validated());

        // Return the created membership resource
        return ApiResponse::success(new MembershipResource($membership), 'Membership created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Membership $membership)
    {
        // Check if the membership exists
        if (!$membership) {
            return ApiResponse::error([], 'Membership not found', 404);
        }

        // Return the membership resource
        return ApiResponse::success(new MembershipResource($membership), 'Membership retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMembershipRequest $request, Membership $membership)
    {
        // Check if the membership exists
        if (!$membership) {
            return ApiResponse::error([], 'Membership not found', 404);
        }

        // Update the membership with validated data
        $membership->update($request->validated());

        // Return the updated membership resource
        return ApiResponse::success(new MembershipResource($membership), 'Membership updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membership $membership)
    {
        // Check if the membership exists
        if (!$membership) {
            return ApiResponse::error([], 'Membership not found', 404);
        }

        // Delete the membership
        $membership->delete();

        // Return success response
        return ApiResponse::success([], 'Membership deleted successfully.', 204);
    }

}
