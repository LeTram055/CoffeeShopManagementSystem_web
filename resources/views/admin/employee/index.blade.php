@extends('admin/layouts/master')

@section('title')
Quản lý nhân viên
@endsection

@section('feature-title')
Quản lý nhân viên
@endsection

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="btn-close"
            data-bs-dismiss="alert" aria-label="Close"></p>
    @endif
    @endforeach
</div>

<form method="GET" action="{{ route('admin.employee.index') }}" class="row mb-3 justify-content-center">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm nhân viên..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded ms-2" type="submit">Tìm kiếm</button>
        </div>
    </div>
</form>

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.employee.create') }}" class="btn btn-primary">Thêm mới</a>
    <a href="{{ route('admin.employee.exportExcel') }}" class="btn btn-success">Xuất Excel</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'employee_id', 'sort_direction' => $sortField == 'employee_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã nhân viên
                        @if($sortField == 'employee_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'name', 'sort_direction' => $sortField == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tên nhân viên
                        @if($sortField == 'name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'username', 'sort_direction' => $sortField == 'username' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tên đăng nhập
                        @if($sortField == 'username')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'role', 'sort_direction' => $sortField == 'role' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Vai trò
                        @if($sortField == 'role')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>

                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'phone_number', 'sort_direction' => $sortField == 'phone_number' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Số điện thoại
                        @if($sortField == 'phone_number')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'email', 'sort_direction' => $sortField == 'email' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Email
                        @if($sortField == 'email')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'address', 'sort_direction' => $sortField == 'address' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Địa chỉ
                        @if($sortField == 'address')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'start_date', 'sort_direction' => $sortField == 'start_date' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Ngày bắt đầu làm việc
                        @if($sortField == 'start_date')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.employee.index', ['sort_field' => 'status', 'sort_direction' => $sortField == 'status' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Trạng thái
                        @if($sortField == 'status')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>

                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
            <tr>
                <td class="text-center">{{ $employee->employee_id }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->username }}</td>
                <td>
                    @php
                    $roles = [
                    'admin' => 'Quản trị viên',
                    'staff_serve' => 'Nhân viên phục vụ',
                    'staff_counter' => 'Nhân viên quầy',
                    'staff_barista' => 'Nhân viên pha chế'
                    ];
                    @endphp
                    {{ $roles[$employee->role] ?? 'Không xác định' }}
                </td>

                <td>{{ $employee->phone_number }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->address }}</td>
                <td>{{ $employee->start_date->format('d/m/Y') }}</td>
                <td>
                    @php
                    $statuses = [
                    'active' => 'Đang hoạt động',
                    'locked' => 'Đã khóa'
                    ];
                    @endphp
                    {{ $statuses[$employee->status] ?? 'Không xác định' }}
                </td>


                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.employee.edit', ['employee_id' => $employee->employee_id]) }}"
                            class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form class="mx-1" name="frmDelete" method="post" action="{{ route('admin.employee.delete') }}">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
                            <button type="submit" class="btn btn-danger btn-sm delete-employee-btn"><i
                                    class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
});
</script>
@endsection