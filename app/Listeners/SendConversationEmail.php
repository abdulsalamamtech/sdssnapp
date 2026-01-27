<?php

namespace App\Listeners;

use App\Events\ConversationSaved;
use App\Mail\ConversationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use function Laravel\Prompts\info;

class SendConversationEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        info('Listener Fired!');
    }

    /**
     * Handle the event.
     */
    public function handle(ConversationSaved $event): void
    {
        // Access the user using $event->user...
        $users = $event->conversation->sentTo();
        info($users);
        $content['subject'] = $event->conversation->subject;
        $content['message'] = $event->conversation->message;

        foreach ($users as $user) {
            Mail::to($user?->email)->send(new ConversationMail($user, $content));
            info('Conversation: ' . $event->conversation->sentBy->email . ' Sent: ' . $event->conversation->reason . ' To: ' . $user);
        }
    }
}
