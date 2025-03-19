@extends('admin/layouts/master')

@section('title')
Thống kê doanh thu theo sản phẩm
@endsection

@section('feature-title')
Thống kê doanh thu theo sản phẩm
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
            <button class="btn btn-bg w-100" onclick="fetchProductData()">Lọc</button>
        </div>
    </div>

    <canvas id="productChart"></canvas>

</div>
@endsection

@section('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
async function fetchProductData() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    let url = '{{ route("admin.reports.revenueByProduct") }}';
    if (startDate && endDate) {
        url += `?startDate=${startDate}&endDate=${endDate}`;
    }

    const response = await fetch(url);
    const data = await response.json();

    console.log("Product Data:", data);

    if (!Array.isArray(data) || data.length === 0 || !data[0].hasOwnProperty('name')) {
        console.error("Dữ liệu không hợp lệ!", data);
        return;
    }

    const labels = data.map(d => d.name);
    const values = data.map(d => d.total_revenue);

    const ctx = document.getElementById('productChart').getContext('2d');

    // Hủy biểu đồ cũ nếu có
    if (window.productChart instanceof Chart) {
        window.productChart.destroy();
    }

    // Vẽ biểu đồ mới
    window.productChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu theo sản phẩm',
                data: values,
                backgroundColor: 'green'
            }]
        }
    });
}

// Gọi khi trang tải xong
fetchProductData();
</script>

@endsection