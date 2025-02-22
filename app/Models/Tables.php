<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tables extends Model
{
    use HasFactory;
    protected $table = 'tables';
    
    protected $fillable = ['name', 'status_id'];
    protected $guarded = ['table_id'];
    protected $primaryKey = 'table_id';
    public $timestamps = false;

    public function status()
    {
        return $this->belongsTo(TableStatuses::class, 'status_id', 'status_id');
    }
}