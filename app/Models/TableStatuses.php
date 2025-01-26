<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TableStatuses extends Model
{
    use HasFactory;
    protected $table = 'table_statuses';
    
    protected $fillable = ['name'];
    protected $guarded = ['status_id'];
    protected $primaryKey = 'status_id';
    public $timestamps = false;

    public function tables()
    {
        return $this->hasMany(Tables::class, 'status_id', 'status_id');
    }
}