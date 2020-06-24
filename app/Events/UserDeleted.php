<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $organizationId;

    public function __construct(string $userId, string $organizationId = null)
    {
        $this->userId = $userId;
        $this->organizationId = $organizationId;
    }
}
