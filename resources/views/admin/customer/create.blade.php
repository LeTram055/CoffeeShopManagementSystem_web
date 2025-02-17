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
        <h3 class="text-center title2 mb-4">Thêm mới khách hàng</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.customer.save') }}">
            @csrf

            <div class="form-group mb-3">
                <label for="name" class="form-label fw-semibold">Tên khách hàng:</label>
                <input type="text" class="form-control rounded-2" id="name" name="name"
                    placeholder="Nhập tên khách hàng" value="">
                @error('name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="phone_number" class="form-label fw-semibold">Số điện thoại:</label>
                <input type="text" class="form-control rounded-2" id="phone_number" name="phone_number"
                    placeholder="Nhập số điện thoại" value="">
                @error('phone_number')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="notes" class="form-label fw-semibold">Ghi chú:</label>
                <textarea class="form-control rounded-2" id="notes" name="notes" placeholder="Nhập ghi chú"></textarea>
                @error('notes')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary fw-semibold">Lưu</button>
        </form>
    </div>
</div>
@endsection