<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Admin: create a new permission
     */
    public function store(Request $request)
    {
        // Validate request data
        $data = $request->validate(
            [
            'name' => ['required', 'string','max:32'],
            ]
        );

        // Create a new permission
        $permission = Permission::create($data);

        // Return the newly created permission
        return ApiResponse::success($permission, 'Permission created successfully', 201);
    }
}
