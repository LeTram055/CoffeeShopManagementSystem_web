<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shifts extends Model
{
    use HasFactory;

    protected $table = 'shifts';
    protected $primaryKey = 'shift_id';
    public $timestamps = false;

    protected $fillable = [
        'name', 'start_time', 'end_time'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    public function workSchedules()
    {
        return $this->hasMany(WorkSchedules::class, 'shift_id');
    }
}