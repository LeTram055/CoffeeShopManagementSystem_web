<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tables extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tables';
    
    protected $fillable = ['table_number', 'status_id'];
    protected $guarded = ['table_id'];
    protected $primaryKey = 'table_id';
    public $timestamps = false;

    public function status()
    {
        return $this->belongsTo(TableStatuses::class, 'status_id', 'status_id');
    }

    public function orders()
    {
        return $this->hasMany(Orders::class, 'table_id', 'table_id');
    }
}