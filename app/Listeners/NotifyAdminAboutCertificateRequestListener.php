<?php

namespace App\Listeners;

use App\Events\CertificationRequestedProceedEvent;
use App\Mail\NotifyAdminAboutCertificateRequestMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyAdminAboutCertificateRequestListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CertificationRequestedProceedEvent $event): void
    {
        // Here you can implement the logic to notify the admin about the certification request.
        // For example, you might send an email or a notification.

        $certificationRequest = $event->certificationRequest;

        // Example: Log the certification request for now
        Log::info('New certification request received', [
            'request_id' => $certificationRequest->id,
            'user_id' => $certificationRequest->user_id,
            'full_name' => $certificationRequest->full_name,
            'status' => $certificationRequest->status,
        ]);

        // Send an email or notification to the admin
        // NotifyAdminAboutCertificateRequestMail 
        Mail::to('abdulsalamamtech@gmail.com')->send(new NotifyAdminAboutCertificateRequestMail($certificationRequest));
    }
}
