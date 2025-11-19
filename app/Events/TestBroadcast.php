<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message = "Hello Reverb !")
    {
        $this->message = $message . " " . now()->format('H:i:s');
    }

    public function broadcastOn()
    {
        return new Channel('test-channel'); // canal public pour le test
    }

    public function broadcastAs()
    {
        return 'test-event';
    }
}