@extends('admin.layouts.master')

@section('title', 'Thông tin quán')

@section('feature-title', 'Thông tin quán')

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }} position-relative">
        {{ Session::get('alert-' . $msg) }}
        <button type="button" class="btn-close position-absolute end-0 me-2" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </p>
    @endif
    @endforeach
</div>
<div class="row justify-content-center align-items-center">
    <div class="col-6 border rounded-3 p-5 custom-shadow">
        <h3 class="text-center title2 mb-4">Cập nhật thông tin quán</h3>
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <div class="form-group mb-3">
                <label for="store_name" class="form-label fw-semibold">Tên quán:</label>
                <input type="text" class="form-control rounded-2" id="store_name" name="store_name"
                    value="{{ $setting->store_name }}">
                @error('store_name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="address" class="form-label fw-semibold">Địa chỉ:</label>
                <input type="text" class="form-control rounded-2" id="address" name="address"
                    value="{{ $setting->address }}">
                @error('address')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="phone_number" class="form-label fw-semibold">Số điện thoại:</label>
                <input type="text" class="form-control rounded-2" id="phone_number" name="phone_number"
                    value="{{ $setting->phone_number }}">
                @error('phone_number')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <button type="submit" class="btn btn-primary fw-semibold">Lưu</button>
                <a href="{{ route('admin.home.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
// Hiện thông báo nếu có
$(document).ready(function() {
    setTimeout(function() {
        $('.flash-message .alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection