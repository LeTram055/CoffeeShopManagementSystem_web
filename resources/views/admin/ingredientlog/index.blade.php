@extends('admin/layouts/master')

@section('title')
Quản lý log nguyên liệu
@endsection

@section('feature-title')
Quản lý log nguyên liệu
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

<form method="GET" action="{{ route('admin.ingredientlog.index') }}" class="row mb-3 justify-content-center">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm log nguyên liệu..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded ms-2" type="submit">Tìm kiếm</button>
        </div>
    </div>
</form>

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.ingredientlog.exportExcel') }}" class="btn btn-success">Xuất Excel</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'log_id', 'sort_direction' => $sortField == 'log_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã log
                        @if($sortField == 'log_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'ingredient_name', 'sort_direction' => $sortField == 'ingredient_name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tên nguyên liệu
                        @if($sortField == 'ingredient_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'quantity_change', 'sort_direction' => $sortField == 'quantity_change' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Số lượng thay đổi
                        @if($sortField == 'quantity_change')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'reason', 'sort_direction' => $sortField == 'reason' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Lý do
                        @if($sortField == 'reason')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'employee_name', 'sort_direction' => $sortField == 'employee_name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Nhân viên thực hiện
                        @if($sortField == 'employee_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'changed_at', 'sort_direction' => $sortField == 'changed_at' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Thời gian thay đổi
                        @if($sortField == 'changed_at')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($ingredientLogs as $log)
            <tr>
                <td class="text-center">{{ $log->log_id }}</td>
                <td>{{ $log->ingredient->name }}</td>
                <td class="text-center">{{ $log->quantity_change }}</td>
                <td>{{ $log->reason ?? 'Không có' }}</td>
                <td>{{ $log->employee->name }}</td>
                <td class="text-center">{{ $log->changed_at->format('H:i:s d/m/Y') }}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Xóa -->
<div class="modal fade" id="delete-confirm" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
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
    $('.delete-log-btn').on('click', function(e) {
        e.preventDefault();
        formToSubmit = $(this).closest('form');
        $('#delete-confirm').modal('show');
    });
    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });
});
</script>
@endsection