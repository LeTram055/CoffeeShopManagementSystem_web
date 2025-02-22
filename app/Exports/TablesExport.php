<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Tables;

class TablesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Tables::all();
    }

    public function headings(): array
    {
        return [
            'Mã bàn',
            'Số bàn',
            'Trạng thái',
            
        ];
    }

    public function map($table): array
    {
        return [
            $table->table_id,
            $table->table_number,
            $table->status->name,
            
        ];
    }
}