<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{

    /**
     * Register a new user and get token.
     */
    public function register(RegisterRequest $request)
    {
        try {

            DB::beginTransaction();            
            
            // Create user
            $user = User::create($request->validated());

            // Create social media record
            $user->social()->create();
            
            // Dispatch event
            event(new Registered($user));
            // $user->sendEmailVerificationNotification();


            // Unset sensitive information
            $data = $user->toArray();
            unset($data['password']);
            info('Registered', $data);

            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();

            // Return response
            return response()->json([
                'success' => 'true',
                'message' => 'account created successfully',
                'data' => $user,
                'token' => $token,
                'type' => 'Bearer',
            ], 201);

        } catch (\Throwable $th) {
            //throw $th;
            $message = $th->getMessage();

            DB::rollBack();
            Log::error('Error rolling back transaction: ', [$message]);
            return ApiResponse::error($th, $message);

        }

    }


    /**
     * Log in the user and get token.
     */
    public function login(LoginRequest $request)
    {

        // Authenticate request
        $request->authenticate();

        // Get user
        $user = $request->user();

        // Unset sensitive information
        $data = $user->toArray();
        unset($data['password']);
        info('Login', $data);

        // Delete all user tokens
        // $user->tokens()->delete();

        // Generate new token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return response
        return response()->json([
            'success' => true,
            'message' => 'login successful',
            'data' => $user,
            'token' => $token,
            'type' => 'Bearer',
        ], 201);

    }    

    /**
     * Destroy current users token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $message = 'Logged out successfully';
        return ApiResponse::success([], $message);

    }



        /**
     * Destroy the user's token.
     */
    public function logoutDevices(Request $request)
    {
        // $user->tokens()->delete();
        $request->user()->tokens()->delete();
        $message = 'Logged out successfully';
        return ApiResponse::success([], $message);

    }

}
