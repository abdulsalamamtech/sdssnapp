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


    /**
     * Get all statistical resource
     */
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
     * Get all admin.
     */
    public function admin()
    {
        $admin = User::where('role', 'admin')->latest()->get();

        if (!$admin) {
            return $this->sendError([], 'unable to load admins', 404);
        }

        return $this->sendSuccess($admin, 'successful', 200);
    }

    /**
     * Display all users.
     */
    public function allUsers()
    {
        $users = User::latest()->get();
        $metadata = $this->getMetadata($users);

        if (!$users) {
            return $this->sendError([], 'unable to load users', 500);
        }

        return $this->sendSuccess($users, 'successful', 200, $metadata);
    }



    /**
     * Display and paginate users.
     */
    public function users()
    {

        $users = User::paginate();
        $metadata = $this->getMetadata($users);

        if (!$users) {
            return $this->sendError([], 'unable to load users', 404);
        }

        return $this->sendSuccess($users, 'successful', 200, $metadata);

    }

    /**
     * Display all location of registered users.
     */
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


    /**
     * Display all memberships of users.
     */
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

        $request->validate([
            'email' =>'required|email|exists:users,email',
            'role' =>'required|in:user,admin'
        ]);

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


    /**
     * Assign a role to a user
     */
    public function assignRole(Request $request){
        $request->validate([
            'email' =>'required|email|exists:users,email',
            'role' =>'required|in:user,admin'
        ]);
    

        $user = User::where('email', $request->email)->first();
        // I remove the package from the middleware
        // {
        //   "success": false,
        //   "message": "An error occurred. Please try again later.",
        //   "error": "Authorizable class `App\\Models\\User` must use Spatie\\Permission\\Traits\\HasRoles trait."
        // }

        if(!$user){
            return response()->json(['status' => false, 'message' => 'User not found'], 201);
        }
    
        if(!$request->role){
            return response()->json(['status' => false, 'message' => 'enter a role'], 201);
        }
    
        if(!in_array($request->role, ['user', 'moderator', 'admin', 'super-admin'])){
            return response()->json(['status' => false,'message' => 'Invalid role'], 201);
        }
    
        $user->role = $request->role;
        $user->save();
    
        $message = $request->role . ' role assign to ' . $request->email;
        return response()->json([
            'status' => true,
            'message' => $message
        ], 201);
    }

}
