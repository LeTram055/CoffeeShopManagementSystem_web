@extends('admin.layouts.master')

@section('title')
Quản lý bàn
@endsection
@section('feature-title')
Quản lý bàn
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-6 border rounded-3 p-5 custom-shadow mb-3">
        <h3 class="text-center title2 mb-4">Thêm mới bàn</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.table.save') }}">
            @csrf

            <!-- Số bàn -->
            <div class="form-group mb-3">
                <label for="table_number" class="form-label fw-semibold">Số bàn:</label>
                <input type="text" class="form-control rounded-2" id="table_number" name="table_number"
                    value="{{ old('table_number') }}" placeholder="Nhập số bàn">
                @error('table_number')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Số lượng ghế -->
            <div class="form-group mb-3">
                <label for="capacity" class="form-label fw-semibold">Số lượng ghế:</label>
                <input type="number" class="form-control rounded-2" id="capacity" name="capacity"
                    value="{{ old('capacity') }}" placeholder="Nhập số lượng ghế">
                @error('capacity')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Chọn trạng thái bàn -->
            <div class="form-group mb-3">
                <label for="status_id" class="form-label fw-semibold">Trạng thái bàn:</label>
                <select class="form-control rounded-2" id="status_id" name="status_id">
                    <option value="">-- Chọn trạng thái --</option>
                    @foreach($statuses as $status)
                    <option value="{{ $status->status_id }}"
                        {{ old('status_id') == $status->status_id ? 'selected' : '' }}>
                        {{ $status->name }}
                    </option>
                    @endforeach
                </select>
                @error('status_id')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary fw-semibold">Lưu</button>
            <a href="{{ route('admin.table.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection