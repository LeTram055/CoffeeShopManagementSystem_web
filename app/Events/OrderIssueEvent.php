<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderIssueEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orderId;
    public $itemName;
    public $reason;

    public function __construct($orderId, $itemName, $reason)
    {
        $this->orderId = $orderId;
        $this->itemName = $itemName;
        $this->reason = $reason;
    }

    public function broadcastOn()
    {
        return new Channel('orderissue');
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->orderId,
            'item_name' => $this->itemName,
            'reason' => $this->reason,
        ];
    }

    public function broadcastAs()
    {
        return 'order.issue';
    }
}