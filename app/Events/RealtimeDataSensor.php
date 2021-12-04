<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Read;

class RealtimeDataSensor implements ShouldBroadcast
{
    use  SerializesModels;

    public $data;

    public $sensorId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Read $data, $id)
    {
        $this->data = $data;
        $this->sensorId = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('read.sensor.'.$this->sensorId);
    }
}
