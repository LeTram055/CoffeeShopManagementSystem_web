<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\IngredientLogs;

class IngredientLogsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Lấy danh sách log nguyên liệu từ database.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return IngredientLogs::all();
    }

    /**
     * Định nghĩa tiêu đề của file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Mã log',
            'Tên nguyên liệu',
            'Số lượng thay đổi',
            'Lý do',
            'Nhân viên thực hiện',
            'Ngày tạo',
        ];
    }

    /**
     * Định nghĩa cách hiển thị dữ liệu trong Excel.
     *
     * @param \App\Models\IngredientLogs $log
     * @return array
     */
    public function map($log): array
    {
        return [
            $log->log_id,
            $log->ingredient->name ?? 'N/A',
            $log->quantity_change,
            $log->reason,
            $log->employee->name ?? 'N/A',
            $log->changed_at->format('H:m:s d/m/Y'),
        ];
    }
}