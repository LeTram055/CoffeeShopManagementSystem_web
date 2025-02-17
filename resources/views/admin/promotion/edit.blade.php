@extends('admin.layouts.master')

@section('title')
Quản lý quảng cáo
@endsection

@section('feature-title')
Quản lý quảng cáo
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-6 border rounded-3 p-5 custom-shadow mb-3">
        <h3 class="text-center title2">Cập nhật quảng cáo</h3>
        <form name="frmEdit" id="frmEdit" method="post" action="{{ route('admin.promotion.update') }}">
            @csrf
            <input type="hidden" name="promotion_id" value="{{ $promotion->promotion_id }}">
            <div class="form-group mb-3">
                <label for="name" class="form-label fw-semibold">Tên quảng cáo:</label>
                <input type="text" class="form-control rounded-2" id="name" name="name"
                    value="{{ old('name', $promotion->name) }}" placeholder="Nhập tên quảng cáo">
                @error('name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="discount_type" class="form-label fw-semibold">Loại giảm giá:</label>
                <select class="form-control rounded-2" id="discount_type" name="discount_type">
                    <option value="percentage"
                        {{ old('discount_type', $promotion->discount_type) == 'percentage' ? 'selected' : '' }}>Phần
                        trăm</option>
                    <option value="fixed"
                        {{ old('discount_type', $promotion->discount_type) == 'fixed' ? 'selected' : '' }}>Cố định
                    </option>
                </select>
                @error('discount_type')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="discount_value" class="form-label fw-semibold">Giá trị giảm giá:</label>
                <input type="number" class="form-control rounded-2" id="discount_value" name="discount_value"
                    value="{{ old('discount_value', $promotion->discount_value) }}">
                @error('discount_value')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="min_order_value" class="form-label fw-semibold">Giá trị tối thiểu áp dụng khuyến
                    mãi:</label>
                <input type="number" class="form-control rounded-2" id="min_order_value" name="min_order_value"
                    value="{{ old('min_order_value', $promotion->min_order_value) }}">
                @error('min_order_value')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="start_date" class="form-label fw-semibold">Ngày bắt đầu:</label>
                <input type="datetime-local" class="form-control rounded-2" id="start_date" name="start_date"
                    value="{{ old('start_date', $promotion->start_date) }}">
                @error('start_date')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="end_date" class="form-label fw-semibold">Ngày kết thúc:</label>
                <input type="datetime-local" class="form-control rounded-2" id="end_date" name="end_date"
                    value="{{ old('end_date', $promotion->end_date) }}">
                @error('end_date')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                    {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Kích hoạt</label>
            </div>
            <button type="submit" class="btn btn-primary fw-semibold">Lưu</button>
        </form>
    </div>
</div>
@endsection