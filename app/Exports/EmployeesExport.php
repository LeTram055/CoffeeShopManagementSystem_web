<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Employees;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    
    public function collection()
    {
        return Employees::all();
    }

    public function headings(): array
    {
        return [
            'Mã nhân viên',
            'Tên nhân viên',
            'Tên đăng nhập',
            'Vai trò',
            'Số điện thoại',
            'Email',
            'Địa chỉ',
            'Ngày bắt đầu',
            'Trạng thái',
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->employee_id,
            $employee->name,
            $employee->username,
            $employee->role,
            $employee->phone_number,
            $employee->email,
            $employee->address,
            $employee->start_date,
            $employee->status,
        ];
    }
}