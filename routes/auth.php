<?php

use App\Http\Controllers\Api\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



use App\Models\User;

use Illuminate\Auth\Events\PasswordReset;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Password;

use Illuminate\Support\Str;


Route::middleware('guest')->group(function () {
    // Route::get('register', [RegisteredUserController::class, 'create'])
    //     ->name('register');
    Route::get('register', function(){
        // Redirect to external link
        return redirect()->away(config('app.frontend_register_url'));
    });

    // Route::post('register', [RegisteredUserController::class, 'store']);
    Route::post('register', function(){
        // Redirect to external link
        return redirect()->away(config('app.frontend_register_url'));
    });

    // Route::get('login', [AuthenticatedSessionController::class, 'create'])
    //     ->name('login');
    Route::get('login', function(){
        // Redirect to external link
        return redirect()->away(config('app.frontend_login_url'));
    });

    // Route::post('login', [AuthenticatedSessionController::class, 'store']);
    // Route::get('login', function(){
    //     // Redirect to external link
    //     return redirect()->away(config('app.frontend_login_url'));
    // });

    // Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    //     ->name('password.request');
    Route::get('forgot-password', function(){
        // Redirect to external link
        return redirect()->away(config('app.frontend_forgot_password_url'));
    });
    
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
        ->name('password.store');
});

// Route::post('/reset-password', function (Request $request) {

//     $request->validate([

//         'token' => 'required',

//         'email' => 'required|email',

//         'password' => 'required|min:8|confirmed',

//     ]);

 
//     info('Attempting password reset', 
//         [
//             'email' => $request->email,
//             'token' => $request->token,
//             'password' => $request->password ? 'provided' : 'not provided',
//             'password_confirmation' => $request->password_confirmation ? 'provided' : 'not provided',
//         ]
//     );

//         //

//     $status = Password::reset(

//         $request->only('email', 'password', 'password_confirmation', 'token'),

//         function (User $user, string $password) {

//             $user->forceFill([

//                 'password' => Hash::make($password)

//             ])->setRememberToken(Str::random(60));

//             info("SAVED NEW PASSWORD FOR USER ID: {$user->id}");
 

//             $user->save();

 

//             event(new PasswordReset($user));

//         }

//     );

 
//     return ($status === Password::PasswordReset)

//         ? "DONE"
//         : "NOT DONE";

//     return $status === Password::PasswordReset

//         ? redirect()->route('login')->with('status', __($status))

//         : back()->withErrors(['email' => [__($status)]]);

// })
// ->middleware('guest')
// ->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
// ->name('password.store');


// Modified it to suite web and api
// Allowing Guests to verify their email by clicking the link on their mail
Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    //     ->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});



// Route::get('/migrate-force', function () {
//     Artisan::call('migrate', ['--force' => true]);
//     return 'Migration completed successfully!';
// });