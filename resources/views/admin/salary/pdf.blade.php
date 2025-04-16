<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lương nhân viên {{ $salary->employee->name }}</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        margin: 20px;
    }

    h5,
    h3,
    h2 {
        margin-bottom: 15px;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 20px;
        padding: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
        text-align: center;
    }

    .centered {
        text-align: center;
        /* Căn giữa nội dung cho dòng "Lương cuối cùng" */
    }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Chi Tiết Lương</h2>

    <h4 class="mb-4">Thông tin lương nhân viên: <strong>{{ $salary->employee->name }}</strong></h4>
    <div class="card">
        <table class="info-table">
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
                <td><strong>Trạng thái:</strong> {{ $salary->status == 'pending' ? 'Chờ duyệt' : 'Đã trả' }}</td>
                <td><strong>Tổng lương:</strong> {{ number_format($salary->total_salary, 0, ',', '.') }} VNĐ</td>
            </tr>
            <tr>
                <td colspan="2" class="centered"><strong>Lương cuối cùng:</strong>
                    {{ number_format($salary->final_salary, 0, ',', '.') }} VNĐ</td>
            </tr>
        </table>
    </div>

    @if($salary->employee->workSchedules->isNotEmpty())
    <h4 class="mb-4">Chi tiết lịch làm việc</h4>
    <table>
        <thead>
            <tr>
                <th>Ngày</th>
                <th>Ca làm</th>
                <th>Số giờ làm</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salary->employee->workSchedules as $schedule)
            <tr>
                <td class="centered">{{ $schedule->work_date->format('d/m/Y') }}</td>
                <td class="centered">
                    {{ $schedule->shift->name }} ({{ $schedule->shift->start_time->format('H:i') }} -
                    {{ $schedule->shift->end_time->format('H:i') }})
                </td>
                <td class="centered">{{ $schedule->work_hours }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($salary->employee->bonusesPenalties->isNotEmpty())
    <h4 class="mb-4">Chi tiết thưởng/ phạt</h4>
    <table>
        <thead>
            <tr>
                <th>Lý do</th>
                <th>Số tiền</th>
                <th>Ngày</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salary->employee->bonusesPenalties as $bonusPenalty)
            <tr>
                <td class="centered">{{ $bonusPenalty->reason }}</td>
                <td class="centered">{{ number_format($bonusPenalty->amount, 0, ',', '.') }} VNĐ</td>
                <td class="centered">{{ $bonusPenalty->date->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>

</html>