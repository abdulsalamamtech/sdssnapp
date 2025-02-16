

composer create-project laravel/laravel project-name

cd project-name

composer require laravel/breeze --dev

php artisan breeze:install [Blade with Alpine ]
 
php artisan migrate
npm install
npm run dev



I recommend installing the "Blade with Alpine" stack if you're looking 
for a straightforward and efficient option to get started with Breeze. 
It combines the simplicity of Blade templates with the interactive 
capabilities of Alpine.js, making it suitable for most applications.


npm run dev
npm run build

## Authentication


https://laravel.com/docs/11.x/authentication

https://laravel.com/docs/11.x/passwords

https://laravel.com/docs/11.x/sanctum



php artisan install:api

use Laravel\Sanctum\HasApiTokens;


->withMiddleware(function (Middleware $middleware) {
    $middleware->statefulApi();
})


php artisan config:publish cors

supports_credentials = true

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;


Finally, you should ensure your application's session cookie 
domain configuration supports any subdomain of your root domain.



config/session.php
'domain' => '.domain.com',

.env
SESSION_DOMAIN


################################


Authenticating
CSRF Protection
To authenticate your SPA, your SPA's "login" page should 
first make a request to the /sanctum/csrf-cookie endpoint 
to initialize CSRF protection for the application:

axios.get('/sanctum/csrf-cookie').then(response => {
    // Login...
});


 return $user->createToken($request->device_name)->plainTextToken;
 $user->tokens()->delete();



[https://medium.com/himit-pens/implementing-user-roles-in-laravel-api-with-spatie-48b059a3b18f]

[https://dev.to/codeanddeploy/laravel-8-user-roles-and-permissions-step-by-step-tutorial-1dij#:~:text=Laravel%208%20User%20Roles%20and%20Permissions%20Step%20by,Routes%20...%207%20Step%207%3A%20Add%20Controllers%20]

 composer require spatie/laravel-permission


 ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        $middleware->alias([ // thiss
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]); // until thiss
    })


php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan config:clear
php artisan migrate


use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    // ...
}



```php

namespace Database\Seeders;

use App\Models\User;

use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $authorRole = Role::create(['name' => 'author']);
        $viewerRole = Role::create(['name' => 'viewer']);

        $userAdmin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('adminuhuy'),
        ]);
        
        $userAuthor = User::create([
            'name' => 'Author',
            'email' => 'author@gmail.com',
            'password' => bcrypt('authoruhuy'),
        ]);

        $userViewer = User::create([
            'name' => 'Viewer',
            'email' => 'viewer@gmail.com',
            'password' => bcrypt('vieweruhuy'),
        ]);

        $userAdmin->assignRole($adminRole);
        $userAuthor->assignRole($authorRole);
        $userViewer->assignRole($viewerRole);
    }
}

```

php artisan db:seed

```php


use App\Models\User; // this
use Illuminate\Http\JsonResponse; // this

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request): JsonResponse // this
    {
        $validatedData = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if(!Auth::attempt($validatedData)) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed. Please check your credentials.'
            ], 401);
        }

        $token = $user->createToken($user->name)->plainTextToken;
        
        return response()->json([
            'success' => true,
            'user' => $user,
            'access_token' => $token
        ], 200);
    }
// ...
}

```

```php


use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Route::apiResource('/posts', PostController::class);

Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/posts', [PostController::class, 'index'])->middleware('role:admin|viewer');
    Route::post('/posts', [PostController::class, 'store'])->middleware('role:admin|author');
    Route::put('/posts/{post}', [PostController::class, 'update'])->middleware('role:admin');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware('role:admin');
    Route::get('/posts/{post}', [PostController::class, 'show'])->middleware('role:admin|viewer');
});

```

```sh
    php artisan serve
    php artisan make:controller Api/AuthController
```


### Assets
ImageKit 
Cloudinary
Email Template
PDF Template
Excel Template
Event and Notification


### Documentation
Laravel Swagger uses annotations to automatically generate OpenAPI documentation, and 
laravel-apidoc-generator generates a static HTML documentation from your routes and controllers. 
Both tools simplify the documentation process, making it easier to maintain accurate API documentation.



[https://scramble.dedoc.co/installation | Very good]
composer require dedoc/scramble
php artisan vendor:publish --provider="Dedoc\Scramble\ScrambleServiceProvider" --tag="scramble-config"

/docs/api - UI viewer for your documentation
/docs/api.json - Open API document in JSON format describing your API.
