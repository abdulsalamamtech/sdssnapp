<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Assets;
use App\Models\Partner;
use Illuminate\Http\Request;

class OurPartners extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $our_partners = Partner::with('banner')->latest()->paginate();
        return ApiResponse::success($our_partners, 'successful');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'banner' => ['required', 'image', 'max:5480'], // 2MB size
        ]);

        // upload banner to cloudinary server
        // Upload Asset if exists
        if ($request->hasFile('banner')) {

            $cloudinaryImage = $request->file('banner')->storeOnCloudinary('sdssn-app');
            $url = $cloudinaryImage->getSecurePath();
            $public_id = $cloudinaryImage->getPublicId();
    
            // dd($cloudinaryImage);
            // return [$cloudinaryImage];
            // 'original_name',
            // 'name',
            // 'type',
            // 'path',
            // 'file_id',
            // 'url',
            // 'size',
            // 'hosted_at',
            // 'active',
            $asset = Assets::create([
                'original_name' => 'partner image',
                'path' => 'image',
                'hosted_at' => 'cloudinary',
                'name' =>  $cloudinaryImage->getOriginalFileName(),
                'description' => 'assignment file upload',
                'url' => $url,
                'file_id' => $public_id,
                'type' => $cloudinaryImage->getFileType(),
                'size' => $cloudinaryImage->getSize(),
            ]);

            $data['banner_id'] = $asset->id;
        }        

        $partner = Partner::create($data);
        $partner->load(['banner']);

        return ApiResponse::success($partner, 'partner created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $partner = Partner::with('banner')->find($id);

        if (!$partner) {
            return ApiResponse::error([], 'partner not found', 404);
        }

        return ApiResponse::success($partner, 'partner retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $partner = Partner::find($id);

        if (!$partner) {
            return ApiResponse::error([], 'partner not found', 404);
        }

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['required','string','max:255'],
            'banner' => ['nullable', 'image', 'max:5480'], // 2MB size
        ]);

        if ($request->hasFile('banner')) {

            $cloudinaryImage = $request->file('banner')->storeOnCloudinary('sdssn-app');
            $url = $cloudinaryImage->getSecurePath();
            $public_id = $cloudinaryImage->getPublicId();


            $asset = Assets::create([
                'original_name' => 'partner image',
                'path' => 'image',
                'hosted_at' => 'cloudinary',
                'name' =>  $cloudinaryImage->getOriginalFileName(),
                'description' => 'assignment file upload',
                'url' => $url,
                'file_id' => $public_id,
                'type' => $cloudinaryImage->getFileType(),
                'size' => $cloudinaryImage->getSize(),
            ]);

            $data['banner_id'] = $asset->id;
        }

        $partner->update($data);
        $partner->load(['banner']);
        return ApiResponse::success($partner, 'partner updated successfully');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $partner = Partner::find($id);

        if (!$partner) {
            return ApiResponse::error([], 'partner not found', 404);
        }

        $partner->delete();

        return ApiResponse::success([], 'partner deleted successfully');
    }
}
