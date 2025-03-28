@extends('admin.layouts.master')

@section('title')
Quản lý ca làm việc
@endsection

@section('feature-title')
Quản lý ca làm việc
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-6 border rounded-3 p-5 custom-shadow">
        <h3 class="text-center title2 mb-4">Cập nhật ca làm việc</h3>
        <form name="frmEdit" id="frmEdit" method="post" action="{{ route('admin.shift.update') }}">
            @csrf
            <input type="hidden" name="shift_id" value="{{ $shift->shift_id }}">
            <div class="form-group mb-3">
                <label for="name" class="form-label fw-semibold">Tên ca:</label>
                <input type="text" class="form-control rounded-2" id="name" name="name"
                    value="{{ old('name', $shift->name) }}">
                @error('name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="start_time" class="form-label fw-semibold">Thời gian bắt đầu:</label>
                <input type="time" class="form-control rounded-2" id="start_time" name="start_time"
                    value="{{ old('start_time', $shift->start_time->format('H:i:s')) }}">
                @error('start_time')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="end_time" class="form-label fw-semibold">Thời gian kết thúc:</label>
                <input type="time" class="form-control rounded-2" id="end_time" name="end_time"
                    value="{{ old('end_time', $shift->end_time->format('H:i:s')) }}">
                @error('end_time')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary fw-semibold">Lưu</button>
            <a href="{{ route('admin.shift.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection