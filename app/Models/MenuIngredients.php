<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuIngredients extends Model
{
    use HasFactory;
    protected $table = 'menu_ingredients';
    protected $fillable = ['item_id', 'ingredient_id', 'quantity_per_unit'];
    //protected $guarded = ['ingredient_id', 'item_id'];
    //protected $primaryKey = ['item_id', 'ingredient_id'];

    public $timestamps = false;

    public function ingredient()
    {
        return $this->belongsTo(Ingredients::class, 'ingredient_id', 'ingredient_id');
    }

    public function item()
    {
        return $this->belongsTo(MenuItems::class, 'item_id', 'item_id');
    }
}