<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    // : RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        info('Attempting password reset for email: ' . $request->email, [
            'email' => $request->email,
            'token' => $request->token,
            'password' => $request->password ? 'provided' : 'not provided',
            'password_confirmation' => $request->password_confirmation ? 'provided' : 'not provided',
            'match' => $request->password === $request->password_confirmation ? 'yes' : 'no',
        ]);



        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        // $status = Password::reset(
        //     $request->only('email', 'password', 'password_confirmation', 'token'),
        //     function ($user) use ($request) {
        //         $updatedUser = $user->forceFill([
        //             'password' => Hash::make($request->password),
        //             'remember_token' => Str::random(60),
        //         ])->save();

        //         info('Password reset successful for email: ' . $request->email, [
        //             'email' => $request->email,
        //             'token' => $request->token,
        //             'password' => $request->password ? 'provided' : 'not provided',
        //             'password_confirmation' => $request->password_confirmation ? 'provided' : 'not provided',
        //             'user_id' => $user->id,
        //             'updated' => $updatedUser ? 'yes' : 'no',
        //         ]);

        //         event(new PasswordReset($user));
        //     }
        // );

    $status = Password::reset(

        $request->only('email', 'password', 'password_confirmation', 'token'),

        function (User $user, string $password) {

            $user->forceFill([

                'password' => Hash::make($password)

            ])->setRememberToken(Str::random(60));

            info("SAVED NEW PASSWORD FOR USER ID: {$user->id}");
 

            $user->save();

 

            event(new PasswordReset($user));

        }

    );


        if($status == Password::PASSWORD_RESET) {
            return redirect()->away(config('app.frontend_login_url'));
        }else{
            return redirect()->away(config('app.frontend_forgot_password_url'));

        }
 
    // return ($status === Password::PasswordReset)

    //     ? "DONE"
    //     : "NOT DONE";

    // return $status === Password::PasswordReset

    //     ? redirect()->route('login')->with('status', __($status))

    //     : back()->withErrors(['email' => [__($status)]]);
        
        
        // dd($request->only('email', 'password', 'password_confirmation', 'token'), $status);

        info('Password reset status: ' . $status,
            [
                'email' => $request->email,
                'status' => $status,
                'token' => $request->token,
                'password' => $request->password ? 'provided' : 'not provided',
                'password_confirmation' => $request->password_confirmation ? 'provided' : 'not provided',
            ]
        );




        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
