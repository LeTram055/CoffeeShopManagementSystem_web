<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingredients extends Model
{
    use HasFactory;
    protected $table = 'ingredients';
    
    protected $fillable = [
        'name',
        'quantity',
        'unit', 
        'min_quantity',
        'last_updated'
    ];
    protected $guarded = ['ingredient_id'];
    protected $primaryKey = 'ingredient_id';
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