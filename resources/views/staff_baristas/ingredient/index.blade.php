@extends('staff_baristas.layouts.master')

@section('custom-css')
<style>
.container-search {
    display: flex;
    justify-content: center;
    margin-bottom: 15px;
}

#searchInput {
    width: 50%;
}

.modal-header {
    background-color: #007bff;
    color: white;
}

.table th {
    vertical-align: top;
}

.table thead th a {
    color: inherit;
    text-decoration: none;
    font-weight: inherit;
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
</style>
@endsection

@section('title')
Nguyên liệu
@endsection

@section('feature-title')
Nguyên liệu
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

            </div>

            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>

<div class="container-search mt-3">
    <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm nguyên liệu...">
</div>

<ul class="nav nav-tabs custom-tabs m-3" id="ingredientTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button"
            role="tab">Tất cả</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="low-stock-tab" data-bs-toggle="tab" data-bs-target="#low-stock" type="button"
            role="tab">Trong kho thấp</button>
    </li>
</ul>

<div class="tab-content m-3">
    <!-- Tab Tất cả -->
    <div class="tab-pane fade show active" id="all" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">Mã nguyên liệu</th>
                        <th class="text-center">Tên nguyên liệu</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-center">Đơn vị</th>
                        <th class="text-center">Số lượng tối thiểu</th>
                        <th class="text-center">Cập nhật lần cuối</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ingredients as $ingredient)
                    <tr>
                        <td class="text-center">{{ $ingredient->ingredient_id }}</td>
                        <td class="ingredient-name">{{ $ingredient->name }}</td>
                        <td class="text-center">{{ $ingredient->quantity }}</td>
                        <td class="text-center">{{ $ingredient->unit }}</td>
                        <td class="text-center">{{ $ingredient->min_quantity }}</td>
                        <td class="text-center">
                            {{ $ingredient->last_updated ? $ingredient->last_updated->format('H:i:s d/m/Y') : 'N/A' }}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-link text-warning mx-2 btn-update"
                                data-bs-toggle="modal" data-bs-target="#updateModal"
                                data-id="{{ $ingredient->ingredient_id }}" data-name="{{ $ingredient->name }}"
                                data-unit="{{ $ingredient->unit }}" data-min-quantity="{{ $ingredient->min_quantity }}"
                                data-quantity="{{ $ingredient->quantity }}">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> <!-- Đóng tab Tất cả -->

    <!-- Tab Trong kho thấp -->
    <div class="tab-pane fade" id="low-stock" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">Mã nguyên liệu</th>
                        <th class="text-center">Tên nguyên liệu</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-center">Đơn vị</th>
                        <th class="text-center">Số lượng tối thiểu</th>
                        <th class="text-center">Cập nhật lần cuối</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ingredients as $ingredient)
                    @if ($ingredient->quantity <= $ingredient->min_quantity)
                        <tr>
                            <td class="text-center">{{ $ingredient->ingredient_id }}</td>
                            <td class="ingredient-name">{{ $ingredient->name }}</td>
                            <td class="text-center text-danger fw-bold">{{ $ingredient->quantity }}</td>
                            <td class="text-center">{{ $ingredient->unit }}</td>
                            <td class="text-center">{{ $ingredient->min_quantity }}</td>
                            <td class="text-center">
                                {{ $ingredient->last_updated ? $ingredient->last_updated->format('H:i:s d/m/Y') : 'N/A' }}
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-link text-warning mx-2 btn-update"
                                    data-bs-toggle="modal" data-bs-target="#updateModal"
                                    data-id="{{ $ingredient->ingredient_id }}" data-name="{{ $ingredient->name }}"
                                    data-unit="{{ $ingredient->unit }}"
                                    data-min-quantity="{{ $ingredient->min_quantity }}"
                                    data-quantity="{{ $ingredient->quantity }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                </tbody>
            </table>
        </div>
    </div> <!-- Đóng tab Trong kho thấp -->
</div>


<!-- Modal Cập Nhật Nguyên Liệu -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật nguyên liệu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm" method="POST">
                    @csrf
                    <input type="hidden" name="ingredient_id" id="ingredientId">

                    <div class="form-group mb-3">
                        <label for="ingredientName" class="form-label">Tên nguyên liệu:</label>
                        <input type="text" id="ingredientName" class="form-control" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="ingredientUnit" class="form-label">Đơn vị:</label>
                        <input type="text" id="ingredientUnit" class="form-control" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="ingredientMinQuantity" class="form-label">Số lượng tối thiểu:</label>
                        <input type="number" id="ingredientMinQuantity" class="form-control" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="current_quantity" class="form-label fw-semibold">Số lượng hiện tại:</label>
                        <input type="number" step="0.01" class="form-control rounded-2" id="current_quantity"
                            name="current_quantity" readonly>
                    </div>

                    <!-- <div class="form-group mb-3">
                        <label for="change_type" class="form-label">Loại cập nhật:</label>
                        <select class="form-select" id="change_type" name="change_type">
                            <option value="increase" {{ old('change_type')=='increase' ? 'selected' : '' }}>Tăng
                            </option>
                            <option value="decrease" {{ old('change_type')=='decrease' ? 'selected' : '' }}>Giảm
                            </option>
                        </select>
                    </div> -->

                    <div class="form-group mb-3">
                        <label for="change_value" class="form-label">Số lượng thay đổi:</label>
                        <input type="number" step="0.01" class="form-control" id="change_value" name="change_value"
                            placeholder="Nhập số lượng thay đổi">
                        @error('change_value')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="reason" class="form-label">Lý do cập nhật:</label>
                        <textarea class="form-control" id="reason" name="reason"
                            placeholder="Nhập lý do thay đổi"></textarea>
                        @error('reason')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
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
    $('.btn-update').click(function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let unit = $(this).data('unit');
        let min_quantity = $(this).data('min-quantity');
        let quantity = $(this).data('quantity');

        $('#ingredientId').val(id);
        $('#ingredientName').val(name);
        $('#ingredientUnit').val(unit);
        $('#ingredientMinQuantity').val(min_quantity);
        $('#current_quantity').val(quantity);

        $('#updateForm').attr('action', '/staff_baristas/ingredient/update/' + id);
        $('#updateModal').modal('show'); // Mở modal
    });

    $('#updateForm').submit(function(e) {
        let isValid = true;
        let changeValue = $('#change_value').val().trim();
        let reason = $('#reason').val().trim();


        $('.text-danger').remove();

        if (!changeValue || isNaN(changeValue) || parseFloat(changeValue) <= 0) {
            isValid = false;
            $('#change_value').after(
                '<small class="text-danger">Vui lòng nhập số lượng hợp lệ</small>');
        }

        if (!reason) {
            isValid = false;
            $('#reason').after('<small class="text-danger">Vui lòng nhập lý do cập nhật</small>');
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    $('#searchInput').on('keyup', function() {
        let value = $(this).val().toLowerCase();
        $('#ingredientTable tbody tr').filter(function() {
            $(this).toggle($(this).find('.ingredient-name').text().toLowerCase().indexOf(
                value) > -1);
        });
    });

    setTimeout(function() {
        $(".flash-message .alert").fadeOut("slow", function() {
            $(this).remove();
        });
    }, 5000);
});
</script>
@endsection