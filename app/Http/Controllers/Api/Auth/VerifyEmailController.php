<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    // public function __invoke(EmailVerificationRequest $request)
    // {
    //     if ($request->user()->hasVerifiedEmail()) {
    //         // return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    //         return ApiResponse::success([], 'email verified');

    //     }

    //     if ($request->user()->markEmailAsVerified()) {
    //         event(new Verified($request->user()));
    //     }

    //     // return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    //     return ApiResponse::success([], 'email verified');


    // }

    
    // Includes the verification redirect URL
    protected function redirectUrl(){
        return [
            // 'success' => config('app.frontend_url') . '/auth/verify-email-success',
            'success' => env('FRONTEND_URL') ?? env('APP_URL') . '/auth/verify-email-success',
            'error' => 'username',
            'login' => 'https://localhost:8000/auth/login',
            'invalid_token' => 'https://localhost:8000/auth/invalid-token',
            'expired_token' => 'https://localhost:8000/auth/expired-token',
            'token_expired' => 'https://localhost:8000/auth/token-expired',
            'token_not_provided' => 'https://localhost:8000/auth/token-not-provided',
            'user_not_found' => 'https://localhost:8000/auth/user-not-found',
        ];
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = 'Email verification failed';
        $status_code = 500;
        $user = null;

        // Email verification details
        $id = $request->id;
        $hash = $request->hash;
        $expires = $request->expires;

        // Check if the URL is signed
        $valid_signature = $request->hasValidSignature();

        // Get user information
        $user = User::where('id', $id)->first();
        if(!$user){

            $message = 'user not found';
            $status_code = 404;
            // return response()->json(['success' => false,'message' => $message], $status_code);
            return redirect()->intended(route('login', absolute: false).'?verified=1');

        }


        // Check if the user exists and the email has been verified
        if($user->email_verified_at != null && $user->email){

            
            // Redirect to success page
            // $message = 'Email already verified';
            // $status_code = 200;
            
            // return response()->json(['success' => true,'message' => $message], $status_code);
            
            // Redirect to success page
            // return redirect($this->redirectUrl()['success']);

            return redirect()->intended(route('login', absolute: false).'?verified=1');

        }




        $now = time();
        $valid_hash = hash_equals($hash, sha1($user->email));

        // $details = [
        //     'user_id'=>$id,
        //     'hash' => $hash,
        //     'expires' => $expires,
        //     'valid_signature' => $valid_signature,
        //     'valid_hash' => $valid_hash,
        //     'time' => $now,
        //     'valid_time' => ($expires >= $now),
        //     'time_remaining' => ($expires - $now),
        //     'user' => $user
        // ];


        // Check if the URL is signed and if it's not expired
        if (!$valid_signature && !$valid_hash && !($expires >= $now)) {

            $message = 'Invalid signature or hash or expired URL';
            $status_code = 403;
            // return response()->json(['success' => false,'message' => $message], $status_code);
            return redirect()->intended(route('login', absolute: false).'?verified=0');


        // Check successful
        }else{
                $user->email_verified_at = now();
                $user->save();

                $message = 'Email verified successfully';
                $status_code = 200;

                // Redirect to success page
                // return redirect($this->redirectUrl()['success']);
                return redirect()->intended(route('login', absolute: false).'?verified=1');

        }

        // return response()->json(['success'=> true,'message' => $message], $status_code);
        return redirect()->intended(route('login', absolute: false).'?verified=1');

    }     


    /**
     * Add this code to the quest web routes and comment the one on the auth routes.
     * Mark the authenticated user's email address as verified.
     */
    // Modified it to suite web and api
    // Allowing Guests to verify their email by clicking the link on their mail
    // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    // ->middleware(['signed', 'throttle:6,1'])
    // ->name('verification.verify');
}
