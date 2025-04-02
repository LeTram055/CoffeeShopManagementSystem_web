@extends('admin/layouts/master')

@section('title')
Quản lý nguyên liệu
@endsection

@section('feature-title')
Quản lý nguyên liệu
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-12 col-md-6 border rounded-3 p-5 custom-shadow">
        <h3 class="text-center title2 mb-4">Thêm mới nguyên liệu</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.ingredient.save') }}">
            @csrf

            <div class="form-group mb-3">
                <label for="name" class="form-label fw-semibold">Tên nguyên liệu:</label>
                <input type="text" class="form-control rounded-2" id="name" name="name" value="{{ old('name') }}"
                    placeholder="Nhập tên nguyên liệu">
                @error('name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="quantity" class="form-label fw-semibold">Số lượng:</label>
                <input type="number" step="0.01" class="form-control rounded-2" id="quantity" name="quantity"
                    value="{{ old('quantity') }}" placeholder="Nhập số lượng">
                @error('quantity')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="unit" class="form-label fw-semibold">Đơn vị:</label>
                <input type="text" class="form-control rounded-2" id="unit" name="unit" value="{{ old('unit') }}"
                    placeholder="Nhập đơn vị (vd: kg, lít, g)">
                @error('unit')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="cost_price" class="form-label fw-semibold">Số lượng tối thiểu:</label>
                <input type="number" step="0.01" class="form-control rounded-2" id="cost_price" name="cost_price"
                    value="{{ old('cost_price') }}" placeholder="Nhập số lượng tối thiểu">
                @error('cost_price')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="min_quantity" class="form-label fw-semibold">Giá/Đơn vị:</label>
                <input type="number" step="0.01" class="form-control rounded-2" id="min_quantity" name="min_quantity"
                    value="{{ old('min_quantity') }}" placeholder="Nhập số lượng tối thiểu">
                @error('min_quantity')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary fw-semibold">Lưu</button>
            <a href="{{ route('admin.ingredient.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection