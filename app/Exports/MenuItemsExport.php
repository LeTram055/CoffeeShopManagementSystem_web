<?php

namespace App\Exports;

use App\Models\MenuItems;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MenuItemsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Lấy dữ liệu thực đơn và nguyên liệu từ database
     */
    public function collection()
    {
        return MenuItems::all();
        // return MenuItems::with('ingredients.ingredient')->get()->map(function ($item) {
        //     return [
        //         'Mã sản phẩm' => $item->id,
        //         'Tên sản phẩm' => $item->name,
        //         'Giá' => $item->price,
        //         'Mô tả' => $item->description,
        //         'Nguyên liệu' => $item->ingredients->map(function ($ing) {
        //             return $ing->ingredient->name . ' (' . $ing->quantity_per_unit . ')';
        //         })->implode(', '), // Gộp nguyên liệu thành chuỗi
        //     ];
        // });
    }

    /**
     * Tiêu đề của các cột trong Excel
     */
    public function headings(): array
    {
        return ["Mã sản phẩm", "Tên sản phẩm", "Giá", "Mô tả", "Nguyên liệu"];
    }

    /**
     * Định dạng dữ liệu trong Excel
     */
    public function map($item): array
    {
        return [
            $item->id,
            $item->name,
            $item->price,
            $item->description,
            $item->ingredients->map(function ($ing) {
                return $ing->ingredient->name . ' (' . $ing->quantity_per_unit . ')';
            })->implode(', '), // Gộp nguyên liệu thành chuỗi
        ];
    }
}