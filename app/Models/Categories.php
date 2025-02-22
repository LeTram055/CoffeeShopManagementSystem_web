<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categories extends Model
{
    use HasFactory;
    protected $table = 'categories';
    
    protected $fillable = ['name'];
    protected $guarded = ['category_id'];
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    public function items()
    {
        return $this->hasMany(MenuItems::class, 'category_id', 'category_id');
    }
}