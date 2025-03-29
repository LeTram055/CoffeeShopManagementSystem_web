@extends('admin.layouts.master')

@section('title')
Quản lý thưởng phạt
@endsection

@section('feature-title')
Quản lý thưởng phạt
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-6 border rounded-3 p-5 custom-shadow">
        <h3 class="text-center title2 mb-4">Cập nhật thưởng/phạt</h3>
        <form name="frmEdit" id="frmEdit" method="post" action="{{ route('admin.bonuspenalty.update') }}">
            @csrf

            <input type="hidden" name="bonus_penalty_id" value="{{ $bonusPenalty->bonus_penalty_id }}">
            <div class="form-group mb-3">
                <label for="employee_id" class="form-label fw-semibold">Nhân viên:</label>
                <select class="form-control rounded-2" id="employee_id" name="employee_id">
                    <option value="">-- Chọn nhân viên --</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->employee_id }}"
                        {{ $bonusPenalty->employee_id == $employee->employee_id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                    @endforeach
                </select>
                @error('employee_id')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="type" class="form-label fw-semibold">Loại:</label>
                <select class="form-control rounded-2" id="type" name="type" required>
                    <option value="">-- Chọn loại --</option>
                    <option value="bonus" {{ $bonusPenalty->amount >= 0 ? 'selected' : '' }}>Thưởng</option>
                    <option value="penalty" {{ $bonusPenalty->amount < 0 ? 'selected' : '' }}>Phạt</option>
                </select>
                @error('type')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="amount" class="form-label fw-semibold">Số tiền:</label>
                <input type="number" class="form-control rounded-2" id="amount" name="amount"
                    value="{{ abs($bonusPenalty->amount) }}">
                @error('amount')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="reason" class="form-label fw-semibold">Lý do:</label>
                <textarea class="form-control rounded-2" id="reason"
                    name="reason">{{ $bonusPenalty->reason }}</textarea>
                @error('reason')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="date" class="form-label fw-semibold">Ngày:</label>
                <input type="date" class="form-control rounded-2" id="date" name="date"
                    value="{{ old('date', optional($bonusPenalty->date)->format('Y-m-d')) }}">

                @error('date')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary fw-semibold">Cập nhật</button>
            <a href="{{ route('admin.bonuspenalty.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection