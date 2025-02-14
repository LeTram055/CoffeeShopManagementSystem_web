<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IngredientLogs extends Model
{
    use HasFactory;
    protected $table = 'ingredient_logs';
    
    protected $fillable = [
        'ingredient_id', 
        'quantity_change', 
        'reason', 
        'employee_id',
        'changed_at'];
    protected $guarded = ['log_id'];
    protected $primaryKey = 'log_id';

    protected $casts = [
        'changed_at' => 'datetime',
    ];
    public $timestamps = false;

    public function ingredient()
    {
        return $this->belongsTo(Ingredients::class, 'ingredient_id', 'ingredient_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'employee_id');
    }
}