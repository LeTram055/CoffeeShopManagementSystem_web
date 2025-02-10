<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payments extends Model
{
    use HasFactory;
    protected $table = 'payments';
    
    protected $fillable = [
        'order_id', 
        'employee_id',
        'amount',
        'payment_method',
        'amount_received',
        'change_given',
        'payment_time',
];
    protected $guarded = ['payment_id'];
    protected $primaryKey = 'payment_id';
    protected $casts = [
        'payment_time' => 'datetime',
    ];
    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'order_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'employee_id');
    }
}