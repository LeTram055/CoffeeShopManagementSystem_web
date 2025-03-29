<?php

namespace App\Exports;

use App\Models\WorkSchedules;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

class WorkSchedulesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return WorkSchedules::all();
    }

    public function headings(): array
    {
        return ['Mã lịch làm việc', 'Nhân viên', 'Ca', 'Ngày làm việc', 'Trạng thái','Số giờ làm'];
    }

    public function map($schedule): array
    {
        return [
            $schedule->schedule_id,
            $schedule->employee->name,
            $schedule->shift->name,
            $schedule->work_date,
            $schedule->status,
            $schedule->work_hours
        ];
    }
}