<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItems extends Model
{
    use HasFactory;
    protected $table = 'menu_items';
    
    protected $fillable = [
        'name', 
        'image_url',
        'price', 
        'category_id', 
        'description',
        'is_available'
    ];
    protected $guarded = ['item_id'];
    protected $primaryKey = 'item_id';
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id', 'category_id');
    }

    public function ingredients()
    {
        return $this->hasMany(MenuIngredients::class, 'item_id', 'item_id');
    }
}