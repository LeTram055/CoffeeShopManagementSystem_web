<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employees extends Authenticatable
{
    use HasFactory;
    protected $table = 'employees';
    
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'status',
        'phone_number',
        'email',
        'address',
        'start_date',
        'hourly_rate'
    ];
    protected $guarded = ['employee_id'];
    protected $primaryKey = 'employee_id';

    protected $casts = [
        'start_date' => 'datetime',
    ];
    public $timestamps = false;


    public function ingredientLogs()
    {
        return $this->hasMany(IngredientLogs::class, 'employee_id', 'employee_id');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'employee_id', 'employee_id');
    }

    public function workSchedules()
    {
        return $this->hasMany(WorkSchedules::class, 'employee_id');
    }

    public function salaries()
    {
        return $this->hasMany(Salaries::class, 'employee_id');
    }

    public function bonusesPenalties()
    {
        return $this->hasMany(BonusesPenalties::class, 'employee_id');
    }

    public function calculateSalary($month, $year)
{
    // Tính tổng số giờ làm trong tháng từ bảng work_schedules
    $totalHours = $this->workSchedules()
        ->whereYear('work_date', $year)
        ->whereMonth('work_date', $month)
        ->where('status', 'completed')
        ->sum('work_hours');

    // Lương theo giờ
    $hourlyRate = $this->hourly_rate;

    // Tổng tiền lương cơ bản
    $baseSalary = $totalHours * $hourlyRate;

    // Lấy tổng thưởng/phạt trong tháng từ bảng bonuses_penalties
    $bonusPenalty = $this->bonusesPenalties()
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->sum('amount');

    // Tổng lương cuối cùng
    return [
        'total_hours' => $totalHours,
        'salary_per_hour' => $hourlyRate,
        'total_salary' => $baseSalary,
        'total_bonus_penalty' => $bonusPenalty,
        'final_salary' => $baseSalary + $bonusPenalty
    ];
}

}