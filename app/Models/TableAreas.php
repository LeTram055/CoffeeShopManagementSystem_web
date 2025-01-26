<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TableAreas extends Model
{
    use HasFactory;
    protected $table = 'table_areas';
    
    protected $fillable = ['name'];
    protected $guarded = ['area_id'];
    protected $primaryKey = 'area_id';
    public $timestamps = false;

    public function tables()
    {
        return $this->hasMany(Tables::class, 'area_id', 'area_id');
    }
}