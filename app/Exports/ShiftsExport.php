<?php

namespace App\Exports;

use App\Models\Shifts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

class ShiftsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Shifts::all();
    }

    public function headings(): array
    {
        return ['ID', 'Tên Ca', 'Thời Gian Bắt Đầu', 'Thời Gian Kết Thúc'];
    }

    public function map($shift): array
    {
        return [
            $shift->shift_id,
            $shift->name,
            $shift->start_time->format('H:i'),
            $shift->end_time->format('H:i'),
        ];
    }
}