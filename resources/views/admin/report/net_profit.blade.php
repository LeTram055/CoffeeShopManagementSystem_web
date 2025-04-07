@extends('admin/layouts/master')

@section('title')
Thống kê doanh thu - chi phí
@endsection

@section('feature-title')
Thống kê doanh thu - chí phí
@endsection

@section('content')
<div class="container">


    <!-- Bộ lọc ngày -->
    <div class="row mb-4 d-flex justify-content-center">
        <div class="col-md-2 d-flex align-items-end">
            <a class="btn btn-outline-secondary w-100" href={{ route('admin.reports.netProfitPage') }}>Làm
                mới</a>
        </div>
        <div class="col-md-2">
            <label for="fromDate">Ngày bắt đầu:</label>
            <input type="date" id="fromDate" class="form-control">
        </div>
        <div class="col-md-2">
            <label for="toDate">Ngày kết thúc:</label>
            <input type="date" id="toDate" class="form-control">
        </div>
        <div class="col-md-2 d-flex align-items-end mt-1">
            <button class="btn btn-bg w-100" onclick="fetchNetProfit()">Lọc</button>
        </div>
    </div>

    <!-- Biểu đồ -->
    <canvas id="netProfitChart" class="my-4" style="max-height: 550px; width: 100%"></canvas>
    <!-- <div class="row justify-content-center mb-5">
        <div class="col-md-6">
            <canvas id="netProfitChart" style="height: 300px;"></canvas>
        </div>
    </div> -->

    <!-- Bảng chi tiết -->
    <!-- <div class="row justify-content-center">
        <div class="col-md-8">
            <h5 class="mb-3">Chi tiết lợi nhuận</h5>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Chỉ tiêu</th>
                        <th>Số tiền (VND)</th>
                    </tr>
                </thead>
                <tbody id="netProfitTableBody">
                    
                </tbody>
            </table>
        </div>
    </div> -->
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.addEventListener('DOMContentLoaded', () => {
    const today = new Date();
    const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

    const fromDateInput = document.getElementById('fromDate');
    const toDateInput = document.getElementById('toDate');

    // Format YYYY-MM-DD
    const formatDateToInput = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    if (!fromDateInput.value) {
        fromDateInput.value = formatDateToInput(startOfMonth);
    }

    if (!toDateInput.value) {
        toDateInput.value = formatDateToInput(endOfMonth);
    }

    // Ngày kết thúc không được trước ngày bắt đầu
    toDateInput.min = fromDateInput.value;

    fromDateInput.addEventListener('change', () => {
        // Cập nhật ngày min cho toDate
        toDateInput.min = fromDateInput.value;

        // Nếu ngày kết thúc đang nhỏ hơn ngày bắt đầu thì set lại bằng ngày bắt đầu
        if (toDateInput.value < fromDateInput.value) {
            toDateInput.value = fromDateInput.value;
        }
    });
});

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

    // Vẽ biểu đồ 
    window.netProfitChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Doanh thu',
                'Nguyên liệu xuất',
                'Nguyên liệu nhập',
                'Chi phí lương',
                'Tổng giá trị đơn hàng',
                'Khuyến mãi',
                // 'Lợi nhuận ròng'
            ],
            datasets: [{
                label: 'Số tiền (VND)',
                data: [
                    data.total_revenue,
                    data.ingredient_export_cost,
                    data.ingredient_import_cost,
                    data.salary_cost,
                    data.real_order_cost,
                    data.promotion_cost,
                    // data.net_profit
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)', // Doanh thu
                    'rgba(255, 159, 64, 0.6)', // Nguyên liệu xuất
                    'rgba(255, 205, 86, 0.6)', // Nguyên liệu nhập
                    'rgba(201, 203, 207, 0.6)', // Lương
                    'rgba(153, 102, 255, 0.6)', // Chi phí đơn hàng
                    'rgba(255, 99, 132, 0.6)', // Khuyến mãi
                    // 'rgba(54, 162, 235, 0.6)' // Lợi nhuận ròng
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(201, 203, 207, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 99, 132, 1)',
                    // 'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + Number(context.raw).toLocaleString(
                                'vi-VN') + ' đ';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                }
            }
        }
    });

    // Bảng dữ liệu
    // const tableBody = document.getElementById("netProfitTableBody");
    // tableBody.innerHTML = `
    //     <tr>
    //         <td><strong>Tổng doanh thu</strong></td>
    //         <td>${Number(data.total_revenue).toLocaleString('vi-VN')} đ</td>
    //     </tr>
    //     <tr>
    //         <td><strong>Chi phí nguyên liệu xuất</strong></td>
    //         <td>${Number(data.ingredient_export_cost).toLocaleString('vi-VN')} đ</td>
    //     </tr>
    //     <tr>
    //         <td><strong>Chi phí nguyên liệu nhập</strong></td>
    //         <td>${Number(data.ingredient_import_cost).toLocaleString('vi-VN')} đ</td>
    //     </tr>
    //     <tr>
    //         <td><strong>Chi phí lương</strong></td>
    //         <td>${Number(data.salary_cost).toLocaleString('vi-VN')} đ</td>
    //     </tr>
    //     <tr>
    //         <td><strong>Chi phí thực sự của đơn hàng</strong></td>
    //         <td>${Number(data.real_order_cost).toLocaleString('vi-VN')} đ</td>
    //     </tr>
    //     <tr>
    //         <td><strong>Chi phí khuyến mãi</strong></td>
    //         <td>${Number(data.promotion_cost).toLocaleString('vi-VN')} đ</td>
    //     </tr>
    //     <tr>
    //         <td><strong>Tổng chi phí</strong></td>
    //         <td>${Number(data.total_cost).toLocaleString('vi-VN')} đ</td>
    //     </tr>
    //     <tr>
    //         <td><strong>Lợi nhuận ròng</strong></td>
    //         <td>${Number(data.net_profit).toLocaleString('vi-VN')} đ</td>
    //     </tr>
    // `;
}

fetchNetProfit();
</script>
@endsection