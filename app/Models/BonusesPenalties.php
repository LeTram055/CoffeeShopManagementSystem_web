<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonusesPenalties extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bonuses_penalties';
    protected $primaryKey = 'bonus_penalty_id';
    public $timestamps = false;

    protected $fillable = [
        'employee_id', 'type', 'amount', 'reason', 'date'
    ];

    protected $casts = [
        'date' => 'datetime',
        
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}