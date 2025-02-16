<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            // return redirect()->intended(route('dashboard', absolute: false));
            return ApiResponse::success([], 'email account verified, please login');
        }

        $request->user()->sendEmailVerificationNotification();

        return ApiResponse::success([], 'verification link sent');
    }
}
