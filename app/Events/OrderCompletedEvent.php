<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Orders;
use Illuminate\Support\Facades\Log;

class OrderCompletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Orders $order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        return new Channel('ordercompleted');
    }

    public function broadcastAs()
    {
        return 'order.completed';
    }

    public function broadcastWith()
    {
        
        return ['order' => $this->order];
    }

}