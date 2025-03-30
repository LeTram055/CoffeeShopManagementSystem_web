<h5 class="mb-4">Thông tin lương nhân viên: <strong>{{ $salary->employee->name }}</strong></h5>
<div class="card mb-4">
    <div class="card-body">
        <ul class="list-group">
            <li class="list-group-item">Mã lương: <strong>{{ $salary->salary_id }}</strong></li>
            <li class="list-group-item">Tháng: <strong>{{ $salary->month }}</strong></li>
            <li class="list-group-item">Năm: <strong>{{ $salary->year }}</strong></li>
            <li class="list-group-item">Số giờ làm: <strong>{{ $salary->total_hours }}</strong></li>
            <li class="list-group-item">Lương theo giờ:
                <strong>{{ number_format($salary->salary_per_hour, 0, ',', '.') }} VNĐ</strong>
            </li>
            <li class="list-group-item">Tổng lương: <strong>{{ number_format($salary->total_salary, 0, ',', '.') }}
                    VNĐ</strong></li>
            <li class="list-group-item">Thưởng/Phạt:
                <strong>{{ number_format($salary->total_bonus_penalty, 0, ',', '.') }} VNĐ</strong>
            </li>
            <li class="list-group-item">Lương cuối cùng: <strong>{{ number_format($salary->final_salary, 0, ',', '.') }}
                    VNĐ</strong></li>
            <li class="list-group-item">Trạng thái:
                <strong>{{ $salary->status == 'pending' ? 'Chờ duyệt' : 'Đã trả' }}</strong>
            </li>
        </ul>
    </div>
</div>

<h5 class="mb-4">Chi tiết ca làm việc</h5>
<div class="table-responsive mb-4">
    <table class="table table-striped">
        <thead class="table-light">
            <tr>
                <th>Ngày</th>
                <th>Ca làm</th>

                <th>Số giờ làm</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salary->employee->workSchedules as $schedule)
            <tr>
                <td>{{ $schedule->work_date->format('d/m/Y') }}</td>
                <td>{{ $schedule->shift->name ($schedule->shift->start_time->format('H:i') - $schedule->shift->end_time->format('H:i')) }}
                </td>

                <td>{{ $schedule->work_hours }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<h5 class="mb-4">Chi tiết thưởng/ phạt</h5>
<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-light">
            <tr>
                <th>Lý do</th>
                <th>Số tiền</th>
                <th>Ngày</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salary->employee->bonusesPenalties as $bonusPenalty)
            <tr>
                <td>{{ $bonusPenalty->reason }}</td>
                <td>{{ number_format($bonusPenalty->amount, 0, ',', '.') }} VNĐ</td>
                <td>{{ $bonusPenalty->date->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>