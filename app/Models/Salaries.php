<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salaries extends Model
{
    use HasFactory;

    protected $table = 'salaries';
    protected $primaryKey = 'salary_id';
    public $timestamps = false;

    protected $fillable = [
        'employee_id', 'month', 'year', 'total_hours', 'salary_per_hour', 'total_salary', 'total_bonus_penalty', 'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}