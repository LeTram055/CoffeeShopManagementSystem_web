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

<div class="container-search mt-3">
    <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm nguyên liệu...">
</div>

<div class="table-responsive m-3">
    <table class="table table-striped table-hover" id="ingredientTable">
        <thead>
            <tr>
                <th class="text-center">
                    <a
                        href="{{ route('staff_baristas.ingredient.index', ['sort_field' => 'ingredient_id', 'sort_direction' => $sortField == 'ingredient_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã nguyên liệu
                        @if($sortField == 'ingredient_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('staff_baristas.ingredient.index', ['sort_field' => 'name', 'sort_direction' => $sortField == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tên nguyên liệu
                        @if($sortField == 'name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('staff_baristas.ingredient.index', ['sort_field' => 'quantity', 'sort_direction' => $sortField == 'quantity' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Số lượng
                        @if($sortField == 'quantity')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('staff_baristas.ingredient.index', ['sort_field' => 'unit', 'sort_direction' => $sortField == 'unit' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Đơn vị
                        @if($sortField == 'unit')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('staff_baristas.ingredient.index', ['sort_field' => 'min_quantity', 'sort_direction' => $sortField == 'min_quantity' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Số lượng tối thiểu
                        @if($sortField == 'min_quantity')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('staff_baristas.ingredient.index', ['sort_field' => 'last_updated', 'sort_direction' => $sortField == 'last_updated' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
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
                <td class="ingredient-name">{{ $ingredient->name }}</td>
                <td class="text-center">{{ $ingredient->quantity }}</td>
                <td class="text-center">{{ $ingredient->unit }}</td>
                <td class="text-center">{{ $ingredient->min_quantity }}</td>
                <td class="text-center">
                    {{ $ingredient->last_updated ? $ingredient->last_updated->format('H:i:s d/m/Y') : 'N/A' }}</td>

                <td class="text-center">
                    <button type="button" class="btn btn-link text-warning mx-2 btn-update" data-bs-toggle="modal"
                        data-bs-target="#updateModal" data-id="{{ $ingredient->ingredient_id }}"
                        data-name="{{ $ingredient->name }}" data-unit="{{ $ingredient->unit }}"
                        data-min-quantity="{{ $ingredient->min_quantity }}" data-quantity="{{ $ingredient->quantity }}">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>

            </tr>
            @endforeach

        </tbody>
    </table>
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

                    <div class="form-group mb-3">
                        <label for="change_type" class="form-label">Loại cập nhật:</label>
                        <select class="form-select" id="change_type" name="change_type">
                            <option value="increase" {{ old('change_type')=='increase' ? 'selected' : '' }}>Tăng
                            </option>
                            <option value="decrease" {{ old('change_type')=='decrease' ? 'selected' : '' }}>Giảm
                            </option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="change_value" class="form-label">Số lượng thay đổi:</label>
                        <input type="number" step="0.01" class="form-control" id="change_value" name="change_value">
                        @error('change_value')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="reason" class="form-label">Lý do cập nhật:</label>
                        <textarea class="form-control" id="reason" name="reason"></textarea>
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