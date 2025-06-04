<?php

namespace App\Events;

use App\Models\Api\CertificationRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CertificationRequestedProceedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * The certification request instance.
     *
     * @var \App\Models\Api\CertificationRequest
     */
    public CertificationRequest $certificationRequest;

    /**
     * Create a new event instance.
     */
    public function __construct(CertificationRequest $certificationRequest)
    {
        $this->certificationRequest = $certificationRequest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
