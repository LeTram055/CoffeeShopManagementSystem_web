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

@php
function getRoleInVietnamese($role)
{
switch ($role) {
case 'admin':
return 'Quản trị viên';
case 'staff_counter':
return 'Nhân viên thu ngân';
case 'staff_serve':
return 'Nhân viên phục vụ';
case 'staff_barista':
return 'Nhân viên pha chế';
default:
return 'Không xác định';
}
}

function getStatusInVietnamese($status)
{
switch ($status) {
case 'scheduled':
return 'Đã lên lịch';
case 'completed':
return 'Hoàn thành';
case 'absent':
return 'Vắng mặt';
case 'pending':
return 'Chờ duyệt';
case 'paid':
return 'Đã trả';
default:
return 'Không xác định';
}
}
@endphp

@extends($layout)
@section('title', 'Thông tin nhân viên')
@section('feature-title', 'Thông tin nhân viên')

@section('content')
<div class="mb-4">
    <form method="GET" action="{{ route('profile') }}" class="d-flex gap-2">
        <select name="month" class="form-select">
            <option value="">Chọn tháng</option>
            @for ($m = 1; $m <= 12; $m++) <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>Tháng {{ $m }}
                </option>
                @endfor
        </select>
        <select name="year" class="form-select">
            <option value="">Chọn năm</option>
            @for ($y = date('Y') - 5; $y <= date('Y'); $y++) <option value="{{ $y }}"
                {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
        </select>
        <button type="submit" class="btn btn-primary">Lọc</button>
    </form>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>Thông tin cơ bản</h5>
    </div>
    <div class="card-body">
        <p><strong>Tên:</strong> {{ $employee->name }}</p>
        <p><strong>Tên đăng nhập:</strong> {{ $employee->username }}</p>
        <p><strong>Email:</strong> {{ $employee->email }}</p>
        <p><strong>Số điện thoại:</strong> {{ $employee->phone_number }}</p>
        <p><strong>Địa chỉ:</strong> {{ $employee->address }}</p>
        <p><strong>Vai trò:</strong> {{ getRoleInVietnamese($employee->role) }}</p>
        <p><strong>Ngày bắt đầu:</strong> {{ $employee->start_date->format('d/m/Y') }}</p>
        <p><strong>Lương theo giờ:</strong> {{ number_format($employee->hourly_rate, 0, ',', '.') }} VNĐ</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>Lịch làm việc</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-center">Ngày</th>
                    <th class="text-center">Ca làm</th>
                    <th class="text-center">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($workSchedules as $schedule)
                <tr>
                    <td class="text-center">{{ $schedule->work_date->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $schedule->shift->name }}
                        ({{ $schedule->shift->start_time->format('H:i') }} -
                        {{ $schedule->shift->end_time->format('H:i') }})</td>
                    <td class="text-center">{{ getStatusInVietnamese($schedule->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>Thưởng/Phạt</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-center">Lý do</th>
                    <th class="text-center">Số tiền</th>
                    <th class="text-center">Ngày</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bonusesPenalties as $bonusPenalty)
                <tr>
                    <td class="text-center">{{ $bonusPenalty->reason }}</td>
                    <td class="text-center">{{ number_format($bonusPenalty->amount, 0, ',', '.') }} VNĐ</td>
                    <td class="text-center">{{ $bonusPenalty->date->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>Lương</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-center">Tháng</th>
                    <th class="text-center">Năm</th>
                    <th class="text-center">Tổng lương</th>
                    <th class="text-center">Thưởng/Phạt</th>
                    <th class="text-center">Lương cuối cùng</th>
                    <th class="text-center">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salaries as $salary)
                <tr>
                    <td class="text-center">{{ $salary->month }}</td>
                    <td class="text-center">{{ $salary->year }}</td>
                    <td class="text-center">{{ number_format($salary->total_salary, 0, ',', '.') }} VNĐ</td>
                    <td class="text-center">{{ number_format($salary->total_bonus_penalty, 0, ',', '.') }} VNĐ</td>
                    <td class="text-center">{{ number_format($salary->final_salary, 0, ',', '.') }} VNĐ</td>
                    <td class="text-center">{{ getStatusInVietnamese($salary->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection