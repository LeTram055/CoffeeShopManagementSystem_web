<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItems extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'menu_items';
    
    protected $fillable = [
        'name', 
        'image_url',
        'price', 
        'category_id', 
        'description',
        'is_available',
        'reason',
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

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'item_id', 'item_id');
    }

    public function calculateMaxServings(): int
    {
        if ($this->ingredients->isEmpty()) {
            // Nếu món ăn không có nguyên liệu, không giới hạn số lượng phục vụ
            return PHP_INT_MAX;
        }

        $maxServings = PHP_INT_MAX;

        $this->load('ingredients.ingredient');

        foreach ($this->ingredients as $menuIngredient) {
            $ingredient = $menuIngredient->ingredient;

            // Tính số lượng còn lại có thể dùng cho món này
            $available = $ingredient->quantity - $ingredient->reserved_quantity;

            // Nếu không đủ, có thể làm được ít hoặc 0
            $canMake = floor($available / $menuIngredient->quantity_per_unit);

            $maxServings = min($maxServings, $canMake);
        }

        return $maxServings;
    }
}