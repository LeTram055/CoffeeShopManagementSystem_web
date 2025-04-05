@extends('admin/layouts/master')

@section('title')
Quản lý nhân viên
@endsection

@section('feature-title')
Quản lý nhân viên
@endsection

@section('custom-css')
<style>
.custom-tabs .nav-link {
    color: #555;
    font-weight: 500;
    border-radius: 8px 8px 0 0;
    transition: all 0.3s ease-in-out;
    padding: 6px 15px;
}

.custom-tabs .nav-link:hover {
    color: #000;
    background: #f8f9fa;
    border-color: #dee2e6 #dee2e6 transparent;
}

.custom-tabs .nav-link.active {
    color: #fff;
    background: #0049ab;
    border-color: #0049ab #0049ab transparent;
    font-weight: bold;
    box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.1);
}
</style>
@endsection

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
        <a href="{{ route('admin.employee.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
        <a href="{{ route('admin.employee.exportExcel') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
        <a href="{{ route('admin.employee.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-sync-alt"></i> Làm mới
        </a>
    </div>
    <form method="GET" action="{{ route('admin.employee.index') }}" class="d-flex" style="max-width: 50%;">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm nhân viên..."
                value="{{ request('search') }}">
            <input type="hidden" name="type" value="{{ request('type') }}">
            <button class="btn btn-bg" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>

<ul class="nav nav-tabs custom-tabs my-3" id="employeeTabs">
    <li class="nav-item">
        <a class="nav-link {{ request('type') == null ? 'active' : '' }}"
            href="{{ route('admin.employee.index', ['type' => null, 'search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection]) }}">Tất
            cả</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('type') == 'staff_counter' ? 'active' : '' }}"
            href="{{ route('admin.employee.index', ['type' => 'staff_counter', 'search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection]) }}">Nhân
            viên thu ngân</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('type') == 'staff_serve' ? 'active' : '' }}"
            href="{{ route('admin.employee.index', ['type' => 'staff_serve', 'search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection]) }}">Nhân
            viên phục vụ</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('type') == 'staff_barista' ? 'active' : '' }}"
            href="{{ route('admin.employee.index', ['type' => 'staff_barista', 'search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection]) }}">Nhân
            viên pha chế</a>
    </li>
</ul>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'employee_id', 'sort_direction' => $sortField == 'employee_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Mã nhân viên
                        @if($sortField == 'employee_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'name', 'sort_direction' => $sortField == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Tên nhân viên
                        @if($sortField == 'name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'username', 'sort_direction' => $sortField == 'username' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Tên đăng nhập
                        @if($sortField == 'username')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'role', 'sort_direction' => $sortField == 'role' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Vai trò
                        @if($sortField == 'role')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>

                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'phone_number', 'sort_direction' => $sortField == 'phone_number' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Số điện thoại
                        @if($sortField == 'phone_number')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'email', 'sort_direction' => $sortField == 'email' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Email
                        @if($sortField == 'email')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'address', 'sort_direction' => $sortField == 'address' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Địa chỉ
                        @if($sortField == 'address')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'start_date', 'sort_direction' => $sortField == 'start_date' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Ngày bắt đầu làm việc
                        @if($sortField == 'start_date')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'hourly_rate', 'sort_direction' => $sortField == 'hourly_rate' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Lương theo giờ
                        @if($sortField == 'hourly_rate')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'status', 'sort_direction' => $sortField == 'status' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Trạng thái
                        @if($sortField == 'status')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>

                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @foreach ($employees as $employee)
            @if(request('type') == null || request('type') == $employee->role)
            <tr>
                <td class="text-center">{{ $employee->employee_id }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->username }}</td>
                <td>
                    @php
                    $roles = [
                    'admin' => 'Quản trị viên',
                    'staff_serve' => 'Nhân viên phục vụ',
                    'staff_counter' => 'Nhân viên thu ngân',
                    'staff_barista' => 'Nhân viên pha chế'
                    ];
                    @endphp
                    {{ $roles[$employee->role] ?? 'Không xác định' }}
                </td>

                <td class="text-center">{{ $employee->phone_number }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->address }}</td>
                <td class="text-center">{{ $employee->start_date->format('d/m/Y') }}</td>
                <td class="text-center">{{ $employee->hourly_rate }}</td>
                <td class="text-center">
                    @php
                    $statuses = [
                    'active' => 'Đang hoạt động',
                    'locked' => 'Đã khóa'
                    ];
                    @endphp
                    {{ $statuses[$employee->status] ?? 'Không xác định' }}
                </td>


                <td class="text-center">
                    <a href="{{ route('admin.employee.edit', ['employee_id' => $employee->employee_id]) }}"
                        class="text-warning mx-2">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form class="d-inline delete-form" method="post" action="{{ route('admin.employee.delete') }}">
                        @csrf
                        <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
                        <button type="submit" class="btn btn-link text-danger p-0 border-0 delete-employee-btn">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <!-- Phân trang -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center my-4 gap-3">
        <form action="{{ route('admin.employee.index') }}" method="GET" class="d-flex align-items-center mt-1">
            @foreach(request()->except(['per_page', 'page']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <label for="per_page" class="me-2 text-nowrap">Hiển thị:</label>
            <select name="per_page" id="per_page" class="form-select form-select-sm w-auto"
                onchange="this.form.submit()">

                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </form>

        <div>
            {{ $employees->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="delete-confirm" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Xóa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
$(document).ready(function() {
    let formToSubmit;

    $('.delete-employee-btn').on('click', function(e) {
        e.preventDefault();
        formToSubmit = $(this).closest('form');
        const employeeName = $(this).closest('tr').find('td').eq(1).text();

        if (employeeName.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa nhân viên "${employeeName}" không?`);
        }
        $('#delete-confirm').modal('show');
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });

    // Tự động đóng thông báo sau 5 giây
    setTimeout(function() {
        $('.flash-message .alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection