@extends('admin.layouts.master')

@section('title', 'Quản lý bàn')
@section('feature-title', 'Quản lý bàn')

@section('custom-css')
<style>
/* Tùy chỉnh card bàn */
.table-card {
    cursor: pointer;
    transition: transform 0.2s ease-in-out;
}

.table-card:hover {
    transform: scale(1.02);
}
</style>
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

<form method="GET" action="{{ route('admin.table.index') }}" class="row mb-3 justify-content-center">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm khuyến mãi..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded ms-2" type="submit">Tìm kiếm</button>
        </div>
    </div>
</form>

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.table.create') }}" class="btn btn-primary">Thêm mới</a>
    <a href="{{ route('admin.table.exportExcel') }}" class="btn btn-success">Xuất Excel</a>
</div>

<!-- Legend trạng thái bàn -->
<div class="mb-3">
    <span class="badge rounded-pill bg-success me-2">Trống</span>
    <span class="badge rounded-pill bg-primary me-2">Đang sử dụng</span>
    <span class="badge rounded-pill bg-secondary me-2">Đang sửa</span>
</div>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link text-dark {{ request('status') == null ? 'active' : '' }}"
            href="{{ route('admin.table.index') }}">
            Tất cả
        </a>
    </li>
    @foreach($statuses as $status)
    <li class="nav-item">
        <a class="nav-link text-dark {{ request('status') == $status->status_id ? 'active' : '' }}"
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
                        class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                    <form class="mx-1" method="POST" name=frmDelete action="{{ route('admin.table.delete') }}">
                        @csrf
                        <input type="hidden" name="table_id" value="{{ $table->table_id }}">

                        <button type="submit" class="btn btn-danger btn-sm delete-table-btn"><i
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
});
</script>
@endsection