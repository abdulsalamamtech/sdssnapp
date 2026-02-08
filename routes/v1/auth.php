<?php

use App\Http\Controllers\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Register
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1');
// Login
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
// verify forget password email, otp && Enter new password
Route::post('/confirm-password', [AuthController::class, 'confirmPassword']);
// Forget password [email] = send 6 digit otp
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:6,1');

Route::middleware(['auth:sanctum'])->group(function () {
    // Verify account
    Route::post('/verify-account', [AuthController::class, 'verifyAccount']);
    // Reset password [old & new password]
    Route::post('/reset-password', [AuthController::class, 'updatePassword']);
    // Resend verification code
    Route::post('/resend-token', [AuthController::class, 'resendVerificationToken'])->middleware('throttle:6,1');

    /**
     * Destroy current user's token.
     * @param token
     * @return Response 
     */
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('api.logout');

    /**
     * Destroy the user's token.
     * @param token
     * @return Response 
     */
    Route::post('logout-devices', [AuthController::class, 'logoutDevices'])
        ->name('api.logout-devices');
});
