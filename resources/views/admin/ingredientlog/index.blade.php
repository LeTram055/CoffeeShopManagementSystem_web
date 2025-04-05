@extends('admin/layouts/master')

@section('title')
Quản lý log nguyên liệu
@endsection

@section('feature-title')
Quản lý log nguyên liệu
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
        <a href="{{ route('admin.ingredientlog.exportExcel') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
        <a href="{{ route('admin.ingredientlog.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-sync-alt"></i> Làm mới
        </a>
    </div>
    <form method="GET" action="{{ route('admin.ingredientlog.index') }}" class="d-flex" style="max-width: 50%;">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm danh mục..."
                value="{{ request('search') }}">
            <input type="hidden" name="type" value="{{ request('type') }}">
            <button class="btn btn-bg" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>

<ul class="nav nav-tabs custom-tabs my-3" id="ingredientLogTabs">
    <li class="nav-item">
        <a class="nav-link {{ request('type') == null ? 'active' : '' }}"
            href="{{ route('admin.ingredientlog.index', ['type' => null, 'search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection]) }}">Tất
            cả</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('type') == 'import' ? 'active' : '' }}"
            href="{{ route('admin.ingredientlog.index', ['type' => 'import', 'search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection]) }}">Nhận</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('type') == 'export' ? 'active' : '' }}"
            href="{{ route('admin.ingredientlog.index', ['type' => 'export', 'search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection]) }}">Xuất</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('type') == 'adjustment' ? 'active' : '' }}"
            href="{{ route('admin.ingredientlog.index', ['type' => 'adjustment', 'search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection]) }}">Điều
            chỉnh</a>
    </li>
</ul>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'log_id', 'sort_direction' => $sortField == 'log_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Mã log
                        @if($sortField == 'log_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'ingredient_name', 'sort_direction' => $sortField == 'ingredient_name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Tên nguyên liệu
                        @if($sortField == 'ingredient_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'quantity_change', 'sort_direction' => $sortField == 'quantity_change' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Số lượng thay đổi
                        @if($sortField == 'quantity_change')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>

                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'reason', 'sort_direction' => $sortField == 'reason' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Lý do
                        @if($sortField == 'reason')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'price', 'sort_direction' => $sortField == 'price' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Giá nhập
                        @if($sortField == 'price')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'new_cost_price', 'sort_direction' => $sortField == 'new_cost_price' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Giá mới
                        @if($sortField == 'new_cost_price')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'log_type', 'sort_direction' => $sortField == 'log_type' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Loại cập nhật
                        @if($sortField == 'log_type')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'employee_name', 'sort_direction' => $sortField == 'employee_name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Nhân viên thực hiện
                        @if($sortField == 'employee_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredientlog.index', ['sort_field' => 'changed_at', 'sort_direction' => $sortField == 'changed_at' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'type' => request('type')]) }}">
                        Thời gian thay đổi
                        @if($sortField == 'changed_at')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
            </tr>
        </thead>

        <tbody class="table-group-divider">
            @foreach ($ingredientLogs as $log)
            @if(request('type') == null || request('type') == $log->log_type)
            <tr>

                <td class="text-center">{{ $log->log_id }}</td>
                <td>{{ $log->ingredient->name }}</td>
                <td class="text-center">{{ number_format($log->quantity_change, 2, ',', '.') }}</td>
                <td>{{ $log->reason ?? 'Không có' }}</td>
                <td class="text-center">{{ number_format($log->price, 0, ',', '.') }} VNĐ</td>
                <td class="text-center">{{ number_format($log->new_cost_price, 0, ',', '.') }} VNĐ</td>
                <td class="text-center">
                    @switch($log->log_type)
                    @case('import')
                    Nhập
                    @break
                    @case('export')
                    Xuất
                    @break
                    @case('adjustment')
                    Điều chỉnh
                    @break
                    @default
                    Không xác định
                    @endswitch
                </td>
                <td>{{ $log->employee->name }}</td>
                <td class="text-center">{{ $log->changed_at->format('H:i:s d/m/Y') }}</td>

            </tr>
            @endif
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

    // Tự động đóng thông báo sau 5 giây
    setTimeout(function() {
        $('.flash-message .alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection