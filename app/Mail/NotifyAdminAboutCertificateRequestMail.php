<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyAdminAboutCertificateRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The certification request instance.
     *
     * @var mixed
     */
    public $certificationRequest;

    /**
     * The credential file instance.
     *
     * @var mixed
     */
    public $credentialFile;


    /**
     * Create a new message instance.
     */
    public function __construct($certificationRequest, $credentialFile = null)
    {
        $this->certificationRequest = $certificationRequest;
        $this->credentialFile = $credentialFile;
        $this->afterCommit();

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notification For Certificate Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        info('Sending email to admin about certification request: ' . $this->certificationRequest->id);
        info('Certification Request Details: ', [
            'email' => $this->certificationRequest->user->email,
            'user_id' => $this->certificationRequest->user_id,
            'full_name' => $this->certificationRequest->full_name,
            'status' => $this->certificationRequest->status,
        ]);
        return new Content(
            view: 'mail.notify-admin-about-certificate-request-mail',
            with: [
                'certificationRequest' => $this->certificationRequest,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            // If there's a credential file, attach it
            // from storage path
            // Attachment::fromPath($this->credentialFile->getRealPath())
            //     ->as($this->credentialFile->getClientOriginalName())
            //     ->withMime($this->credentialFile->getClientMimeType()),
            // from request file upload
            Attachment::fromData(fn () => file_get_contents($this->credentialFile->getRealPath()), $this->credentialFile->getClientOriginalName())
                ->withMime($this->credentialFile->getClientMimeType()),
        ];
    }
}
