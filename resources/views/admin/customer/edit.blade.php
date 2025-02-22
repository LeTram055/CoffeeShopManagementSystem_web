@extends('admin.layouts.master')

@section('title')
Quản lý khách hàng
@endsection

@section('feature-title')
Quản lý khách hàng
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-6 border rounded-3 p-5 custom-shadow">
        <h3 class="text-center title2">Cập nhật thông tin khách hàng</h3>
        <form name="frmEdit" id="frmEdit" method="post"
            action="{{ route('admin.customer.update', $customer->customer_id) }}">
            @csrf


            <input type="hidden" name="customer_id" value="{{ old('name', $customer->customer_id) }}">

            <div class="form-group mb-3">
                <label for="name" class="form-label fw-semibold">Tên khách hàng:</label>
                <input type="text" class="form-control rounded-2" id="name" name="name" value="{{ $customer->name }}"
                    placeholder="Nhập tên khách hàng">
                @error('name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="phone_number" class="form-label fw-semibold">Số điện thoại:</label>
                <input type="text" class="form-control rounded-2" id="phone_number" name="phone_number"
                    value="{{ old('phone_number',$customer->phone_number) }}" placeholder="Nhập số điện thoại">
                @error('phone_number')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="notes" class="form-label fw-semibold">Ghi chú:</label>
                <textarea class="form-control rounded-2" id="notes" name="notes" rows="3"
                    placeholder="Nhập ghi chú">{{ old('notes', $customer->notes) }}</textarea>
                @error('notes')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary fw-semibold">Lưu</button>
            <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection