<?php

namespace App\Exports;

use App\Models\BonusesPenalties;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BonusesPenaltiesExport implements FromCollection, WithHeadings, WithMapping
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
        return BonusesPenalties::with('employee')
        ->whereMonth('date', $this->month)
        ->whereYear('date', $this->year)
        ->get();
    }

    public function headings(): array
    {
        return ['Mã', 'Nhân viên', 'Số tiền', 'Lý do', 'Ngày'];
    }

    public function map($bonusPenalty): array
    {
        return [
            $bonusPenalty->bonus_penalty_id,
            $bonusPenalty->employee ? $bonusPenalty->employee->name : 'N/A',
            number_format($bonusPenalty->amount, 0, ',', '.') . ' VND',
            $bonusPenalty->reason,
            $bonusPenalty->date->format('d/m/Y'),
        ];
    }
}