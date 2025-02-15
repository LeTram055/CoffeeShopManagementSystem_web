@extends('admin.layouts.master')

@section('title', 'Quản lý khuyến mãi')

@section('feature-title', 'Quản lý khuyến mãi')

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="btn-close"
            data-bs-dismiss="alert" aria-label="Close"></button></p>
    @endif
    @endforeach
</div>

<form method="GET" action="{{ route('admin.promotion.index') }}" class="row mb-3 justify-content-center">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm khuyến mãi..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded ms-2" type="submit">Tìm kiếm</button>
        </div>
    </div>
</form>

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.promotion.create') }}" class="btn btn-primary">Thêm mới</a>
    <a href="{{ route('admin.promotion.exportExcel') }}" class="btn btn-success">Xuất Excel</a>
</div>

<ul class="nav nav-tabs" id="promotionTabs">
    <li class="nav-item">
        <a class="nav-link active text-dark" id="all-promotions-tab" data-toggle="tab" href="#all-promotions">Tất cả</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-dark" id="valid-promotions-tab" data-toggle="tab" href="#valid-promotions">Hợp
            lệ</a>
    </li>
</ul>
<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="all-promotions">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'promotion_id', 'sort_direction' => $sortField == 'promotion_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Mã khuyến mãi
                                @if($sortField == 'promotion_id')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'name', 'sort_direction' => $sortField == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Tên khuyến mãi
                                @if($sortField == 'name')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'discount_type', 'sort_direction' => $sortField == 'discount_type' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Loại giảm giá
                                @if($sortField == 'discount_type')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'discount_value', 'sort_direction' => $sortField == 'discount_value' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Giá trị giảm
                                @if($sortField == 'discount_value')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'min_order_value', 'sort_direction' => $sortField == 'min_order_value' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Giá trị tối thiểu áp dụng
                                @if($sortField == 'min_order_value')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'start_date', 'sort_direction' => $sortField == 'start_date' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Ngày bắt đầu
                                @if($sortField == 'start_date')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'end_date', 'sort_direction' => $sortField == 'end_date' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Ngày kết thúc
                                @if($sortField == 'end_date')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'is_active', 'sort_direction' => $sortField == 'is_active' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Trạng thái
                                @if($sortField == 'is_active')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($promotions as $promotion)
                    <tr>
                        <td class="text-center">{{ $promotion->promotion_id }}</td>
                        <td>{{ $promotion->name }}</td>
                        <td>{{ $promotion->discount_type }}</td>
                        <td class="text-end">{{ $promotion->discount_value }}</td>
                        <td class="text-end">{{ $promotion->min_order_value }}</td>
                        <td class="text-end">{{ $promotion->start_date->format('H:i:s d/m/Y') }}</td>
                        <td class="text-end">{{ $promotion->end_date->format('H:i:s d/m/Y') }}</td>
                        <td class="text-center">{{ $promotion->is_active ? 'Hoạt động' : 'Không hoạt động' }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('admin.promotion.edit', ['promotion_id' => $promotion->promotion_id]) }}"
                                    class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                                <form class="mx-1" method="POST" name=frmDelete
                                    action="{{ route('admin.promotion.delete') }}">
                                    @csrf
                                    <input type="hidden" name="promotion_id" value="{{ $promotion->promotion_id }}">

                                    <button type="submit" class="btn btn-danger btn-sm delete-promotion-btn"><i
                                            class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="valid-promotions">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'promotion_id', 'sort_direction' => $sortField == 'promotion_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Mã khuyến mãi
                                @if($sortField == 'promotion_id')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'name', 'sort_direction' => $sortField == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Tên khuyến mãi
                                @if($sortField == 'name')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'discount_type', 'sort_direction' => $sortField == 'discount_type' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Loại giảm giá
                                @if($sortField == 'discount_type')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'discount_value', 'sort_direction' => $sortField == 'discount_value' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Giá trị giảm
                                @if($sortField == 'discount_value')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'min_order_value', 'sort_direction' => $sortField == 'min_order_value' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Giá trị tối thiểu áp dụng
                                @if($sortField == 'min_order_value')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'start_date', 'sort_direction' => $sortField == 'start_date' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Ngày bắt đầu
                                @if($sortField == 'start_date')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'end_date', 'sort_direction' => $sortField == 'end_date' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Ngày kết thúc
                                @if($sortField == 'end_date')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.promotion.index', ['sort_field' => 'is_active', 'sort_direction' => $sortField == 'is_active' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Trạng thái
                                @if($sortField == 'is_active')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($validPromotions as $promotion)
                    <tr>
                        <td class="text-center">{{ $promotion->promotion_id }}</td>
                        <td>{{ $promotion->name }}</td>
                        <td>{{ $promotion->discount_type }}</td>
                        <td class="text-end">{{ $promotion->discount_value }}</td>
                        <td class="text-end">{{ $promotion->min_order_value }}</td>
                        <td class="text-end">{{ $promotion->start_date->format('H:i:s d/m/Y') }}</td>
                        <td class="text-end">{{ $promotion->end_date->format('H:i:s d/m/Y') }}</td>
                        <td class="text-center">{{ $promotion->is_active ? 'Hoạt động' : 'Không hoạt động' }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('admin.promotion.edit', ['promotion_id' => $promotion->promotion_id]) }}"
                                    class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                                <form class="mx-1" method="POST" name=frmDelete
                                    action="{{ route('admin.promotion.delete') }}">
                                    @csrf
                                    <input type="hidden" name="promotion_id" value="{{ $promotion->promotion_id }}">

                                    <button type="submit" class="btn btn-danger btn-sm delete-promotion-btn"><i
                                            class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
    $('#promotionTabs a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });
});

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
});
</script>
@endsection