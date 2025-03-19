@extends('admin/layouts/master')

@section('title')
Thống kê lợi nhuận
@endsection

@section('feature-title')
Thống kê lợi nhuận
@endsection

@section('content')
<div class="container">


    <!-- Bộ lọc ngày -->
    <div class="row mb-4 d-flex justify-content-center">
        <div class="col-md-3">
            <label for="fromDate">Từ ngày:</label>
            <input type="date" id="fromDate" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="toDate">Đến ngày:</label>
            <input type="date" id="toDate" class="form-control">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-bg w-100" onclick="fetchNetProfit()">Lọc</button>
        </div>
    </div>

    <!-- Biểu đồ -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <canvas id="netProfitChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
async function fetchNetProfit() {
    const fromDate = document.getElementById("fromDate").value;
    const toDate = document.getElementById("toDate").value;

    let url = `{{ route("admin.reports.netProfit") }}`;
    if (fromDate && toDate) {
        url += `?from_date=${fromDate}&to_date=${toDate}`;
    }

    const response = await fetch(url);
    const data = await response.json();

    const ctx = document.getElementById('netProfitChart');

    // Hủy biểu đồ cũ nếu có
    if (window.netProfitChart instanceof Chart) {
        window.netProfitChart.destroy();
    }

    // Vẽ biểu đồ mới
    window.netProfitChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Tổng doanh thu', 'Tổng chi phí', 'Lợi nhuận ròng'],
            datasets: [{
                label: 'Số tiền (VND)',
                data: [data.total_revenue, data.total_cost, data.net_profit],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

fetchNetProfit();
</script>
@endsection