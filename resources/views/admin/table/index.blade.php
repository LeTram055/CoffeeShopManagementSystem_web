@extends('admin.layouts.master')

@section('title', 'Quản lý bàn')
@section('feature-title', 'Quản lý bàn')

@section('custom-css')
<style>
/* Tùy chỉnh card bàn */
.table-card {
    cursor: pointer;
    transition: transform 0.2s ease-in-out;
    border: none;
}

.table-card:hover {
    transform: scale(1.02);
}

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

.status-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
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
        <a href="{{ route('admin.table.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
        <a href="{{ route('admin.table.exportExcel') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
        <a href="{{ route('admin.table.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-sync-alt"></i> Làm mới
        </a>
    </div>
    <!-- Legend trạng thái bàn với dấu chấm -->
    <div class="mb-3 d-flex justify-content-end align-items-center gap-3">
        <div class="d-flex align-items-center">
            <span class="status-dot bg-success me-2"></span> Trống
        </div>
        <div class="d-flex align-items-center">
            <span class="status-dot bg-primary me-2"></span> Đang sử dụng
        </div>
        <div class="d-flex align-items-center">
            <span class="status-dot bg-secondary me-2"></span> Đang sửa
        </div>
    </div>
    <form method="GET" action="{{ route('admin.table.index') }}" class="d-flex" style="max-width: 50%;">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm danh mục..."
                value="{{ request('search') }}">
            <button class="btn btn-bg" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>

<!-- <div class="mb-3 d-flex justify-content-end">
    <span class="badge rounded-pill bg-success me-2">Trống</span>
    <span class="badge rounded-pill bg-primary me-2">Đang sử dụng</span>
    <span class="badge rounded-pill bg-secondary me-2">Đang sửa</span>
</div> -->




<ul class="nav nav-tabs custom-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ request('status') == null ? 'active' : '' }}" href="{{ route('admin.table.index') }}">
            Tất cả
        </a>
    </li>
    @foreach($statuses as $status)
    <li class="nav-item">
        <a class="nav-link {{ request('status') == $status->status_id ? 'active' : '' }}"
            href="{{ route('admin.table.index', ['status' => $status->status_id]) }}">
            {{ $status->name }}
        </a>
    </li>
    @endforeach
</ul>
<!-- Danh sách bàn -->
<div class="row">
    @foreach($tables as $table)
    @php

    switch ($table->status->name) {
    case 'Trống':
    $bgClass = 'bg-success';
    break;
    case 'Đang sử dụng':
    $bgClass = 'bg-primary';
    break;
    case 'Đang sửa':
    $bgClass = 'bg-secondary';
    break;
    default:
    $badgeClass = 'bg-secondary-subtle';
    break;
    }
    @endphp
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
        <div class="card table-card {{ $bgClass }} text-white">
            <div class="card-body text-center">
                <h5 class="card-title">Bàn {{ $table->table_number }}</h5>
                <p class="card-text">Số lượng ghế: {{ $table->capacity }}</p>

                <div class="d-flex justify-content-center">
                    <a href="{{ route('admin.table.edit', ['table_id' => $table->table_id]) }}"
                        class="text-warning mx-2"><i class="fa-solid fa-pen-to-square"></i></a>
                    <form class="mx-1" method="POST" name=frmDelete action="{{ route('admin.table.delete') }}">
                        @csrf
                        <input type="hidden" name="table_id" value="{{ $table->table_id }}">

                        <button type="submit" class="btn btn-link text-danger p-0 border-0 delete-table-btn"><i
                                class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal -->
<div class="modal fade" id="delete-confirm" tabindex="-1" aria-labelledby="ddeleteConfirmLabel" role="dialog"
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

    $('.delete-table-btn').on('click', function(e) {
        e.preventDefault();

        formToSubmit = $(this).closest('form');
        const tableNumber = $(this).closest('.card').find('.card-title').text();

        if (tableNumber) {
            $('.modal-body').html(`Bạn có chắc chắn muốn xóa ${tableNumber} không?`);
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