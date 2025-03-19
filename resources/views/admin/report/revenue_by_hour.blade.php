@extends('admin/layouts/master')

@section('title')
Thống kê doanh thu theo giờ
@endsection

@section('feature-title')
Thống kê doanh thu theo giờ
@endsection

@section('content')
<div class="container">

    <div class="row mb-4 d-flex justify-content-center">
        <div class="col-md-3">
            <label for="startDate">Ngày bắt đầu:</label>
            <input type="date" id="startDate" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="endDate">Ngày kết thúc:</label>
            <input type="date" id="endDate" class="form-control">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-bg w-100" onclick="fetchRevenueByHour()">Lọc</button>
        </div>
    </div>

    <canvas id="revenueByHourChart"></canvas>
</div>
@endsection

@section('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
async function fetchRevenueByHour() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    let url = '{{ route("admin.reports.revenueByHour") }}';
    if (startDate && endDate) {
        url += `?startDate=${startDate}&endDate=${endDate}`;
    }

    const response = await fetch(url);
    const data = await response.json();

    console.log("Revenue Data:", data);

    if (!Array.isArray(data) || data.length === 0) {
        console.error("Dữ liệu không hợp lệ!", data);
        return;
    }

    const labels = data.map(d => `${d.hour}:00`);
    const values = data.map(d => d.total_revenue);

    const ctx = document.getElementById('revenueByHourChart').getContext('2d');

    // Hủy biểu đồ cũ nếu có
    if (window.revenueChart instanceof Chart) {
        window.revenueChart.destroy();
    }

    // Vẽ biểu đồ mới
    window.revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu theo giờ',
                data: values,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Gọi khi trang tải xong
fetchRevenueByHour();
</script>
@endsection