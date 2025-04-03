@extends('admin/layouts/master')

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
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="btn-close"
            data-bs-dismiss="alert" aria-label="Close"></p>
    @endif
    @endforeach
</div>
<div class="row justify-content-center align-items-center">
    <div class="col-12 col-md-6 border rounded-3 p-5 custom-shadow">
        <h3 class="text-center title2">Cập nhật nguyên liệu</h3>
        <form name="frmEdit" id="frmEdit" method="post" action="{{ route('admin.ingredient.update') }}">
            @csrf
            <input type="hidden" name="ingredient_id" value="{{ $ingredient->ingredient_id }}">
            <div class="form-group mb-3">
                <label for="name" class="form-label fw-semibold">Tên nguyên liệu:</label>
                <input type="text" class="form-control rounded-2" id="name" name="name"
                    value="{{ old('name', $ingredient->name) }}" placeholder="Nhập tên nguyên liệu">
                @error('name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="row g-2 mb-3">
                <div class="col-md-6 col-12">
                    <label for="current_quantity" class="form-label fw-semibold">Số lượng hiện tại:</label>
                    <input type="number" step="0.01" class="form-control rounded-2" id="current_quantity"
                        name="current_quantity" value="{{ $ingredient->quantity }}" readonly>
                </div>

                <!-- <div class="col-md-6 col-12">
                    <label for="cost_price" class="form-label fw-semibold">Giá vốn hiện tại:</label>
                    <input type="number" step="0.01" class="form-control rounded-2" id="cost_price" name="cost_price"
                        value="{{ number_format($ingredient->cost_price, 0, ',', '.') }}" readonly>

                </div> -->

                <div class="col-md-6 col-12">
                    <label for="cost_price" class="form-label fw-semibold">Giá vốn hiện tại:</label>
                    <input type="number" step="0.01" class="form-control rounded-2" id="cost_price" name="cost_price"
                        value="{{ $ingredient->cost_price }}"
                        {{ old('log_type', $ingredient->log_type ?? 'adjustment') !== 'adjustment' ? 'readonly' : '' }}>
                    @error('cost_price')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="unit" class="form-label fw-semibold">Đơn vị:</label>
                <input type="text" class="form-control rounded-2" id="unit" name="unit"
                    value="{{ old('unit', $ingredient->unit) }}">
                @error('unit')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>



            <div class="row g-2 mb-3">

                <div class="col-md-6 col-12">
                    <label for="log_type" class="form-label fw-semibold">Loại cập nhật:</label>
                    <select name="log_type" id="log_type" class="form-control">
                        <option value="adjustment">Điều chỉnh</option>
                        <option value="import">Nhập hàng</option>
                        <option value="export">Xuất kho</option>
                    </select>
                    @error('log_type')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6 col-12" id="change_value_input">
                    <label for="change_value" class="form-label fw-semibold">Số lượng thay đổi:</label>
                    <input type="number" step="0.01" class="form-control" id="change_value" name="change_value"
                        placeholder="Nhập số lượng thay đổi" value="{{ old('change_value') }}">
                    @error('change_value')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group mb-3" id="change_type" style="display: none;">
                <label for="change_type" class="form-label fw-semibold">Loại điều chỉnh:</label>
                <select class="form-select" id="change_type" name="change_type">
                    <option value="increase" {{ old('change_type')=='increase' ? 'selected' : '' }}>Tăng</option>
                    <option value="decrease" {{ old('change_type')=='decrease' ? 'selected' : '' }}>Giảm</option>
                </select>
            </div>

            <div class="form-group mb-3" id="price_input" style="display: none;">
                <label for="price" class="form-label fw-semibold">Giá nhập:</label>
                <input type="number" step="0.01" class="form-control rounded-2" id="price" name="price"
                    value="{{ old('price') }}" placeholder="Nhập giá nhập hàng">
                @error('price')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>


            <div class="form-group mb-3">
                <label for="min_quantity" class="form-label fw-semibold">Số lượng tối thiểu:</label>
                <input type="number" step="0.01" class="form-control rounded-2" id="min_quantity" name="min_quantity"
                    value="{{ old('min_quantity',$ingredient->min_quantity) }}">
                @error('min_quantity')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="reason" class="form-label fw-semibold">Lý do cập nhật:</label>
                <textarea class="form-control rounded-2" id="reason" name="reason"
                    placeholder="Nhập lý do (nếu có)"></textarea>
            </div>

            <button type="submit" name="submit" class="btn btn-primary fw-semibold">Lưu</button>
            <a href="{{ route('admin.ingredient.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
$(document).ready(function() {
    function toggleFields() {
        var logType = $('#log_type').val();
        var changeValue = $('#change_value').val();
        console.log("chang_value = ", changeValue);

        if (logType === "import") {
            $('#price_input').show();
            $('#change_type').hide();
            $('#cost_price').prop('readonly', true);
        } else if (logType === "adjustment") {
            $('#price_input').hide();
            $('#cost_price').prop('readonly', false);

            if (changeValue !== "" && !isNaN(changeValue) && parseFloat(changeValue) !== 0) {
                $('#change_type').show();
            } else {
                $('#change_type').hide();
            }
        } else {
            $('#price_input').hide();
            $('#change_type').hide();
            $('#cost_price').prop('readonly', true);
        }
    }

    // Gọi khi trang load
    toggleFields();

    // Khi thay đổi loại cập nhật
    $('#log_type').change(function() {
        toggleFields();
    });

    // Khi thay đổi số lượng
    $('#change_value').on('input', function() {
        toggleFields();
    });
});
</script>
@endsection