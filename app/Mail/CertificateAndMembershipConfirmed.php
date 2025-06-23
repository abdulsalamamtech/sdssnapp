<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CertificateAndMembershipConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The membership request instance.
     *
     * @var mixed
     */
    public $membership;

    /**
     * Create a new message instance.
     */
    public function __construct($membership)
    {
        $this->membership = $membership;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        info('Sending email to user about payment approval: ' . $this->membership->id);
        info('Membership Details: ', [
            'email' => $this->membership->user->email,
            'user_id' => $this->membership->user_id,
            'full_name' => $this->membership->full_name,
            'status' => $this->membership->status,
        ]);
        return new Envelope(
            subject: 'Certificate And Membership Confirmed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.certificate-and-membership-confirmed',
            with: [
                'membership' => $this->membership,
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
