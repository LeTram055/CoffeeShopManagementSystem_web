<h5 class="mb-4">Thông tin lương nhân viên: <strong>{{ $salary->employee->name }}</strong></h5>
<div class="card mb-4">
    <div class="card-body">
        <!-- <ul class="list-group">
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
                <strong id="salaryStatus">{{ $salary->status == 'pending' ? 'Chờ duyệt' : 'Đã trả' }}</strong>
            </li>
        </ul> -->
        <table class="table table-bordered mb-0">
            <colgroup>
                <col style="width: 50%;"> <!-- Cột 1 chiếm 50% -->
                <col style="width: 50%;"> <!-- Cột 2 chiếm 50% -->
            </colgroup>
            <tr>
                <td><strong>Mã lương:</strong> {{ $salary->salary_id }}</td>
                <td><strong>Lương theo giờ:</strong> {{ number_format($salary->salary_per_hour, 0, ',', '.') }} VNĐ</td>
            </tr>
            <tr>
                <td><strong>Tháng:</strong> {{ $salary->month }}</td>
                <td><strong>Số giờ làm:</strong> {{ $salary->total_hours }}</td>
            </tr>
            <tr>
                <td><strong>Năm:</strong> {{ $salary->year }}</td>
                <td><strong>Thưởng/Phạt:</strong> {{ number_format($salary->total_bonus_penalty, 0, ',', '.') }} VNĐ
                </td>
            </tr>
            <tr>
                <td><strong>Trạng thái:</strong> <span
                        id="salaryStatus">{{ $salary->status == 'pending' ? 'Chờ duyệt' : 'Đã trả' }}</span></td>
                <td><strong>Tổng lương:</strong> {{ number_format($salary->total_salary, 0, ',', '.') }} VNĐ</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center"><strong>Lương cuối cùng:</strong>
                    {{ number_format($salary->final_salary, 0, ',', '.') }} VNĐ</td>
            </tr>
        </table>
    </div>
</div>

<h5 class="mb-4">Chi tiết lịch làm việc</h5>
<div class="table-responsive mb-4">
    <table class="table table-striped">
        <thead class="table-light">
            <tr>
                <th class="text-center">Ngày</th>
                <th class="text-center">Ca làm</th>

                <th class="text-center">Số giờ làm</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salary->employee->workSchedules as $schedule)
            <tr>
                <td class="text-center">{{ $schedule->work_date->format('d/m/Y') }}</td>
                <td class="text-center">
                    {{ $schedule->shift->name }} ({{ $schedule->shift->start_time->format('H:i') }} -
                    {{ $schedule->shift->end_time->format('H:i') }})
                </td class="text-center">

                <td class="text-center">{{ $schedule->work_hours }}</td>
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
                <th class="text-center">Lý do</th>
                <th class="text-center">Số tiền</th>
                <th class="text-center">Ngày</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salary->employee->bonusesPenalties as $bonusPenalty)
            <tr>
                <td class="text-center">{{ $bonusPenalty->reason }}</td>
                <td class="text-center">{{ number_format($bonusPenalty->amount, 0, ',', '.') }} VNĐ</td>
                <td class="text-center">{{ $bonusPenalty->date->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>