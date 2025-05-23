<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $event_type; // Loại sự kiện (created, updated, cancelled)

    public function __construct($order, $event_type)
    {
        $this->order = $order;
        $this->event_type = $event_type;
    }

    public function broadcastOn()
    {
        return new Channel('orderevent');
    }

    public function broadcastWith()
    {
        return [
            'order' => $this->order,
            'event_type' => $this->event_type,
        ];
    }

    public function broadcastAs()
    {
        return 'order.event';
    }
}