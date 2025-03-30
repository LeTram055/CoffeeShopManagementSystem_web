@php
$currentMonth = date('n'); // Lấy tháng hiện tại (1-12)
$currentYear = date('Y'); // Lấy năm hiện tại

// Xác định tháng trước
if ($currentMonth == 1) {
$lastMonth = 12;
$yearLimit = $currentYear - 1;
} else {
$lastMonth = $currentMonth - 1;
$yearLimit = $currentYear;
}
@endphp

@extends('admin.layouts.master')
@section('title', 'Tạo Bảng Lương')
@section('feature-title', 'Tạo Bảng Lương')

@section('content')
<form method="POST" action="{{ route('admin.salary.save') }}">
    @csrf
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div class="d-flex gap-2">
            {{-- Chọn tháng chỉ cho phép từ tháng trước trở về trước --}}
            <select name="month" class="form-select">
                @for ($m = 1; $m <= 12; $m++) <option value="{{ $m }}" {{ $m == $lastMonth ? 'selected' : '' }}>
                    Tháng {{ $m }}
                    </option>
                    @endfor
            </select>

            {{-- Chọn năm từ 5 năm trước đến hiện tại, mặc định là năm của tháng lương được chọn --}}
            <select name="year" class="form-select">
                @for ($y = $yearLimit - 5; $y <= $yearLimit; $y++) <option value="{{ $y }}"
                    {{ $y == $yearLimit ? 'selected' : '' }}>
                    {{ $y }}
                    </option>
                    @endfor
            </select>

            {{-- Chọn nhân viên (tuỳ chọn) --}}
            <select name="employee_id" class="form-select">
                <option value="">Tất cả nhân viên</option>
                @foreach ($employees as $employee)
                <option value="{{ $employee->employee_id }}">{{ $employee->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Tạo Bảng Lương</button>
        </div>
    </div>
</form>
@endsection