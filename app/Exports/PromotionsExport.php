<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Promotions;

class PromotionsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Promotions::all();
    }

    public function headings(): array
    {
        return [
            'Mã khuyến mãi',
            'Tên khuyến mãi',
            'Loại giảm giá',
            'Giá trị giảm giá',
            'Ngày bắt đầu',
            'Ngày kết thúc',
            'Trạng thái',
            
        ];
    }

    public function map($promotion): array
    {
        return [
            $promotion->promotion_id,
            $promotion->name,
            $promotion->discount_type,
            $promotion->discount_value,
            $promotion->start_date,
            $promotion->end_date,
            $promotion->is_active,
            
        ];
    }
}