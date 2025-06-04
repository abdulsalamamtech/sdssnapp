<?php

namespace App\Providers;


// use Dedoc\Scramble\Scramble;
use App\Events\CertificationRequestedProceedEvent;
use App\Listeners\NotifyAdminAboutCertificateRequestListener;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

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
        );
    }
}
