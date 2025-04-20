<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotions extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'promotions';
    
    protected $fillable = [
        'name', 
        'discount_type', 
        'discount_value',
        'min_order_value',
        'start_date', 
        'end_date',
        'is_active'
    ];
    protected $guarded = ['promotion_id'];
    protected $primaryKey = 'promotion_id';
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    public $timestamps = false;

    public function payments()
    {
        return $this->hasMany(Payments::class, 'promotion_id', 'promotion_id');
    }

}