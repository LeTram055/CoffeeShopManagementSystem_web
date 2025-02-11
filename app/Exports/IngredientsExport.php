<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Ingredients;

class IngredientsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Lấy danh sách nguyên liệu từ database.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Ingredients::all();
    }

    /**
     * Định nghĩa tiêu đề của file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Mã nguyên liệu',
            'Tên nguyên liệu',
            'Số lượng',
            'Đơn vị',
            'Số lượng tối thiểu',
            'Cập nhật lần cuối',
        ];
    }

    /**
     * Định nghĩa cách hiển thị dữ liệu trong Excel.
     *
     * @param \App\Models\Ingredients $ingredient
     * @return array
     */
    public function map($ingredient): array
    {
        return [
            $ingredient->id,
            $ingredient->name,
            $ingredient->quantity,
            $ingredient->unit,
            $ingredient->min_quantity,
            $ingredient->last_updated->format('H:i:s d/m/Y'),
        ];
    }
}