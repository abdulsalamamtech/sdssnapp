<?php

namespace App\Providers;


// use Dedoc\Scramble\Scramble;
use App\Events\CertificationRequestedProceedEvent;
use App\Events\ConversationSaved;
use App\Listeners\NotifyAdminAboutCertificateRequestListener;
use App\Listeners\SendConversationEmail;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Log viewer
        // LogViewer::auth(function ($request) {        
            // return true to allow viewing the Log Viewer.  
            // if($request->token && $request->token == config('app.log_viewer_token')) {
            //     // Auth::loginUsingId(1); // Assuming user with ID 1 is an admin
                            
            //     // Authenticate the user
            //     $user = \App\Models\User::findOrFail(1); // Replace with your logic to get the admin user
            //     Auth::login($user);-
            //     return true; 
            // }
            // return true; 
            // return false; 
        // });

            // LogViewer::auth(function ($request) {
            //     $user = \App\Models\User::findOrFail(1); // Replace with your logic to get the admin user
            //     Auth::login($user);
            //     if(!$request->token && $request->token !== config('app.log_viewer_token')) {
            //         session('log_viewer_token', $request->token);
            //         return false; 
            //     }
            //     return $user
            //         && in_array($user->email, [
            //             'abdulsalamamtech@gmail.com',
            //         ]);
            // });
        // ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
        //     return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        // });
        Scramble::configure()
        ->withDocumentTransformers(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });

        // Register an event and listener for certification requests
        // \App\Events\CertificationRequestedProceedEvent::class => [
        //     \App\Listeners\NotifyAdminAboutCertificateRequestListener::class,
        // ],
        Event::listen(
            CertificationRequestedProceedEvent::class,
            NotifyAdminAboutCertificateRequestListener::class,
            ConversationSaved::class, // Event
            SendConversationEmail::class // Listener
        );
    }
}
