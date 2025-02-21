@extends('admin.layouts.master')

@section('custom-css')
<style>
.card {
    box-shadow: 0px 4px 10px #0049ab;
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: scale(1.01);
    box-shadow: 0px 6px 15px #0049ab;
}

#modalImage {
    max-width: 100%;
    height: auto;
    max-height: 300px;
    object-fit: contain;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
</style>

@endsection

@section('title')
Quản lý thực đơn
@endsection

@section('feature-title')
Quản lý thực đơn
@endsection

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </p>
    @endif
    @endforeach
</div>

<!-- Tìm kiếm sản phẩm -->
<form method="GET" action="{{ route('admin.menuitem.index') }}"
    class="row g-2 mb-3 justify-content-center align-items-center">
    <div class="col-md-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm sản phẩm..."
                value="{{ request('search') }}">
        </div>
    </div>
    <div class="col-md-3">
        <select class="form-select" name="available">
            <option value="">Tất cả</option>
            <option value="1" {{ request('available') === "1" ? 'selected' : '' }}>Còn hàng</option>
            <option value="0" {{ request('available') === "0" ? 'selected' : '' }}>Hết hàng</option>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-bg rounded " type="submit">Tìm kiếm</button>
    </div>
</form>



<!-- Nút thêm sản phẩm mới và xuất excel -->
<div class="d-flex justify-content-between mb-3">

    <a href="{{ route('admin.menuitem.create') }}" class="btn btn-primary">Thêm mới</a>
    <a href="{{ route('admin.menuitem.exportExcel') }}" class="btn btn-success">Xuất Excel</a>
</div>

<ul class="nav nav-tabs" id="categoryTabs">
    <li class="nav-item">
        <a class="nav-link text-dark active" data-bs-toggle="tab" href="#category-all">
            Tất cả
        </a>
    </li>
    @foreach ($categories as $index => $category)
    <li class="nav-item">
        <a class="nav-link text-dark" data-bs-toggle="tab" href="#category-{{ $category->category_id }}">
            {{ $category->name }}
        </a>
    </li>
    @endforeach
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="category-all">
        <div class="row">
            @foreach ($categories as $category)
            @foreach ($category->items as $item)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card mb-3">
                    <img src="{{ asset('storage/uploads/'. $item->image_url) }}" class="card-img-top"
                        alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text text-danger fw-bold">{{ number_format($item->price, 0, ',', '.') }} đ</p>
                        <p class="fw-bold {{ $item->is_available ? 'text-success' : 'text-danger' }}">
                            {{ $item->is_available ? 'Còn hàng' : 'Hết hàng' }}
                        </p>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-info btn-sm me-2 view-details" data-id="{{ $item->item_id }}"><i
                                    class="fa-solid fa-circle-info"></i></button>
                            <a href="{{ route('admin.menuitem.edit', ['item_id' => $item->item_id]) }}"
                                class="btn btn-warning btn-sm me-2"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form method="POST" action="{{ route('admin.menuitem.delete') }}"
                                class="d-inline delete-form">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                                <button type="button" class="btn btn-danger btn-sm delete-item-btn"><i
                                        class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endforeach
        </div>
    </div>

    @foreach ($categories as $index => $category)
    <div class="tab-pane fade" id="category-{{ $category->category_id }}">
        <div class="row">
            @foreach ($category->items as $item)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card mb-3">
                    <img src="{{ asset('storage/uploads/'. $item->image_url) }}" class="card-img-top"
                        alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text text-danger fw-bold">{{ number_format($item->price, 0, ',', '.') }} đ</p>
                        <p class="fw-bold {{ $item->is_available ? 'text-success' : 'text-danger' }}">
                            {{ $item->is_available ? 'Còn hàng' : 'Hết hàng' }}
                        </p>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-info btn-sm me-2 view-details" data-id="{{ $item->item_id }}"><i
                                    class="fa-solid fa-circle-info"></i></button>
                            <a href="{{ route('admin.menuitem.edit', ['item_id' => $item->item_id]) }}"
                                class="btn btn-warning btn-sm me-2"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form name="frmDelete" method="POST" action="{{ route('admin.menuitem.delete') }}"
                                class="d-inline delete-form">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                                <button type="button" class="btn btn-danger btn-sm delete-item-btn"><i
                                        class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

<!-- Modal xem chi tiết -->
<div class="modal fade" id="menuItemModal" tabindex="-1" aria-labelledby="menuItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuItemModalLabel">Chi tiết sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" class="img-fluid mb-3">
                <h5 id="modalName"></h5>
                <p class="text-danger fw-bold" id="modalPrice"></p>
                <p id="modalDescription"></p>
                <h6>Nguyên liệu:</h6>
                <ul id="modalIngredients"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xóa sản phẩm-->
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
    $('.view-details').on('click', function() {
        let itemId = $(this).data('id');

        $.ajax({
            url: '/admin/menuitem/' + itemId,
            type: 'GET',
            success: function(data) {
                $('#modalImage').attr('src', data.image_url);
                $('#modalName').text(data.name);
                $('#modalPrice').text(new Intl.NumberFormat().format(data.price) + ' đ');
                $('#modalDescription').text(data.description);

                let ingredientsList = '';
                data.ingredients.forEach(function(ing) {
                    ingredientsList +=
                        `<li>${ing.ingredient.name} - ${ing.quantity_per_unit} ${ing.ingredient.unit}</li>`;
                });
                $('#modalIngredients').html(ingredientsList);

                $('#menuItemModal').modal('show');
            }
        });
    });

    //Xóa sản phẩm

    $('.delete-item-btn').on('click', function(e) {
        e.preventDefault();

        formToSubmit = $(this).closest('form');
        const itemName = $(this).closest('.card').find('.card-title').text();

        if (itemName.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa sản phẩm "${itemName}" không?`);
        }

        $('#delete-confirm').modal('show'); // Hiển thị modal
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });
});
</script>
@endsection