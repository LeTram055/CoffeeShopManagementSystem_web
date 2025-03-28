@extends('admin.layouts.master')

@section('title')
Chỉnh sửa nhân viên
@endsection

@section('feature-title')
Chỉnh sửa nhân viên
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-6 border rounded-3 p-5 custom-shadow">
        <h3 class="text-center title2 mb-4">Cập nhật thông tin nhân viên</h3>
        <form name="frmEdit" id="frmEdit" method="post"
            action="{{ route('admin.employee.update', $employee->employee_id) }}">
            @csrf

            <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
            <div class="form-group mb-3">
                <label for="name" class="form-label fw-semibold">Tên nhân viên:</label>
                <input type="text" class="form-control rounded-2" id="name" name="name"
                    value="{{ old('name', $employee->name) }}">
                @error('name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="username" class="form-label fw-semibold">Tên đăng nhập:</label>
                <input type="text" class="form-control rounded-2" id="username" name="username"
                    value="{{ old('username', $employee->username) }}">
                @error('username')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="role" class="form-label fw-semibold">Vai trò:</label>
                <select class="form-control rounded-2" id="role" name="role">
                    <option value="admin" {{ old('role', $employee->role) == 'admin' ? 'selected' : '' }}>Quản trị viên
                    </option>
                    <option value="staff_serve" {{ old('role', $employee->role) == 'staff_serve' ? 'selected' : '' }}>
                        Nhân viên phục vụ</option>
                    <option value="staff_counter"
                        {{ old('role', $employee->role) == 'staff_counter' ? 'selected' : '' }}>
                        Nhân viên quầy</option>
                    <option value="staff_barista"
                        {{ old('role', $employee->role) == 'staff_barista' ? 'selected' : '' }}>
                        Nhân viên pha chế</option>
                </select>
                @error('role')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="email" class="form-label fw-semibold">Email:</label>
                <input type="email" class="form-control rounded-2" id="email" name="email"
                    value="{{ old('email', $employee->email) }}">
                @error('email')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="phone_number" class="form-label fw-semibold">Số điện thoại:</label>
                <input type="text" class="form-control rounded-2" id="phone_number" name="phone_number"
                    value="{{ old('phone_number', $employee->phone_number) }}">
                @error('phone_number')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="address" class="form-label fw-semibold">Địa chỉ:</label>
                <input type="text" class="form-control rounded-2" id="address" name="address"
                    value="{{ old('address', $employee->address) }}">
                @error('address')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="start_date" class="form-label fw-semibold">Ngày bắt đầu:</label>
                <input type="date" class="form-control rounded-2" id="start_date" name="start_date"
                    value="{{ old('start_date', $employee->start_date->format('Y-m-d')) }}">
                @error('start_date')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="hourly_rate" class="form-label fw-semibold">Lương theo giờ:</label>
                <input type="number" class="form-control rounded-2" id="hourly_rate" name="hourly_rate"
                    placeholder="Nhập lương theo giờ" value="{{ old('hourly_rate', $employee->hourly_rate) }}">
                @error('hourly_rate')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="status" class="form-label fw-semibold">Trạng thái:</label>
                <select class="form-control rounded-2" id="status" name="status">
                    <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Hoạt
                        động</option>
                    <option value="locked" {{ old('status', $employee->status) == 'locked' ? 'selected' : '' }}>Đã khóa
                    </option>
                </select>
                @error('status')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary fw-semibold">Lưu</button>
            <a href="{{ route('admin.employee.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection