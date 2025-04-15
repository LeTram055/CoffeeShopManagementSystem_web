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
    protected $fillable = ['order_id', 'item_id', 'quantity', 'note', 'status', 'completed_quantity'];


    public $timestamps = false;

    
    public function getKey()
    {
        return [$this->order_id, $this->item_id];
    }

    
    protected function getKeyForSaveQuery()
{
    $query = $this->newQueryWithoutScopes();

    // Sử dụng where để tìm kiếm theo order_id và item_id
    return $query->where('order_id', $this->getAttribute('order_id'))
                 ->where('item_id', $this->getAttribute('item_id'));
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