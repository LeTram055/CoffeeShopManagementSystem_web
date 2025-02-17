<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Customers;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Lấy toàn bộ danh sách khách hàng
    */
    public function collection()
    {
        return Customers::all();
    }

    /**
    * Định nghĩa tiêu đề cột
    */
    public function headings(): array
    {
        return [
            'Mã khách hàng',
            'Tên khách hàng',
            'Số điện thoại',
            'Ghi chú',
        ];
    }

    /**
    * Định nghĩa dữ liệu theo từng hàng
    */
    public function map($customer): array
    {
        return [
            $customer->customer_id,
            $customer->name,
            $customer->phone_number,
            $customer->notes,
        ];
    }
}