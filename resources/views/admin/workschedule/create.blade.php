@extends('admin.layouts.master')

@section('title')
Quản lý lịch làm việc
@endsection

@section('feature-title')
Quản lý lịch làm việc
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-6 border rounded-3 p-5 custom-shadow">
        <h3 class="text-center title2 mb-4">Thêm mới lịch làm việc</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.workschedule.save') }}">
            @csrf

            <!-- Chọn Nhân Viên -->
            <!-- Chọn Nhân Viên -->
            <div class="form-group mb-3">
                <label for="employee_id" class="form-label fw-semibold">Nhân viên:</label>
                <select class="form-control rounded-2" id="employee_id" name="employee_id">
                    <option value="">-- Chọn nhân viên --</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->employee_id }}"
                        {{ old('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                        {{ $employee->name }} -
                        @switch($employee->role)
                        @case('admin')
                        Quản trị viên
                        @break
                        @case('staff_serve')
                        Nhân viên phục vụ
                        @break
                        @case('staff_barista')
                        Nhân viên pha chế
                        @break
                        @case('staff_counter')
                        Nhân viên thu ngân
                        @break
                        @default
                        Khác
                        @endswitch
                    </option>
                    @endforeach
                </select>
                @error('employee_id')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Chọn Ca Làm -->
            <div class="form-group mb-3">
                <label for="shift_id" class="form-label fw-semibold">Ca làm việc:</label>
                <select class="form-control rounded-2" id="shift_id" name="shift_id">
                    <option value="">-- Chọn ca làm việc --</option>
                    @foreach($shifts as $shift)
                    <option value="{{ $shift->shift_id }}" {{ old('shift_id') == $shift->shift_id ? 'selected' : '' }}>
                        {{ $shift->name }} ({{ $shift->start_time->format('H:i') }} -
                        {{ $shift->end_time->format('H:i') }})
                    </option>
                    @endforeach
                </select>
                @error('shift_id')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Chọn Ngày Làm -->
            <div class="form-group mb-3">
                <label for="work_date" class="form-label fw-semibold">Ngày làm việc:</label>
                <input type="date" class="form-control rounded-2" id="work_date" name="work_date"
                    value="{{ old('work_date') }}">
                @error('work_date')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Chọn Trạng Thái -->
            <div class="form-group mb-3">
                <label for="status" class="form-label fw-semibold">Trạng thái:</label>
                <select class="form-control rounded-2" id="status" name="status">
                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Đã lên lịch</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Vắng mặt</option>
                </select>
                @error('status')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Nhập Số Giờ Làm -->
            <!-- <div class="form-group mb-3">
                <label for="work_hours" class="form-label fw-semibold">Số giờ làm:</label>
                <input type="number" step="0.1" class="form-control rounded-2" id="work_hours" name="work_hours"
                    value="{{ old('work_hours', 0) }}" placeholder="Nhập số giờ làm">
                @error('work_hours')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div> -->

            <!-- Nút Lưu -->
            <button type="submit" class="btn btn-primary fw-semibold">Lưu</button>
            <a href="{{ route('admin.workschedule.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection