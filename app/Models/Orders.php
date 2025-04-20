<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'orders';
    
    protected $fillable = [
        'table_id', 
        'customer_id',    
        'order_type', 
        'total_price',
        'status',
        'created_at'
    ];
    protected $guarded = ['order_id'];
    protected $primaryKey = 'order_id';
    //protected $dateFormat = 'H:i:s d/m/Y';
    protected $casts = [
        'created_at' => 'datetime',
    ];
    public $timestamps = false;

    public function table()
    {
        return $this->belongsTo(Tables::class, 'table_id', 'table_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'customer_id');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'order_id', 'order_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id', 'order_id');
    }

}