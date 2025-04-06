@php
$currentMonth = date('n'); // Lấy tháng hiện tại (1-12)
$currentYear = date('Y'); // Lấy năm hiện tại
@endphp

@extends('admin.layouts.master')

@section('title', 'Quản lý thưởng phạt')

@section('feature-title', 'Quản lý thưởng phạt')

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }}">
        {{ Session::get('alert-' . $msg) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </p>
    @endif
    @endforeach
</div>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div class="d-flex gap-2">
        <a href="{{ route('admin.bonuspenalty.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
        <!-- <a href="{{ route('admin.bonuspenalty.exportExcel') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a> -->
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportExcelModal">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </button>
        <a href="{{ route('admin.bonuspenalty.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-sync-alt"></i> Làm mới
        </a>
    </div>

    <form method="GET" action="{{ route('admin.bonuspenalty.index') }}" class="d-flex" style="max-width: 50%;">
        <div class="dropdown me-2">
            <button class="btn btn-outline-info dropdown-toggle" type="button" id="dateDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                Lọc theo ngày
            </button>
            <div class="dropdown-menu p-3" aria-labelledby="dateDropdown">
                <div class="mb-2">
                    <label for="start_date" class="form-label">Từ ngày:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="{{ request('start_date') }}">
                </div>
                <div class="mb-2">
                    <label for="end_date" class="form-label">Đến ngày:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="{{ request('end_date') }}">
                </div>
                <button type="submit" class="btn btn-primary w-100">Áp dụng</button>
            </div>
        </div>
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm ca làm việc..."
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
                        href="{{ route('admin.bonuspenalty.index', ['sort_field' => 'bonus_penalty_id', 'sort_direction' => $sortField == 'bonus_penalty_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã
                        @if($sortField == 'bonus_penalty_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.bonuspenalty.index', ['sort_field' => 'employee_name', 'sort_direction' => $sortField == 'employee_name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Nhân viên
                        @if($sortField == 'employee_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>


                <th class="text-center">
                    <a
                        href="{{ route('admin.bonuspenalty.index', ['sort_field' => 'amount', 'sort_direction' => $sortField == 'amount' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Số tiền
                        @if($sortField == 'amount')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    Lý do
                </th>

                <th class="text-center">
                    <a
                        href="{{ route('admin.bonuspenalty.index', ['sort_field' => 'date', 'sort_direction' => $sortField == 'date' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Ngày
                        @if($sortField == 'date')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @foreach ($bonusesPenalties as $bonuspenalty)
            <tr>
                <td class="text-center">{{ $bonuspenalty->bonus_penalty_id }}</td>
                <td class="text-center">{{ $bonuspenalty->employee->name }}</td>
                <td class="text-center">{{ number_format($bonuspenalty->amount, 0, ',', '.') }} VNĐ</td>
                <td class="text-center">{{ $bonuspenalty->reason }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($bonuspenalty->date)->format('d/m/Y') }}</td>
                <td class="text-center">
                    <a href="{{ route('admin.bonuspenalty.edit', ['bonus_penalty_id' => $bonuspenalty->bonus_penalty_id]) }}"
                        class="text-warning mx-2">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form class="d-inline delete-form" method="post" action="{{ route('admin.bonuspenalty.delete') }}">
                        @csrf
                        <input type="hidden" name="bonuspenalty_id" value="{{ $bonuspenalty->bonus_penalty_id }}">
                        <button type="submit" class="btn btn-link text-danger p-0 border-0 delete-bonuspenalty-btn">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <form action="{{ route('admin.bonuspenalty.index') }}" method="GET" class="d-flex align-items-center mt-1">
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
            {{ $bonusesPenalties->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
<!-- Modal Xóa-->
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

<!-- Modal Xuất Excel -->
<div class="modal fade" id="exportExcelModal" tabindex="-1" aria-labelledby="exportExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportExcelModalLabel">Xuất Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm" method="GET" action="{{ route('admin.bonuspenalty.exportExcel') }}">
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
$(document).ready(function() {
    let formToSubmit;

    $('.delete-bonuspenalty-btn').on('click', function(e) {
        e.preventDefault();
        formToSubmit = $(this).closest('form');
        const employeeName = $(this).closest('tr').find('td').eq(1).text();
        const amount = $(this).closest('tr').find('td').eq(2).text();

        $('.modal-body').html(
            `Bạn có chắc chắn muốn xóa khoản "${amount}" của nhân viên "${employeeName}" không?`
        );

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

$(document).ready(function() {
    $('#exportForm').on('submit', function() {
        // Đóng modal khi form được gửi
        $('#exportExcelModal').modal('hide');
    });
});
</script>
@endsection