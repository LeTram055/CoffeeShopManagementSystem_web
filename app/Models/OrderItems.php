<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItems extends Model
{
    use HasFactory;
    protected $table = 'order_items';

    protected $primaryKey = ['order_id', 'item_id'];
    public $incrementing = false; 
    protected $fillable = ['order_id', 'item_id', 'quantity', 'note', 'status'];


    public $timestamps = false;

    
    public function getKey()
    {
        return [$this->order_id, $this->item_id];
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'order_id');
    }

    public function item()
    {
        return $this->belongsTo(MenuItems::class, 'item_id', 'item_id');
    }


}