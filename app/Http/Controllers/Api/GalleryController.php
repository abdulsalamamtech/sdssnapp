<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GalleryRequest;
use App\Models\Assets;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::with(['banner'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if (!$galleries) {
            return $this->sendError([], 'unable to load galleries', 404);
        }

        return $this->sendSuccess($galleries, 'successful', 200);        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GalleryRequest $request)
    {
        
        $data = $request->validated();
        $user = $request->user();

        try {
            //code...
            // $upload =  $this->uploadImage($request, 'banner');
            $upload = $this->uploadToImageKit($request,'banner');
    
            // Add assets
            $banner = Assets::create($upload);
            $data['banner_id'] = $banner->id;
            $data['user_id'] = $user->id;
    
            // Generate slug
            $title = $data['title'];
            $slug = Str::slug($title);
    
            $slug_fund = Gallery::where('slug', $slug)->first();
       
            ($slug_fund)
            ?$data['slug'] = $slug.'-'.rand(100,999)
            :$data['slug'] = $slug;
    
            info('Create Gallery Data: ', [$data]);
    
            // Add project
            $gallery = Gallery::create($data);
            $gallery->load(['user', 'banner']);
    
    
            if (!$gallery) {
                return $this->sendError([], 'unable to create gallery', 404);
            }
        } catch (\Throwable $th) {
            //throw $th;
            info('Create Gallery Exceptions: ', [$th->getMessage()]);

            return $this->sendError([], 'unable to create gallery', 500);

        }

        return $this->sendSuccess($gallery, 'gallery created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        $gallery->load([ 'banner']);

        if (!$gallery) {
            return $this->sendError([], 'unable to load gallery', 500);
        }

        return $this->sendSuccess($gallery, 'successful', 200);    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request  $request, Gallery $gallery)
    {
        $data = $request->validate([
            'banner' => ['nullable', 'image', 'max:5120'], // 5MB limit
            'title' => ['required','string', 'max:500'],
            'description' => ['nullable','string'],
        ]);
        // Remove empty data
        $data = array_filter($data);
        // return $data = array_filter($data, function($value) {
        //     return $value!== null;
        // });

        $user = $request->user();

        // return $gallery;
        try {
            DB::beginTransaction();

            if($gallery->title != $data['title']){
                // Generate slug
                $title = $data['title'];
                $slug = Str::slug($title);

                $slug_fund = Gallery::where('slug', $slug)->first();
                
                ($slug_fund)
                ?$data['slug'] = $slug.'-'.rand(100,999)
                :$data['slug'] = $slug;

            }


            // if ($user->id != $gallery->user_id) {
            //     return $this->sendError([], "you are unauthorize, you can't update media uploaded by another admin", 401);
            // }

            if($request->banner){

                // Delete the previously uploaded banner
                // Update the code to delete the previously uploaded banner
                $upload = $this->uploadToImageKit($request,'banner');

                // Add assets
                $banner = Assets::create($upload);
                $data['banner_id'] = $banner->id;

                // Delete previously uploaded file
                $fileId = $gallery->banner->file_id;
                $previousFile = $this->deleteImageKitFile($fileId);
                Assets::where('file_id', $fileId)->delete();

            }

            $gallery->update($data);
            $gallery->load(['user', 'banner']);

            DB::commit();

            return $this->sendSuccess($gallery, 'gallery updated', 200);

        } catch (\Exception $e) {
            // Handle transaction failure
            DB::rollBack();
            return $this->sendError([], 'unable to update gallery, try again later!', 500);

        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        $user = request()->user();

        if ($user->id!= $gallery->user_id) {
            return $this->sendError([], 'you are unauthorize', 401);
        }

        $fileId = $gallery->banner->file_id;
        $previousFile = $this->deleteImageKitFile($fileId);
        Assets::where('file_id', $fileId)->delete();

        if (!$gallery->delete()) {
            return $this->sendError([], "you are unauthorize, you can't delete media uploaded by another admin", 401);
        }

        return $this->sendSuccess([], 'gallery deleted', 200);
    }
}
