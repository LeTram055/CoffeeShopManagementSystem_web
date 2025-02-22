@extends('admin/layouts/master')

@section('title')
Quản lý nguyên liệu
@endsection

@section('feature-title')
Cập nhật nguyên liệu
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-6 border rounded-3 p-5 custom-shadow">
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
            <div class="form-group mb-3">
                <label for="current_quantity" class="form-label fw-semibold">Số lượng hiện tại:</label>
                <input type="number" step="0.01" class="form-control rounded-2" id="current_quantity"
                    name="current_quantity" value="{{ $ingredient->quantity }}" readonly>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-md-6 col-12">
                    <label for="change_type" class="form-label fw-semibold">Loại cập nhật:</label>
                    <select class="form-select" id="change_type" name="change_type">
                        <option value="increase" {{ old('change_type')=='increase' ? 'selected' : '' }}>Tăng</option>
                        <option value="decrease" {{ old('change_type')=='decrease' ? 'selected' : '' }}>Giảm</option>
                    </select>
                </div>
                <div class="col-md-6 col-12">
                    <label for="change_value" class="form-label fw-semibold">Số lượng thay đổi:</label>
                    <input type="number" step="0.01" class="form-control" id="change_value" name="change_value"
                        placeholder="Nhập số lượng thay đổi" value="{{ old('change_value') }}">
                </div>
            </div>
            <!-- <div class="form-group mb-3">
                <label for="quantity" class="form-label fw-semibold">Số lượng:</label>
                <input type="number" step="0.01" class="form-control rounded-2" id="quantity" name="quantity"
                    value="{{ old('quantity', $ingredient->quantity) }}">
                @error('quantity')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div> -->
            <div class="form-group mb-3">
                <label for="unit" class="form-label fw-semibold">Đơn vị:</label>
                <input type="text" class="form-control rounded-2" id="unit" name="unit"
                    value="{{ old('unit', $ingredient->unit) }}">
                @error('unit')
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