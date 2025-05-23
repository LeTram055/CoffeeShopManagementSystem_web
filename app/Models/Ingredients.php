<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredients extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ingredients';
    
    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'cost_price', 
        'min_quantity',
        'reserved_quantity',
        'last_updated'
    ];
    protected $guarded = ['ingredient_id'];
    protected $primaryKey = 'ingredient_id';

    protected $casts = [
        'last_updated' => 'datetime',
    ];
    public $timestamps = false;

    public function logs()
    {
        return $this->hasMany(IngredientLogs::class, 'ingredient_id', 'ingredient_id');
    }

    public function menuIngredients()
    {
        return $this->hasMany(MenuIngredients::class, 'ingredient_id', 'ingredient_id');
    }
}