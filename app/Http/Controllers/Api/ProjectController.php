<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProjectRequest;
use App\Http\Requests\Api\UpdateProjectRequest;
use App\Models\Api\Project;
use App\Models\Assets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * [public] Display a listing of the resource.
     */
    public function index()
    {
        // ['public', 'private', 'draft'])
        $projects = Project::whereNotNull('approved_by')
            ->where('status', 'public')
            ->with(['user', 'comments.user', 'banner'])
            ->get();

            // ->orderBy('created_at', 'desc')
            // ->paginate(10);
            // return $projects;

        if ($projects->isEmpty()) {
            return ApiResponse::error([], 'projects not found', 404);
        }

        return ApiResponse::success($projects, 'successful', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        try {
            DB::beginTransaction();

            // $upload =  $this->uploadImage($request, 'banner');
            $upload = $this->uploadToImageKit($request,'banner');

            // Add assets
            $banner = Assets::create($upload);
            $data['banner_id'] = $banner->id;
            $data['user_id'] = $user->id;
    
            // ['public', 'private', 'draft']
            if($data['status'] == 'public'){
                $data['approved_by'] = $user->id;
            }
    
            // Generate slug
            $title = $data['title'];
            $slug = Str::slug($title);
    
            $slug_fund = Project::where('slug', $slug)->first();
            ($slug_fund)
            ?$data['slug'] = $slug.'-'.rand(100,999)
            :$data['slug'] = $slug;
    
    
            // Add project
            $project = Project::create($data);
            
            
            $project->load(['user', 'comments.user', 'banner']);
            DB::commit();

            if (!$project) {
                return $this->sendError([], 'unable to store project', 500);
            }

        } catch (\Throwable $th) {
            //throw $th;
            // Handle transaction failure
            DB::rollBack();
            info('Exception creating project', [$th->getMessage()]);
            return $this->sendError([], 'unable to create project', 500);

        }

        return $this->sendSuccess($project, 'project created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->views++;
        $project->save();
        $project->load(['user', 'comments.user', 'banner']);

        if (!$project) {
            return $this->sendError([], 'unable to load project', 500);
        }

        return $this->sendSuccess($project, 'successful', 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {

        $data = $request->validated();
        $user = $request->user();

        // Authorized access to the project
        if(!$this->authorized($user->id, $project->user_id)){
            return $this->sendError([], 'you are unauthorize!', 401);
        }

        
        try {
            DB::beginTransaction();
            
            // Project title as slug
            if($project->title != $data['title']){
                // Generate slug
                $title = $data['title'];
                $slug = Str::slug($title);

                $slug_fund = Project::where('slug', $slug)->first();
                
                ($slug_fund)
                ?$data['slug'] = $slug.'-'.rand(100,999)
                :$data['slug'] = $slug;
            }


            // return [$data, $request->banner];

            // If the banner is updated
            if($request->banner){

                // Upload the image
                $upload = $this->uploadToImageKit($request,'banner');

                // Add new assets
                $banner = Assets::create($upload);
                $data['banner_id'] = $banner->id;

                // Delete previously uploaded file
                $fileId = $project->banner?->file_id;
                if($fileId){
                    $previousFile = $this->deleteImageKitFile($fileId);
                    $assetFile = Assets::where('file_id', $fileId)?->first();
                    if($assetFile){
                        $assetFile->delete();
                    }     
                }

            }

            
            
            // ['public', 'private', 'draft']
            if($request->status){
                if($data['status'] == 'public'){
                    $data['approved_by'] = $user->id;
                }
            }
            

            $project->update($data);
            $project->load(['user','comments.user', 'banner']);

            if (!$project) {
                return $this->sendError([], 'unable to update project', 500);
            }

            DB::commit();
            return $this->sendSuccess($project, 'project updated', 200);

        } catch (\Exception $e) {
            // Handle transaction failure
            DB::rollBack();
            return $this->sendError([$e], 'unable to update project, try again later!', 500);

        }

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {

        $user = request()->user();

        if(!$this->authorized($user->id, $project->user_id)){
            return $this->sendError([], 'you are unauthorize!', 401);
        }

        $project->delete();
        return $this->sendSuccess([], 'project deleted!', 200);


        // Add roles you want to allow access to
        // $accessible = ['admin', 'dev', 'super-admin', 'moderator'];
        // if($user->id !== $project->user_id){
        //     if(!in_array(request()->user()->role, $accessible)){
        //         return $this->sendError([], 'you are unauthorize!', 401);
        //     }
        // }
        // return $this->sendSuccess([], 'project deleted', 200);
        

    }


    /**
     * Show all user project resource from storage.
     */
    public function personal(Request $request)
    {

        $user = $request->user();

        $project = Project::where('user_id', $user->id)
            ->with(['user', 'comments.user', 'banner'])
            ->latest()->get();

        if ($project->isEmpty()) {
            return $this->sendError([], 'personal project not found', 404);
        }

        return $this->sendSuccess($project, 'successful', 200, $this->getMetadata($project));
    }


    /**
     * Show all user private project resource from storage.
     */
    public function private(Request $request)
    {

        $user = $request->user();

        $project = Project::where('user_id', $user->id)
            ->where('status', '!=', 'public')
            ->with(['user', 'comments.user', 'banner'])
            ->latest()->get();

        if ($project->isEmpty()) {
            return $this->sendError([], 'private projects not found', 404);
        }

        return $this->sendSuccess($project, 'successful', 200, $this->getMetadata($project));
    }


    /**
     * Show all user public project resource from storage.
     */
    public function public(Request $request)
    {

        $user = $request->user();

        $project = Project::where('user_id', $user->id)
            ->where('status', 'public')
            ->with(['user', 'comments.user', 'banner'])
            ->latest()->get();

        if ($project->isEmpty()) {
            return $this->sendError([], 'public projects not found', 404);
        }

        return $this->sendSuccess($project, 'successful', 200, $this->getMetadata($project));
    }


     /**
     * Like a project.
     */
    public function like(Request $request, Project $project)
    {

        $project->likes++;
        $project->save();
        $project->load(['user', 'comments.user', 'banner']);

        if ($project->isEmpty()) {
            return $this->sendError([], 'unable to like project', 404);
        }

        return $this->sendSuccess($project, 'successful', 200);

    }


     /**
     * Share a project.
     */
    public function share(Project $project)
    {

        $project->shares++;
        $project->save();

        $project->load(['user', 'comments.user', 'banner']);

        if ($project->isEmpty()) {
            return $this->sendError([], 'unable to load project', 404);
        }

        return $this->sendSuccess($project, 'successful', 200);

    }


     /**
     * Approve a project.
     */
    public function approve(Request $request, Project $project)
    {
        $user = $request->user();

        // ['public', 'private', 'draft']
        if($project->status == 'public'){
            $project->approved_by = $user->id;
        }
        $project->save();


        if (!$project) {
            return $this->sendError([], 'unable to load project', 404);
        }

        return $this->sendSuccess($project, 'project approved successfully', 200);

    }

     /**
     * Reject a project.
     */
    public function reject(Request $request, Project $project)
    {
        $user = $request->user();

        // ['public', 'private', 'draft']
        if($project->status == 'public'){
            $project->approved_by = null;
        }
        $project->save();


        if (!$project) {
            return $this->sendError([], 'unable to load project', 404);
        }

        return $this->sendSuccess($project, 'project rejected successfully', 200);

    }


    /**
     * User approved public project.
     */
    public function approved(Request $request)
    {

        $user = $request->user();

        $project = Project::where('approved_by', $user->id)->with(['user', 'comments.user', 'banner'])->get();

        if ($project->isEmpty()) {
            return $this->sendError([], 'unable to load projects', 404);
        }

        return $this->sendSuccess($project, 'successful', 200);
    }

    // Force delete
    public function forceDelete(Request $request, $project)
    {
        $user = $request->user();

        if ($user->role != 'admin') {
            return $this->sendError([], 'you are unauthorize', 401);
        }

        Project::where('id', $project)->forceDelete();

        return $this->sendSuccess($project, 'project deleted from trash', 200);
    }

    // Restore deleted project
    public function restore(Request $request, $project)
    {
        $user = $request->user();

        if ($user->role != 'admin') {
            return $this->sendError([], 'you are unauthorize', 401);
        }

        Project::where('id', $project)->restore();

        return $this->sendSuccess($project, 'project restored successfully', 200);
    }

    // Get trashed project
    public function trash(Request $request)
    {

        $user = $request->user();

        if ($user->role != 'admin') {
            return $this->sendError([], 'you are unauthorize', 401);
        }

        $project = Project::onlyTrashed()->with(['user', 'comments.user', 'banner'])->get();

        if ($project->isEmpty()) {
            return $this->sendError([], 'unable to load trashed projects', 404);
        }

        return $this->sendSuccess($project, 'successful', 200);
    }


    /**
     * Search for a public project.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        if(!$query){
            return $this->sendError([], 'invalid search query', 400);
        }

        // return $query;
        $podcasts = Project::whereNotNull('approved_by')
            ->where('status', 'public')
            ->whereAny([
                'title',
                'slug',
                'category',
                'description',
                'tags',
                'created_at',
            ], 'like', '%' . $query . '%')
            ->with(['user', 'comments.user', 'banner'])
            ->latest()
            ->limit(20)
            ->get();

        if (!$podcasts) {
            return $this->sendError([], 'unable to load podcast', 500);
        }

        return $this->sendSuccess($podcasts, 'successful', 200);

    }


    public function allProjects()
    {
        $projects = Project::where('status', 'public')
            ->with(['user', 'comments.user', 'banner'])
            ->latest()->paginate();

            // return $projects;

        if (!$projects) {
            return $this->sendError([], 'unable to load projects', 500);
        }

        return $this->sendSuccess($projects, 'successful', 200);
    }


    // Authorization methods
    private function authorized($auth_id, $data_user_id){
        $accessible = ['admin', 'dev', 'super-admin', 'moderator'];
        // Owner
        if($auth_id !== $data_user_id){
            // Admin
            if(!in_array(request()->user()->role, $accessible)){
                return false;
            }
        }
        return true;
        
    }
}
