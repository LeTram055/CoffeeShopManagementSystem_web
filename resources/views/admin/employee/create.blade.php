@extends('admin.layouts.master')

@section('title')
Quản lý nhân viên
@endsection

@section('feature-title')
Quản lý nhân viên
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-6 border rounded-3 p-5 custom-shadow">
        <h3 class="text-center title2 mb-4">Thêm mới nhân viên</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.employee.save') }}">
            @csrf

            <div class="form-group mb-3">
                <label for="name" class="form-label fw-semibold">Tên nhân viên:</label>
                <input type="text" class="form-control rounded-2" id="name" name="name" placeholder="Nhập tên nhân viên"
                    value="{{ old('name') }}">
                @error('name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="username" class="form-label fw-semibold">Tên đăng nhập:</label>
                <input type="text" class="form-control rounded-2" id="username" name="username"
                    placeholder="Nhập tên đăng nhập" value="{{ old('username') }}">
                @error('username')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password" class="form-label fw-semibold">Mật khẩu:</label>
                <input type="password" class="form-control rounded-2" id="password" name="password"
                    placeholder="Nhập mật khẩu" value="">
                @error('password')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="role" class="form-label fw-semibold">Vai trò:</label>
                <select class="form-control rounded-2" id="role" name="role">
                    <option value="admin">Quản trị viên</option>
                    <option value="staff_serve">Nhân viên phục vụ</option>
                    <option value="staff_counter">Nhân viên quầy</option>
                    <option value="staff_barista">Nhân viên pha chế</option>
                </select>
                @error('role')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="phone_number" class="form-label fw-semibold">Số điện thoại:</label>
                <input type="text" class="form-control rounded-2" id="phone_number" name="phone_number"
                    placeholder="Nhập số điện thoại" value="{{ old('phone_number') }}">
                @error('phone_number')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="email" class="form-label fw-semibold">Email:</label>
                <input type="email" class="form-control rounded-2" id="email" name="email" placeholder="Nhập email"
                    value="{{ old('email') }}">
                @error('email')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="address" class="form-label fw-semibold">Địa chỉ:</label>
                <input type="text" class="form-control rounded-2" id="address" name="address" placeholder="Nhập địa chỉ"
                    value="{{ old('address') }}">
                @error('address')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="start_date" class="form-label fw-semibold">Ngày bắt đầu làm việc:</label>
                <input type="date" class="form-control rounded-2" id="start_date" name="start_date"
                    value="{{ old('start_date') }}">
                @error('start_date')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary fw-semibold">Lưu</button>
        </form>
    </div>
</div>
@endsection