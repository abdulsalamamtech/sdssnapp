<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CertificationRequestRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The certification request instance.
     *
     * @var mixed
     */
    public $certificationRequest;

    /**
     * Create a new message instance.
     */
    public function __construct($certificationRequest)
    {
        $this->certificationRequest = $certificationRequest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Certification Request Rejected',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        info('Sending email to user about certification request rejection: ' . $this->certificationRequest->id);
        info('Certification Request Details: ', [
            'user_id' => $this->certificationRequest->user_id,
            'full_name' => $this->certificationRequest->full_name,
            'status' => $this->certificationRequest->status,
        ]);
        return new Content(
            view: 'mail.certification-request-rejected-mail',
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
        return [];
    }
}
