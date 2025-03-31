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
@section('title', 'Quản lý lương')
@section('feature-title', 'Quản lý lương')

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }} position-relative">
        {{ Session::get('alert-' . $msg) }}
        <button type="button" class="btn-close position-absolute end-0 me-2" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </p>
    @endif
    @endforeach
</div>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div class="d-flex gap-2">
        <!-- <a href="{{ route('admin.salary.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus"></i> Thêm mới
        </a> -->
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
            data-bs-target="#createSalaryModal">
            <i class="fas fa-plus"></i> Thêm mới
        </button>

        <!-- <a href="{{ route('admin.salary.exportExcel') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a> -->
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportExcelModal">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </button>
        <a href="{{ route('admin.salary.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-sync-alt"></i> Làm mới
        </a>
    </div>
    <form method="GET" action="{{ route('admin.salary.index') }}" class="d-flex" style="max-width: 50%;">

        <select name="month" class="form-select">
            <option value="">Tất cả tháng</option>
            @for ($m = 1; $m <= 12; $m++) <option value="{{ $m }}" {{ $m == request('month') ? 'selected' : '' }}>Tháng
                {{ $m }}</option>
                @endfor
        </select>
        <select name="year" class="form-select ms-2">
            <option value="">Tất cả năm</option>
            @for ($y = date('Y') - 5; $y <= date('Y'); $y++) <option value="{{ $y }}"
                {{ $y == request('year') ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
        </select>
        <div class="input-group ms-2">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm nhân viên, mã lương..."
                value="{{ request('search') }}">
            <button class="btn btn-bg" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th class="text-center">
                    <a
                        href="{{ route('admin.salary.index', ['sort_field' => 'salary_id', 'sort_direction' => $sortField == 'salary_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã lương
                        @if($sortField == 'salary_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.salary.index', ['sort_field' => 'employee_id', 'sort_direction' => $sortField == 'employee_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Nhân viên
                        @if($sortField == 'employee_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.salary.index', ['sort_field' => 'month', 'sort_direction' => $sortField == 'month' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'month' => request('month'), 'year' => request('year')]) }}">
                        Tháng
                        @if($sortField == 'month')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.salary.index', ['sort_field' => 'year', 'sort_direction' => $sortField == 'year' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'month' => request('month'), 'year' => request('year')]) }}">
                        Năm
                        @if($sortField == 'year')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.salary.index', ['sort_field' => 'total_hours', 'sort_direction' => $sortField == 'total_hours' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'month' => request('month'), 'year' => request('year')]) }}">
                        Số giờ làm
                        @if($sortField == 'total_hours')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.salary.index', ['sort_field' => 'salary_per_hour', 'sort_direction' => $sortField == 'salary_per_hour' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'month' => request('month'), 'year' => request('year')]) }}">
                        Lương theo giờ
                        @if($sortField == 'salary_per_hour')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.salary.index', ['sort_field' => 'total_salary', 'sort_direction' => $sortField == 'total_salary' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'month' => request('month'), 'year' => request('year')]) }}">
                        Tổng lương
                        @if($sortField == 'total_salary')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.salary.index', ['sort_field' => 'total_bonus_penalty', 'sort_direction' => $sortField == 'total_bonus_penalty' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'month' => request('month'), 'year' => request('year')]) }}">
                        Thưởng/Phạt
                        @if($sortField == 'total_bonus_penalty')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.salary.index', ['sort_field' => 'final_salary', 'sort_direction' => $sortField == 'final_salary' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'month' => request('month'), 'year' => request('year')]) }}">
                        Lương cuối cùng
                        @if($sortField == 'final_salary')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @foreach ($salaries as $salary)
            <tr>
                <td class="text-center">{{ $salary->salary_id }}</td>
                <td class="text-center">{{ $salary->employee->name ?? 'Không xác định' }}</td>
                <td class="text-center">{{ $salary->month }}</td>
                <td class="text-center">{{ $salary->year }}</td>
                <td class="text-center">{{ $salary->total_hours }}</td>
                <td class="text-center">{{ number_format($salary->salary_per_hour, 0, ',', '.') }} VNĐ</td>
                <td class="text-center">{{ number_format($salary->total_salary, 0, ',', '.') }} VNĐ</td>
                <td class="text-center">{{ number_format($salary->total_bonus_penalty, 0, ',', '.') }} VNĐ</td>
                <td class="text-center">{{ number_format($salary->final_salary, 0, ',', '.') }} VNĐ</td>
                <td class="text-center">
                    <!-- <span class="badge bg-{{ $salary->status == 'paid' ? 'success' : 'warning' }}">
                        {{ ucfirst($salary->status) }}
                    </span> -->
                    <form method="POST" action="{{ route('admin.salary.update') }}">
                        @csrf
                        <input type="hidden" name="salary_id" value="{{ $salary->salary_id }}">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="pending" {{ $salary->status == 'pending' ? 'selected' : '' }}>Chờ duyệt
                            </option>
                            <option value="paid" {{ $salary->status == 'paid' ? 'selected' : '' }}>Đã trả</option>
                        </select>
                    </form>
                </td>
                <td class="text-center">
                    <a href="#" class="text-info mx-2" data-bs-toggle="modal" data-bs-target="#salaryDetailsModal"
                        onclick="showSalaryDetails('{{ $salary->salary_id }}')">
                        <i class="fa-solid fa-circle-info"></i>
                    </a>
                    <!-- <form class="d-inline delete-form" method="post" action="{{ route('admin.salary.delete') }}">
                        @csrf
                        <input type="hidden" name="salary_id" value="{{ $salary->salary_id }}">
                        <button type="submit" class="btn btn-link text-danger p-0 border-0 delete-salary-btn">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form> -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Thêm Bảng Lương -->
<div class="modal fade" id="createSalaryModal" tabindex="-1" aria-labelledby="createSalaryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSalaryModalLabel">Tạo Bảng Lương</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="salaryForm" method="POST" action="{{ route('admin.salary.save') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tháng</label>
                        <select name="month" class="form-select">
                            @for ($m = 1; $m <= 12; $m++) <option value="{{ $m }}"
                                {{ $m == $lastMonth ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                @endfor
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Năm</label>
                        < name="year" class="form-select">
                            @for ($y = $yearLimit - 5; $y <= $yearLimit; $y++) <option value="{{ $y }}"
                                {{ $y == $yearLimit ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                                </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nhân viên (Tùy chọn)</label>
                        <select name="employee_id" class="form-select">
                            <option value="">Tất cả nhân viên</option>
                            @foreach ($employees as $employee)
                            <option value="{{ $employee->employee_id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Tạo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chi Tiết Lương -->
<div class="modal fade" id="salaryDetailsModal" tabindex="-1" aria-labelledby="salaryDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title" id="salaryDetailsModalLabel">Chi Tiết Lương</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="salaryDetailsModalBody">
                <!-- Nội dung modal sẽ được cập nhật bằng JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Xuất Excel -->
<div class="modal fade" id="exportExcelModal" tabindex="-1" aria-labelledby="exportExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportExcelModalLabel">Xuất Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm" method="GET" action="{{ route('admin.salary.exportExcel') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tháng</label>
                        <select name="month" class="form-select">
                            <!-- <option value="">Chọn tháng</option> -->
                            @for ($m = 1; $m <= 12; $m++) <option value="{{ $m }}"
                                {{ $m == $currentMonth ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Năm</label>
                        <select name="year" class="form-select">
                            <!-- <option value="">Chọn năm</option> -->
                            @for ($y = $currentYear - 5; $y <= $currentYear; $y++) <option value="{{ $y }}"
                                {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Xuất Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom-scripts')
<script>
// $(document).ready(function() {
//     let formToSubmit;

//     $('.delete-salary-btn').on('click', function(e) {
//         e.preventDefault();
//         formToSubmit = $(this).closest('form');
//         $('#delete-confirm').modal('show');
//     });

//     $('#confirm-delete').on('click', function() {
//         formToSubmit.submit();
//     });


//     setTimeout(function() {
//         $('.flash-message .alert').fadeOut('slow');
//     }, 5000);
// });

$(document).ready(function() {
    // Khi form được submit, hiển thị thông báo và đóng modal
    $('#salaryForm').on('submit', function(e) {
        e.preventDefault(); // Ngăn chặn reload trang
        let formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            success: function(response) {
                $('#createSalaryModal').modal('hide'); // Ẩn modal
                location.reload(); // Làm mới trang để hiển thị dữ liệu mới
            },
            error: function(xhr) {
                alert("Có lỗi xảy ra, vui lòng thử lại!");
            }
        });
    });
});

function showSalaryDetails(salaryId) {
    $('#salaryDetailsModalBody').empty();
    $.ajax({
        type: 'GET',
        url: '/admin/salary/' + salaryId, // Đảm bảo URL đúng
        success: function(response) {
            $('#salaryDetailsModalBody').html(response); // Cập nhật nội dung modal
            $('#salaryDetailsModal').modal('show'); // Hiển thị modal
        },
        error: function(xhr) {
            alert("Có lỗi xảy ra, vui lòng thử lại!");
        }
    });
}

$(document).ready(function() {
    $('#exportForm').on('submit', function() {
        // Đóng modal khi form được gửi
        $('#exportExcelModal').modal('hide');
    });
});
</script>
@endsection