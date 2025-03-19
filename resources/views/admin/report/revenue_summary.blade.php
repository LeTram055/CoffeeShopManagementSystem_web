@extends('admin.layouts.master')

@section('title')
Thống kê doanh thu
@endsection

@section('feature-title')
Thống kê doanh thu
@endsection

@section('content')
<div class="container">
    <!-- Bộ lọc ngày -->
    <div class="row mb-4 d-flex justify-content-center">
        <div class="col-md-3">
            <label for="startDate">Ngày bắt đầu:</label>
            <input type="date" id="startDate" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="endDate">Ngày kết thúc:</label>
            <input type="date" id="endDate" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="timeFrame">Thời gian:</label>
            <select id="timeFrame" class="form-control">
                <option value="daily">Hàng ngày</option>
                <option value="weekly">Hàng tuần</option>
                <option value="monthly">Hàng tháng</option>
                <option value="yearly">Hàng năm</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-bg w-100" onclick="fetchRevenueData()">Lọc</button>
        </div>
    </div>

    <div class="row mt-3 d-flex justify-content-center">
        <div class="col-md-3">
            <button id="toggleChartBtn" class="btn btn-outline-primary w-100" onclick="toggleChart()">Hiển thị Biểu đồ
                Cột</button>
        </div>
    </div>



    <!-- Biểu đồ đường -->
    <canvas id="revenueChart" class="mt-2"></canvas>

    <!-- Biểu đồ cột -->
    <canvas id="revenueBarChart" class="mt-2" style="display: none;"></canvas>


</div>

@endsection

@section('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let revenueChart = null;
let revenueBarChart = null;

function formatDate(dateString, timeFrame) {
    console.log("Dữ liệu nhận được: ", dateString);

    // Kiểm tra nếu chỉ có năm (ví dụ: "2025")
    if (timeFrame === 'yearly' && /^\d{4}$/.test(dateString)) {
        return `Năm ${dateString}`;
    }

    const date = new Date(dateString);

    if (timeFrame === 'weekly') {
        const startOfWeek = new Date(date);
        startOfWeek.setDate(date.getDate() - date.getDay());
        return `Tuần ${startOfWeek.getDate()}/${startOfWeek.getMonth() + 1}/${startOfWeek.getFullYear()}`;
    }

    if (timeFrame === 'yearly') {
        return `Năm ${date.getFullYear()}`;
    }

    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}


async function fetchRevenueData() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const timeFrame = document.getElementById('timeFrame').value;

    let url = `{{ route('admin.reports.revenueSummary') }}?timeFrame=${timeFrame}`;
    if (startDate && endDate) {
        url += `&startDate=${startDate}&endDate=${endDate}`;
    }

    const response = await fetch(url);
    const data = await response.json();

    // Định dạng lại ngày trước khi hiển thị
    const labels = data.map(d => formatDate(d.date, timeFrame));

    const values = data.map(d => d.total_revenue);

    const lineCtx = document.getElementById('revenueChart').getContext('2d');
    const barCtx = document.getElementById('revenueBarChart').getContext('2d');

    // Hủy biểu đồ cũ nếu đã tồn tại
    if (revenueChart instanceof Chart) {
        revenueChart.destroy();
    }
    if (revenueBarChart instanceof Chart) {
        revenueBarChart.destroy();
    }

    // Biểu đồ đường
    revenueChart = new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu',
                data: values,
                borderColor: 'blue',
                backgroundColor: 'rgba(0, 0, 255, 0.2)',
                fill: true
            }]
        }
    });

    // Biểu đồ cột
    revenueBarChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu',
                data: values,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
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
fetchRevenueData();


// Gọi khi trang tải xong
fetchRevenueData();

function toggleChart() {
    let lineChart = document.getElementById('revenueChart');
    let barChart = document.getElementById('revenueBarChart');
    let toggleBtn = document.getElementById('toggleChartBtn');

    if (lineChart.style.display === 'none') {
        // Hiển thị biểu đồ đường, ẩn biểu đồ cột
        lineChart.style.display = 'block';
        barChart.style.display = 'none';
        toggleBtn.innerText = 'Hiển thị Biểu đồ Cột';
    } else {
        // Hiển thị biểu đồ cột, ẩn biểu đồ đường
        lineChart.style.display = 'none';
        barChart.style.display = 'block';
        toggleBtn.innerText = 'Hiển thị Biểu đồ Đường';
    }
}
</script>
@endsection