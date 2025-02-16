<?php 


use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Api\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Api\Auth\EmailVerifyController;
use App\Http\Controllers\Api\Auth\NewPasswordController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use App\Http\Controllers\Api\Register\MarketerRegister;
use App\Http\Controllers\Api\Register\RefineryRegister;
use App\Http\Controllers\Api\Register\TransporterRegister;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {

    /**
     * Register a new user
     * @param name string
     * @param email string
     * @param password string
     * @param password_confirmation string
     * @return Response json
     */
    Route::post('register', [AuthController::class, 'register']);

    /**
     * Login the user
     * @param email string
     * @param password string
     * @return Response json
     */
    Route::post('login', [AuthController::class, 'login']);

    /**
     * Update the user's password
     * @param email string
     * @return Response json
     */
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');


    // This request is sent from the UI [web route will handle the request]
    // Reset Password
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
    
    // This request is sent from the UI [web route will handle the request]
    //  Hash email verification url
    Route::get('verify-email/{id}/{hash}', EmailVerifyController::class)->name('verification.verify');

});


// User authenticated routes
Route::middleware('auth:sanctum')->group(function () {

    // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    //     ->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');

    // For Web, when user need to re-enter their password
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    
    /**
     * Send a new email verification notification.
     * @param token The
     * @return Response
     */
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    /**
     * Update the user's password
     * @param current_password
     * @param password
     * @param password_confirmation
     * @return Response
     */
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    /**
     * Destroy the user's token.
     * @param token
     * @return Response 
     */
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');
});

