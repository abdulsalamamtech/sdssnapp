<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Admin: Display a listing of the resource.
     */
    public function index(){

        // Fetch all activities from the database
        $activities = Activity::latest()->paginate();

        // Add metadata to the response
        $metadata = $activities;

        // Check if there are any activities
        if($activities->isEmpty()){
            return ApiResponse::success([], 'No activities found', 200);
        }

        // Transform the items
        $data = ActivityResource::collection($activities);

        // Return response
        return ApiResponse::success($data, 'successful', 200, $metadata);
    }
}
