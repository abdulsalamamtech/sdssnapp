<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PartnerRequest;
use App\Http\Requests\Api\UpdatePartnerRequest;
use App\Models\Assets;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partners = Partner::with(['banner'])->latest()->paginate();
        $metadata = $this->getMetadata($partners);

        if (!$partners) {
            return $this->sendError([], 'unable to load partners', 500);
        }

        return $this->sendSuccess($partners, 'successful', 200, $metadata);
    }

    /**
     * Store a newly created resource in storage.
     * @param string $banner
     * @param string $name
     * @param string $description
     */
    public function store(PartnerRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        try {
            DB::beginTransaction();

            if($request->hasFile('banner')){
                $upload = $this->uploadToImageKit($request,'banner');
    
                // Add assets
                $banner = Assets::create($upload);
                $data['banner_id'] = $banner->id;
                $data['user_id'] = $user->id;
            }
            
            // Add partner
            $partner = Partner::create($data);

            if (!$partner) {
                return $this->sendError([], 'unable to update partner', 500);
            }
            $partner->load(['banner']);

            DB::commit();
            return $this->sendSuccess($partner, 'partner created', 201);

        } catch (\Exception $e) {
            // Handle transaction failure
            DB::rollBack();
            return $this->sendError([], 'unable to create partner, try again later!', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Partner $partner)
    {

        $partner->load(['banner']);

        if (!$partner) {
            return $this->sendError([], 'unable to load partner', 500);
        }

        return $this->sendSuccess($partner, 'successful', 200);
    }

    /**
     * Update the specified resource in storage.
     * @param string $banner
     * @param string $name
     * @param string $description
     */
    public function update(UpdatePartnerRequest $request, Partner $partner)
    {
        $data = $request->validated();
        $user = $request->user();

        try {
            DB::beginTransaction();

            if($request->banner){

                // Delete the previously uploaded banner
                // Update the code to delete the previously uploaded banner
                $upload = $this->uploadToImageKit($request,'banner');

                // Add assets
                $banner = Assets::create($upload);
                $data['banner_id'] = $banner->id;

                // Delete previously uploaded file
                $fileId = $partner->banner->file_id;
                $previousFile = $this->deleteImageKitFile($fileId);
                Assets::where('file_id', $fileId)->delete();

            }

            $partner->update($data);
            $partner->load(['banner']);

            if (!$partner) {
                return $this->sendError([], 'unable to update partner', 500);
            }

            DB::commit();
            return [$partner, $data];
            return $this->sendSuccess($partner, 'partner updated', 200);

        } catch (\Exception $e) {
            // Handle transaction failure
            DB::rollBack();
            return $this->sendError([], 'unable to update partner, try again later!', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partner $partner)
    {
        $user = request()->user();

        if ($user->id == $partner->user_id 
        || $user->role == 'super-admin' 
        || $user->role == 'admin' 
        || $user->role == 'moderator') {

            $partner->delete();
            return $this->sendSuccess([], 'partner deleted', 200);
        }else{
            
            return $this->sendError([], 'you are unauthorize', 401);
        }        
    }

}
