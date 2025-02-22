<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Categories;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Categories::all();
    }

    public function headings(): array
    {
        return [
            'Mã danh mục',
            'Tên danh mục',
            
        ];
    }

    public function map($category): array
    {
        return [
            $category->category_id,
            $category->name,
            
        ];
    }
}