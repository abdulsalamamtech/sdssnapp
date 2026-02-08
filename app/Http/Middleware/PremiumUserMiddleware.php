<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PremiumUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->membership_status == 'free') {
            $response['success'] = false;
            $response['message'] = 'Unauthorized: Access Denied, You do not have the necessary permission, please upgrade your account!';
            $statusCode = 401;

            return response()->json($response, $statusCode);
        }
        return $next($request);
    }
}
