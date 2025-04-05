@extends('admin/layouts/master')

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
Quản lý nguyên liệu
@endsection

@section('feature-title')
Quản lý nguyên liệu
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

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="orderToast" class="toast align-items-center text-white bg-success border-0" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <strong id="toastOrderId"></strong> vừa được tạo!
            </div>

            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div class="d-flex gap-2">
        <a href="{{ route('admin.ingredient.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
        <a href="{{ route('admin.ingredient.exportExcel') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
        <a href="{{ route('admin.ingredient.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-sync-alt"></i> Làm mới
        </a>
    </div>
    <form method="GET" action="{{ route('admin.ingredient.index') }}" class="d-flex" style="max-width: 50%;">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm danh mục..."
                value="{{ request('search') }}">
            <input type="hidden" name="tab" value="{{ request('tab', 'all-ingredients') }}">
            <button class="btn btn-bg" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>

<ul class="nav nav-tabs custom-tabs" id="ingredientTabs">
    <li class="nav-item">
        <a class="nav-link {{ request('tab') == 'all-ingredients' || request('tab') == null ? 'active' : '' }}"
            href="{{ route('admin.ingredient.index', ['tab' => 'all-ingredients', 'search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection]) }}">
            Tất cả
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('tab') == 'low-stock' ? 'active' : '' }}"
            href="{{ route('admin.ingredient.index', ['tab' => 'low-stock', 'search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection]) }}">
            Hợp lệ
        </a>
    </li>
</ul>
<div class="tab-content">
    <!-- Tab Tất cả -->
    <div class="tab-pane fade  {{ $activeTab == 'all-ingredients' ? 'show active' : '' }}" id="all" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'ingredient_id', 'sort_direction' => $sortField == 'ingredient_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Mã nguyên liệu
                                @if($sortField == 'ingredient_id')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'name', 'sort_direction' => $sortField == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Tên nguyên liệu
                                @if($sortField == 'name')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'quantity', 'sort_direction' => $sortField == 'quantity' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Số lượng
                                @if($sortField == 'quantity')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'unit', 'sort_direction' => $sortField == 'unit' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Đơn vị
                                @if($sortField == 'unit')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'cost_price', 'sort_direction' => $sortField == 'cost_price' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Giá
                                @if($sortField == 'cost_price')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'min_quantity', 'sort_direction' => $sortField == 'min_quantity' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Số lượng tối thiểu
                                @if($sortField == 'min_quantity')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'last_updated', 'sort_direction' => $sortField == 'last_updated' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Cập nhật lần cuối
                                @if($sortField == 'last_updated')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>

                <tbody class="table-group-divider">
                    @foreach ($ingredients as $ingredient)
                    <tr>
                        <td class="text-center">{{ $ingredient->ingredient_id }}</td>
                        <td>{{ $ingredient->name }}</td>
                        <td class="text-center">
                            {{ number_format($ingredient->quantity, 2, ',', '.') }}</td>
                        <td class="text-center">{{ $ingredient->unit }}</td>
                        <td class="text-center">{{ number_format($ingredient->cost_price, 0, ',', '.') }} VNĐ</td>
                        <td class="text-center">{{ number_format($ingredient->min_quantity, 2, ',', '.') }}</td>
                        <td class="text-center">
                            {{ $ingredient->last_updated ? $ingredient->last_updated->format('H:i:s d/m/Y') : 'N/A' }}
                        </td>

                        <td class="text-center">
                            <a href="{{ route('admin.ingredient.edit', ['ingredient_id' => $ingredient->ingredient_id]) }}"
                                class="text-warning mx-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form class="d-inline delete-form" method="post"
                                action="{{ route('admin.ingredient.delete') }}">
                                @csrf
                                <input type="hidden" name="ingredient_id" value="{{ $ingredient->ingredient_id }}">
                                <button type="submit"
                                    class="btn btn-link text-danger p-0 border-0 delete-ingredient-btn">
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

    <!-- Tab Nguyên liệu trong kho thấp -->
    <div class="tab-pane fade  {{ $activeTab == 'low-stock' ? 'show active' : '' }}" id="low-stock" role="tabpanel">

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'ingredient_id', 'sort_direction' => $sortField == 'ingredient_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Mã nguyên liệu
                                @if($sortField == 'ingredient_id')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'name', 'sort_direction' => $sortField == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Tên nguyên liệu
                                @if($sortField == 'name')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'quantity', 'sort_direction' => $sortField == 'quantity' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Số lượng
                                @if($sortField == 'quantity')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'unit', 'sort_direction' => $sortField == 'unit' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Đơn vị
                                @if($sortField == 'unit')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'cost_price', 'sort_direction' => $sortField == 'cost_price' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Giá
                                @if($sortField == 'cost_price')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'min_quantity', 'sort_direction' => $sortField == 'min_quantity' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Số lượng tối thiểu
                                @if($sortField == 'min_quantity')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a
                                href="{{ route('admin.ingredient.index', ['sort_field' => 'last_updated', 'sort_direction' => $sortField == 'last_updated' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'tab' => request('tab')]) }}">
                                Cập nhật lần cuối
                                @if($sortField == 'last_updated')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>

                <tbody class="table-group-divider">
                    @foreach ($ingredients as $ingredient)
                    @if ($ingredient->quantity <= $ingredient->min_quantity)
                        <tr>
                            <td class="text-center">{{ $ingredient->ingredient_id }}</td>
                            <td>{{ $ingredient->name }}</td>
                            <td class="text-center text-danger fw-bold">
                                {{ number_format($ingredient->quantity, 2, ',', '.') }}</td>
                            <td class="text-center">{{ $ingredient->unit }}</td>
                            <td class="text-center">{{ number_format($ingredient->cost_price, 0, ',', '.') }} VNĐ</td>
                            <td class="text-center">{{ number_format($ingredient->min_quantity, 2, ',', '.') }}</td>
                            <td class="text-center">
                                {{ $ingredient->last_updated ? $ingredient->last_updated->format('H:i:s d/m/Y') : 'N/A' }}
                            </td>

                            <td class="text-center">
                                <a href="{{ route('admin.ingredient.edit', ['ingredient_id' => $ingredient->ingredient_id]) }}"
                                    class="text-warning mx-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form class="d-inline delete-form" method="post"
                                    action="{{ route('admin.ingredient.delete') }}">
                                    @csrf
                                    <input type="hidden" name="ingredient_id" value="{{ $ingredient->ingredient_id }}">
                                    <button type="submit"
                                        class="btn btn-link text-danger p-0 border-0 delete-ingredient-btn">
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
<script src="https://cdn.socket.io/4.0.1/socket.io.min.js"></script>
<script>
$(document).ready(function() {
    let formToSubmit;

    $('.delete-ingredient-btn').on('click', function(e) {
        e.preventDefault();
        formToSubmit = $(this).closest('form');
        const ingredientName = $(this).closest('tr').find('td').eq(1).text();

        if (ingredientName.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa nguyên liệu "${ingredientName}" không?`);
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


$(document).ready(function() {
    const socket = io("http://localhost:3000");

    socket.on("connect", () => {
        console.log("Connected to WebSocket server");
    });

    socket.on("lowstock.event", (ingredient) => {

        showToast(
            `Nguyên liệu "${ingredient.data.name}" chỉ còn ${ingredient.data.quantity}, dưới mức tối thiểu (${ingredient.data.min_quantity})!`,
            "bg-danger"
        );
    });

    // Hàm hiển thị Toast
    function showToast(message, bgClass) {
        let toastHtml = `
            <div class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;

        $('.toast-container').append(toastHtml);
        let newToast = $('.toast-container .toast').last();
        let toast = new bootstrap.Toast(newToast[0], {
            autohide: false
        });
        toast.show();
    }


});
</script>
@endsection