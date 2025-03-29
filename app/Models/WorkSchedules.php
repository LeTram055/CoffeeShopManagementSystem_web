<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSchedules extends Model
{
    use HasFactory;

    protected $table = 'work_schedules';
    protected $primaryKey = 'schedule_id';
    public $timestamps = false;

    protected $fillable = [
        'employee_id', 'shift_id', 'work_date', 'status', 'work_hours'
    ];

    protected $casts = [
        'work_date' => 'date',
        
    ];

    public function shift()
    {
        return $this->belongsTo(Shifts::class, 'shift_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}