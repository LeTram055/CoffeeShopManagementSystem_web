<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customers extends Model
{
    use HasFactory;
    protected $table = 'customers';
    
    protected $fillable = ['name', 'phone_number', 'notes'];
    protected $guarded = ['customer_id'];
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    public function orders()
    {
        return $this->hasMany(Orders::class, 'customer_id', 'customer_id');
    }
}