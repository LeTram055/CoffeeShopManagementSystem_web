@extends('admin/layouts/master')

@section('title')
Quản lý quảng cáo
@endsection

@section('feature-title')
Quản lý quảng cáo
@endsection
@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-6 border rounded-3 p-5 custom-shadow mb-3">
        <h3 class="text-center title2 mb-4">Thêm mới quảng cáo</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.promotion.save') }}">
            @csrf

            <div class="form-group mb-3">
                <label for="name" class="form-label fw-semibold">Tên quảng cáo:</label>
                <input type="text" class="form-control rounded-2" id="name" name="name" placeholder="Nhập tên quảng cáo"
                    required>
                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="discount_type" class="form-label fw-semibold">Loại giảm giá:</label>
                <select class="form-control rounded-2" id="discount_type" name="discount_type" required>
                    <option value="percentage">Phần trăm</option>
                    <option value="fixed">Cố định</option>
                </select>
                @error('discount_type')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="discount_value" class="form-label fw-semibold">Giá trị giảm giá:</label>
                <input type="number" class="form-control rounded-2" id="discount_value" name="discount_value"
                    placeholder="Nhập giá trị giảm giá" required>
                @error('discount_value')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="min_order_value" class="form-label fw-semibold">Giá trị tối thiểu áp dụng khuyến
                    mãi:</label>
                <input type="number" class="form-control rounded-2" id="min_order_value" name="min_order_value"
                    placeholder="Nhập giá trị giảm giá" required>
                @error('min_order_value')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="start_date" class="form-label fw-semibold">Ngày bắt đầu:</label>
                <input type="datetime-local" class="form-control rounded-2" id="start_date" name="start_date" required>
                @error('start_date')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="end_date" class="form-label fw-semibold">Ngày kết thúc:</label>
                <input type="datetime-local" class="form-control rounded-2" id="end_date" name="end_date" required>
                @error('end_date')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label class="form-label fw-semibold">Trạng thái:</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                    <label class="form-check-label" for="is_active">Hoạt động</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary fw-semibold">Lưu</button>
            <a href="{{ route('admin.promotion.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection