<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employees extends Model
{
    use HasFactory;
    protected $table = 'employees';
    
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'status',
        'phone_number',
        'email',
        'address',
        'start_date',
    ];
    protected $guarded = ['employee_id'];
    protected $primaryKey = 'employee_id';

    protected $casts = [
        'start_date' => 'datetime',
    ];
    public $timestamps = false;


    public function ingredientLogs()
    {
        return $this->hasMany(IngredientLogs::class, 'employee_id', 'employee_id');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'employee_id', 'employee_id');
    }
}