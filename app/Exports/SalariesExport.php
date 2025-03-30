<?php

namespace App\Exports;

use App\Models\Salaries;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalariesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return Salaries::with('employee')
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Mã lương',
            'Tên nhân viên',
            'Tháng',
            'Năm',
            'Số giờ làm',
            'Lương theo giờ',
            'Tổng lương',
            'Thưởng/Phạt',
            'Lương cuối cùng',
            'Trạng thái',
        ];
    }

    public function map($salary): array
    {
        return [
            $salary->salary_id, // Sửa thành salary_id nếu bạn sử dụng trường này
            $salary->employee->name,
            $salary->month,
            $salary->year,
            $salary->total_hours,
            $salary->salary_per_hour,
            $salary->total_salary,
            $salary->total_bonus_penalty,
            $salary->final_salary,
            $this->getStatusInVietnamese($salary->status), // Gọi hàm để lấy trạng thái tiếng Việt
        ];
    }

    private function getStatusInVietnamese($status)
    {
        switch ($status) {
            case 'pending':
                return 'Chờ duyệt';
            case 'paid':
                return 'Đã trả';
            default:
                return 'Không xác định'; // Trả về giá trị mặc định nếu không khớp
        }
    }
}