@extends('admin.layouts.master')

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

@section('title')
Quản lý khuyến mãi
@endsection

@section('feature-title')
Quản lý khuyến mãi
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
        <a href="{{ route('admin.promotion.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
        <a href="{{ route('admin.promotion.exportExcel') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
        <a href="{{ route('admin.promotion.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-sync-alt"></i> Làm mới
        </a>
    </div>
    <form method="GET" action="{{ route('admin.promotion.index') }}" class="d-flex" style="max-width: 50%;">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm khuyến mãi..."
                value="{{ request('search') }}">
            <input type="hidden" name="tab" value="{{ request('tab', 'all-promotions') }}">
            <button class="btn btn-bg" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>

<ul class="nav nav-tabs custom-tabs" id="promotionTabs">
    <li class="nav-item">
        <a class="nav-link {{ request('tab') == 'all-promotions' || request('tab') == null ? 'active' : '' }}"
            href="{{ route('admin.promotion.index', ['tab' => 'all-promotions', 'search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection, 'per_page' => request('per_page')]) }}">
            Tất cả
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('tab') == 'valid-promotions' ? 'active' : '' }}"
            href="{{ route('admin.promotion.index', ['tab' => 'valid-promotions', 'search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection, 'per_page' => request('per_page')]) }}">
            Hợp lệ
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade {{ $activeTab == 'all-promotions' ? 'show active' : '' }}" id="all-promotions">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'promotion_id', 'sort_direction' => $sortField == 'promotion_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Mã khuyến mãi
                                @if($sortField == 'promotion_id')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'name', 'sort_direction' => $sortField == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Tên khuyến mãi
                                @if($sortField == 'name')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'discount_type', 'sort_direction' => $sortField == 'discount_type' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Loại giảm giá
                                @if($sortField == 'discount_type')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'discount_value', 'sort_direction' => $sortField == 'discount_value' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Giá trị giảm
                                @if($sortField == 'discount_value')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'min_order_value', 'sort_direction' => $sortField == 'min_order_value' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Giá trị tối thiểu áp dụng
                                @if($sortField == 'min_order_value')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'start_date', 'sort_direction' => $sortField == 'start_date' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Ngày bắt đầu
                                @if($sortField == 'start_date')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'end_date', 'sort_direction' => $sortField == 'end_date' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Ngày kết thúc
                                @if($sortField == 'end_date')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'is_active', 'sort_direction' => $sortField == 'is_active' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Trạng thái
                                @if($sortField == 'is_active')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($promotions as $promotion)
                    <tr>
                        <td class="text-center">{{ $promotion->promotion_id }}</td>
                        <td>{{ $promotion->name }}</td>
                        <td class="text-center">
                            @php
                            $type = [
                            'percentage' => 'Phần trăm',
                            'fixed' => 'Cố định'
                            ];

                            @endphp
                            {{ $type[$promotion->discount_type] ?? '' }}
                        </td>
                        <td class="text-center">
                            @if($promotion->discount_type === 'percentage')
                            {{ number_format($promotion->discount_value, 0, ',', '.') }}%
                            @else
                            {{ number_format($promotion->discount_value, 0, ',', '.') }} VNĐ
                            @endif
                        </td>
                        <td class="text-center">
                            {{ number_format($promotion->min_order_value, 0, ',', '.') }} VNĐ
                        </td>

                        <td class="text-end">{{ $promotion->start_date->format('H:i:s d/m/Y') }}</td>
                        <td class="text-end">{{ $promotion->end_date->format('H:i:s d/m/Y') }}</td>
                        <td class="text-center">{{ $promotion->is_active ? 'Hoạt động' : 'Không hoạt động' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.promotion.edit', ['promotion_id' => $promotion->promotion_id]) }}"
                                class="text-warning mx-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form class="d-inline delete-form" method="post"
                                action="{{ route('admin.promotion.delete') }}">
                                @csrf
                                <input type="hidden" name="promotion_id" value="{{ $promotion->promotion_id }}">
                                <button type="submit"
                                    class="btn btn-link text-danger p-0 border-0 delete-promotion-btn">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Tab hợp lệ -->
    <div class="tab-pane fade {{ $activeTab == 'valid-promotions' ? 'show active' : '' }}" id="valid-promotions">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'promotion_id', 'sort_direction' => $sortField == 'promotion_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Mã khuyến mãi
                                @if($sortField == 'promotion_id')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'name', 'sort_direction' => $sortField == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Tên khuyến mãi
                                @if($sortField == 'name')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'discount_type', 'sort_direction' => $sortField == 'discount_type' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Loại giảm giá
                                @if($sortField == 'discount_type')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'discount_value', 'sort_direction' => $sortField == 'discount_value' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Giá trị giảm
                                @if($sortField == 'discount_value')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'min_order_value', 'sort_direction' => $sortField == 'min_order_value' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Giá trị tối thiểu áp dụng
                                @if($sortField == 'min_order_value')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'start_date', 'sort_direction' => $sortField == 'start_date' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Ngày bắt đầu
                                @if($sortField == 'start_date')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'end_date', 'sort_direction' => $sortField == 'end_date' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Ngày kết thúc
                                @if($sortField == 'end_date')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'is_active', 'sort_direction' => $sortField == 'is_active' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Trạng thái
                                @if($sortField == 'is_active')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($promotions as $promotion)
                    @if($promotion->is_active && $promotion->start_date <= now() && $promotion->end_date >= now())
                        <tr>
                            <td class="text-center">{{ $promotion->promotion_id }}</td>
                            <td>{{ $promotion->name }}</td>
                            <td>{{ $promotion->discount_type }}</td>
                            <td class="text-center">
                                @if($promotion->discount_type === 'percentage')
                                {{ number_format($promotion->discount_value, 0, ',', '.') }}%
                                @else
                                {{ number_format($promotion->discount_value, 0, ',', '.') }} VNĐ
                                @endif
                            </td>
                            <td class="text-center">
                                {{ number_format($promotion->min_order_value, 0, ',', '.') }} VNĐ
                            </td>
                            <td class="text-end">{{ $promotion->start_date->format('H:i:s d/m/Y') }}</td>
                            <td class="text-end">{{ $promotion->end_date->format('H:i:s d/m/Y') }}</td>
                            <td class="text-center">{{ $promotion->is_active ? 'Hoạt động' : 'Không hoạt động' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.promotion.edit', ['promotion_id' => $promotion->promotion_id]) }}"
                                    class="text-warning mx-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form class="d-inline delete-form" method="post"
                                    action="{{ route('admin.promotion.delete') }}">
                                    @csrf
                                    <input type="hidden" name="promotion_id" value="{{ $promotion->promotion_id }}">
                                    <button type="submit"
                                        class="btn btn-link text-danger p-0 border-0 delete-promotion-btn">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Phân trang -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <form action="{{ route('admin.promotion.index') }}" method="GET" class="d-flex align-items-center mt-1">
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
            {{ $promotions->appends([
        'tab' => request('tab', 'all-promotions'),
        'search' => request('search'),
        'sort_field' => $sortField,
        'sort_direction' => $sortDirection,
        'per_page' => request('per_page'),
    ])->links('pagination::bootstrap-5') }}
        </div>
    </div>
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

    $('.delete-promotion-btn').on('click', function(e) {
        e.preventDefault();

        formToSubmit = $(this).closest('form');
        const promotionName = $(this).closest('tr').find('td').eq(1).text();

        if (promotionName) {
            $('.modal-body').html(`Bạn có chắc chắn muốn xóa khuyến mãi ${promotionName} không?`);
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