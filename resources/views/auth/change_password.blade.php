@php
$role = Auth::user()->role;
switch ($role) {
case 'admin':
$layout = 'admin.layouts.master';
break;
case 'staff_counter':
$layout = 'staff_counter.layouts.master';
break;
default:
$layout = 'staff_baristas.layouts.master';
break;
}
@endphp

@extends($layout)

@section('title')
Đổi mật khẩu
@endsection

@section('feature-title')
Đổi mật khẩu
@endsection

@section('custom-css')
<style>
body {
    background: url('{{ asset("images/login_bg.png") }}') no-repeat center center fixed;
    background-size: cover;
}

.change-password-form {
    background: rgba(255, 255, 255, 0.6);
    border-radius: 8px;
    padding: 20px;
    border: solid 1px #0049ab;
    box-shadow: 0 4px 10px #0049ab;
    transition: all 0.3s ease-in-out;
}

.change-password-form:hover {
    box-shadow: 0 4px 20px #053370;
}

.form-label {
    font-weight: bold;
}

.form-control {
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid #ccc;
}
</style>
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-12 col-md-4 change-password-form">
        <h3 class="text-center title2 mb-4">Đổi mật khẩu</h3>
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                <input type="password" class="form-control" id="current_password" name="current_password"
                    placeholder="Nhập mật khẩu hiện tại" required>
                @error('current_password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="new_password" class="form-label">Mật khẩu mới</label>
                <input type="password" class="form-control" id="new_password" name="new_password"
                    placeholder="Nhập mật khẩu mới" required>
                @error('new_password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                <input type="password" class="form-control" id="new_password_confirmation"
                    name="new_password_confirmation" placeholder="Xác thực mật khẩu mới" required>
                @error('new_password_confirmation')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-grid gap-2">
                <button type="submit" name="submit" class="btn btn-primary">Đổi mật khẩu</button>
            </div>
        </form>
    </div>
    @endsection