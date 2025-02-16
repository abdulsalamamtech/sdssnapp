<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Certificate;
use App\Models\Api\Podcast;
use App\Models\Api\Project;
use App\Models\Assets;
use App\Models\Gallery;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['users'] = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'moderators' => User::where('role', 'moderator')->count(),
            'users' => User::where('role', 'user')->count(),
            'verified' => User::where('email_verified_at')->count(),
            'pending' => User::whereNot('email_verified_at')->count(),
            'male' => User::where('gender', 'male')->count(),
            'female' => User::where('gender', 'female')->count(),
        ];

        $data['projects'] = [
            'total' => Project::count(),
            'comments' => Project::with(['comments'])->count(),
            'shares' => Project::sum('shares'),
            'likes' => Project::sum('likes'),
            'views' => Project::sum('views'),
            // 'map', 'discussion', 'link'
            'maps' => Project::where('category', 'map')->count(),
            'discussions' => Project::where('category', 'discussion')->count(),
            'drafts' => Project::where('category', 'link')->count(),
            // public, private, draft
            'public' => Project::where('status', 'public')->count(),
            'private' => Project::where('status', 'private')->count(),
            'draft' => Project::where('status', 'draft')->count(),
            'trash' => Project::onlyTrashed()->count(),
        ];

        $data['podcasts'] = [
            'total' => Podcast::count(),
            'comments' => Podcast::with(['comments'])->count(),
            'shares' => Podcast::sum('shares'),
            'likes' => Podcast::sum('likes'),
            'views' => Podcast::sum('views'),
            'videos' => Podcast::where('category', 'video')->count(),
            'audios' => Podcast::where('category', 'audio')->count(),
            'trash' => Podcast::onlyTrashed()->count(),
        ];

        $data['assets'] = [
            'total' => Assets::count(),
            'sizes' => Assets::sum('size'),
            'capacity' => 'KB',
        ];


        $data['resources'] = [
            'users' => [
                'total' => User::count(),
                'male' => User::where('gender', 'male')->count(),
                'female' => User::where('gender', 'female')->count(),
            ],
            'certificates' => [
                'total'=> Certificate::all(),
                'courses' => Certificate::select('course', DB::raw('count(*) as total'))
                    ->groupBy('course')
                    ->get()
            ],
            'profession' => User::select('profession', DB::raw('count(*) as total'))
                ->groupBy('profession')
                ->get(),
            'membership_status' => User::select('membership_status', DB::raw('count(*) as total'))
                ->groupBy('membership_status')
                ->get(),
            'cities' => User::select('city', DB::raw('count(*) as total'))
                ->groupBy('city')
                ->get(),
            'states' => User::select('state', DB::raw('count(*) as total'))
                ->groupBy('state')
                ->get(), 
            'countries' => User::select('country', DB::raw('count(*) as total'))
                ->groupBy('country')
                ->get(),
            'organizations' => User::select('organization', DB::raw('count(*) as total'))
                ->groupBy('organization')
                ->get(),
            'organization_categories' => User::select('organization_category', DB::raw('count(*) as total'))
                ->groupBy('organization_category')
                ->get(),
            'organization_roles' => User::select('organization_role', DB::raw('count(*) as total'))
                ->groupBy('organization_role')
                ->get(),
        ];

        $data['newsletters'] = [
            'total' => Newsletter::count(),
            'active' => Newsletter::where('active', 'yes')->count(),
        ];

        $data['galleries'] = [
            'total' => Gallery::count(),
        ];

        if (!$data) {
            return $this->sendError([], 'unable to load data', 500);
        }

        return $this->sendSuccess($data, 'resource loaded successfully', 200);
    }

    // Get the resource
    public function resources(){
        $data = [
            'users' => [
                'total' => User::count(),
                'male' => User::where('gender', 'male')->count(),
                'female' => User::where('gender', 'female')->count(),
            ],
            'certificates' => [
                'total'=> Certificate::all(),
                'courses' => Certificate::select('course', DB::raw('count(*) as total'))
                    ->groupBy('course')
                    ->get()
            ],
            'profession' => User::select('profession', DB::raw('count(*) as total'))
                ->groupBy('profession')
                ->get(),
            'membership_status' => User::select('membership_status', DB::raw('count(*) as total'))
                ->groupBy('membership_status')
                ->get(),
            'cities' => User::select('city', DB::raw('count(*) as total'))
                ->groupBy('city')
                ->get(),
            'states' => User::select('state', DB::raw('count(*) as total'))
                ->groupBy('state')
                ->get(), 
            'countries' => User::select('country', DB::raw('count(*) as total'))
                ->groupBy('country')
                ->get(),
            'organizations' => User::select('organization', DB::raw('count(*) as total'))
                ->groupBy('organization')
                ->get(),
            'organization_categories' => User::select('organization_category', DB::raw('count(*) as total'))
                ->groupBy('organization_category')
                ->get(),
            'organization_roles' => User::select('organization_role', DB::raw('count(*) as total'))
                ->groupBy('organization_role')
                ->get(),
        ];

        if (!$data) {
            return $this->sendError([], 'unable to load data', 500);
        }

        return $this->sendSuccess($data, 'resource loaded successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display all users.
     */
    public function allUsers()
    {
        $users = User::all();
        $metadata = $this->getMetadata($users);

        if (!$users) {
            return $this->sendError([], 'unable to load users', 500);
        }

        return $this->sendSuccess($users, 'successful', 200, $metadata);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     //
    // }



    // Get all the users
    public function users()
    {

        $users = User::paginate();
        $metadata = $this->getMetadata($users);

        if (!$users) {
            return $this->sendError([], 'unable to load users', 500);
        }

        return $this->sendSuccess($users, 'successful', 200, $metadata);

    }


    // Get location of registered users
    public function locations()
    {
        $locations = User::select('state', DB::raw('count(*) as total'))
            ->groupBy('state')
            ->get();

        // $locations = User::select('state')->groupBy('state')->get();

        $metadata = $this->getMetadata($locations);

        if (!$locations) {
            return $this->sendError([], 'unable to load locations', 500);
        }

        return $this->sendSuccess($locations, 'successful', 200, $metadata);

    }

    // Get memberships of users
    public function memberships()
    {
        $locations = User::select('membership_status', DB::raw('count(*) as total'))
            ->groupBy('membership_status')
            ->get();

        $metadata = $this->getMetadata($locations);

        if (!$locations) {
            return $this->sendError([], 'unable to load locations', 500);
        }

        return $this->sendSuccess($locations, 'successful', 200, $metadata);

    }


    /**
     * Update user role [user, admin, moderator]
     */
    public function updateRole(Request $request)
    {
        // return $request->email;
        $user = User::where('email', $request->email)->first();


        if(!$user){
            return $this->sendError([], 'user not found', 404);
        }

        if(!$request->role){
            return $this->sendError([], 'enter a role', 404);


        }

        if(!in_array($request->role, ['user', 'moderator', 'admin', 'super-admin'])){
            return $this->sendError([], 'invalid role', 402);
        }

        $user->role = $request->role;
        $user->save();

        $message = $request->role . ' role assign to ' . $request->email;
        return $this->sendSuccess($user, $message, 200);

    }

}
