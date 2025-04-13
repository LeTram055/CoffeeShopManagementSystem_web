@extends('admin.layouts.master')

@section('custom-css')
<style>
.card {
    box-shadow: 0px 4px 8px rgba(0, 73, 171, 0.5);
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: scale(1.01);
    box-shadow: 0px 6px 12px rgba(0, 73, 171, 0.7);
}

.card-img-container {
    position: relative;
    height: 160px;

}

.card-img-top {
    height: 100%;
    object-fit: cover;
}

.stock-status {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(249, 246, 246, 0.62);
}

.stock-status i {
    font-size: 18px;
}

.card-body {
    padding: 5px;
    text-align: center;
}

.card-title {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;
}

.card-text {
    font-size: 14px;
    font-weight: bold;
    color: red;
    margin-bottom: 5px;
}

.action-buttons {
    display: flex;
    justify-content: center;
    gap: 5px;
}

#modalStatus {
    font-size: 14px;
    border-radius: 20px;
    padding: 8px 12px;
    top: 15px;
    right: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
        <a href="{{ route('admin.menuitem.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
        <a href="{{ route('admin.menuitem.exportExcel') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
        <a href="{{ route('admin.menuitem.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-sync-alt"></i> Làm mới
        </a>
    </div>
    <form method="GET" action="{{ route('admin.menuitem.index') }}" class="d-flex gap-2" style="max-width: 60%;">
        <!-- Bộ lọc -->
        <select class="form-select w-auto" name="available">
            <option value="">Tất cả</option>
            <option value="1" {{ request('available') === "1" ? 'selected' : '' }}>Còn hàng</option>
            <option value="0" {{ request('available') === "0" ? 'selected' : '' }}>Hết hàng</option>
        </select>

        <!-- Tìm kiếm -->
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded-start" placeholder="Tìm kiếm sản phẩm..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded-end" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>

<ul class="nav nav-tabs custom-tabs" id="categoryTabs">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#category-all">
            Tất cả
        </a>
    </li>
    @foreach ($categories as $index => $category)
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#category-{{ $category->category_id }}">
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
                    <div class="card-img-container">
                        <img src="{{ asset('storage/uploads/'. $item->image_url) }}" class="card-img-top"
                            alt="{{ $item->name }}">
                        <div class="stock-status">
                            <i
                                class="fa-solid {{ $item->is_available ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }}"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">{{ number_format($item->price, 0, ',', '.') }} đ</p>
                        <div class="action-buttons">
                            <button class="btn btn-link text-info view-details" data-id="{{ $item->item_id }}">
                                <i class="fa-solid fa-circle-info"></i>
                            </button>
                            <a href="{{ route('admin.menuitem.edit', ['item_id' => $item->item_id]) }}"
                                class="btn btn-link text-warning">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.menuitem.delete') }}"
                                class="d-inline delete-form">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                                <button type="button" class="btn btn-link text-danger delete-item-btn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
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
                    <div class="card-img-container">
                        <img src="{{ asset('storage/uploads/'. $item->image_url) }}" class="card-img-top"
                            alt="{{ $item->name }}">
                        <div class="stock-status">
                            <i
                                class="fa-solid {{ $item->is_available ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }}"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">{{ number_format($item->price, 0, ',', '.') }} đ</p>
                        <div class="action-buttons">
                            <button class="btn btn-link text-info view-details" data-id="{{ $item->item_id }}">
                                <i class="fa-solid fa-circle-info"></i>
                            </button>
                            <a href="{{ route('admin.menuitem.edit', ['item_id' => $item->item_id]) }}"
                                class="btn btn-link text-warning">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.menuitem.delete') }}"
                                class="d-inline delete-form">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                                <button type="button" class="btn btn-link text-danger delete-item-btn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
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

                <h5 class="modal-title d-flex align-items-center">
                    Chi tiết sản phẩm
                    <span id="modalStatus" class="badge ms-2"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <img id="modalImage" src="" class="img-fluid mb-3">
                <h5 id="modalName"></h5>
                <p class="text-danger fw-bold" id="modalPrice"></p>
                <!-- <p id="modalAvailable" class="fw-bold"></p> -->

                <p id="modalDescription"></p>
                <p id="modalReason" style="display: none;"></p>
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
                // Xử lý trạng thái Còn hàng / Hết hàng
                let availableText = data.is_available ? 'Còn hàng' : 'Hết hàng';
                let availableClass = data.is_available ? 'bg-success' : 'bg-danger';

                $('#modalStatus')
                    .text(availableText)
                    .removeClass('bg-success bg-danger')
                    .addClass(availableClass);

                $('#modalImage').attr('src', data.image_url);
                $('#modalName').text(data.name);
                $('#modalPrice').text(new Intl.NumberFormat().format(data.price) + ' đ');
                $('#modalDescription').text(data.description);

                // Hiển thị lý do nếu sản phẩm không có sẵn
                if (!data.is_available && data.reason) {
                    $('#modalReason').html(`<strong>Lý do:</strong> ${data.reason}`).show();
                } else {
                    $('#modalReason').hide();
                }

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

    // Tự động đóng thông báo sau 5 giây
    setTimeout(function() {
        $('.flash-message .alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection