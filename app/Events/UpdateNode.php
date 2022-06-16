<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateNode implements ShouldBroadcast
{
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($node)
    {
        $this->data = $node;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('events');
    }
}
