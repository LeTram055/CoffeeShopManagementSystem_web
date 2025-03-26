<?php

namespace App\Events;

use App\Models\Ingredients;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LowStockEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $ingredient;

    public function __construct(Ingredients $ingredient)
    {
        $this->ingredient = $ingredient;
    }
    

    public function broadcastOn()
    {
        return new Channel('lowstock');
    }

    public function broadcastAs()
    {
        return 'lowstock.event';
    }

    public function broadcastWith()
    {
        return [
            'ingredient_id' => $this->ingredient->ingredient_id,
            'name' => $this->ingredient->name,
            'quantity' => $this->ingredient->quantity,
            'min_quantity' => $this->ingredient->min_quantity
        ];
    }
}