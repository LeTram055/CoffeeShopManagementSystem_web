@extends('admin/layouts/master')

@section('title')
Thống kê doanh thu theo hình thức phục vụ
@endsection

@section('feature-title')
Thống kê doanh thu theo hình thức phục vụ
@endsection

@section('content')
<div class="container">
    <div class="row mb-4 d-flex justify-content-center">
        <div class="col-md-2 d-flex align-items-end">
            <a class="btn btn-outline-secondary w-100" href={{ route('admin.reports.revenueByOrderTypePage') }}>Làm
                mới</a>
        </div>
        <div class="col-md-2">
            <label for="startDate">Ngày bắt đầu:</label>
            <input type="date" id="startDate" class="form-control">
        </div>
        <div class="col-md-2">
            <label for="endDate">Ngày kết thúc:</label>
            <input type="date" id="endDate" class="form-control">
        </div>
        <div class="col-md-2 d-flex align-items-end mt-1">
            <button class="btn btn-bg w-100" onclick="fetchRevenueByOrderType()">Lọc</button>
        </div>
    </div>
    <div class="d-flex justify-content-center my-4">
        <div style="width: 100%; max-width: 500px; aspect-ratio: 1;">
            <canvas id="revenueByOrderTypeChart"></canvas>
        </div>
    </div>


    <!-- <canvas id="revenueByOrderTypeChart"></canvas> -->
</div>
@endsection

@section('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.addEventListener('DOMContentLoaded', () => {
    const today = new Date();
    const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');

    const formatDateToInput = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    if (!startDateInput.value) {
        startDateInput.value = formatDateToInput(startOfMonth);
    }

    if (!endDateInput.value) {
        endDateInput.value = formatDateToInput(endOfMonth);
    }

    // Ngày kết thúc không được trước ngày bắt đầu
    endDateInput.min = startDateInput.value;

    startDateInput.addEventListener('change', () => {
        // Cập nhật ngày min cho endDate
        endDateInput.min = startDateInput.value;

        // Nếu ngày kết thúc đang nhỏ hơn ngày bắt đầu thì set lại bằng ngày bắt đầu
        if (endDateInput.value < startDateInput.value) {
            endDateInput.value = startDateInput.value;
        }
    });
});

async function fetchRevenueByOrderType() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    let url = '{{ route("admin.reports.revenueByOrderType") }}';
    if (startDate && endDate) {
        url += `?startDate=${startDate}&endDate=${endDate}`;
    }

    const response = await fetch(url);
    const data = await response.json();

    if (!Array.isArray(data) || data.length === 0) {
        console.error("Dữ liệu không hợp lệ!", data);
        return;
    }

    const labels = data.map(d => {
        if (d.order_type === 'dine_in') return 'Dùng tại chỗ';
        if (d.order_type === 'takeaway') return 'Mang về';
        return 'Không xác định';
    });

    const values = data.map(d => d.total_revenue);

    const ctx = document.getElementById('revenueByOrderTypeChart').getContext('2d');

    // Hủy biểu đồ cũ nếu có
    if (window.serveTypeChart instanceof Chart) {
        window.serveTypeChart.destroy();
    }

    // Vẽ biểu đồ tròn
    window.serveTypeChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu theo hình thức phục vụ',
                data: values,
                backgroundColor: [
                    // 'rgba(255, 99, 132, 0.6)',
                    // 'rgba(54, 162, 235, 0.6)',
                    // 'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    // 'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
}


fetchRevenueByOrderType();
</script>
@endsection