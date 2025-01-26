<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employees extends Model
{
    use HasFactory;
    protected $table = 'employees';
    
    protected $fillable = [
        'account_id',
        'name',
        'phone_number',
        'email',
        'address',
    ];
    protected $guarded = ['employee_id'];
    protected $primaryKey = 'employee_id';
    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(Accounts::class, 'account_id', 'account_id');
    }

    public function ingredientLogs()
    {
        return $this->hasMany(IngredientLogs::class, 'employee_id', 'employee_id');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'employee_id', 'employee_id');
    }
}