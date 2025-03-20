@extends('staff_counter.layouts.master')

@section('title', 'Thống kê thu ngân')
@section('feature-title', 'Thống kê thu ngân')

@section('content')
<div class="container mt-3">
    <div class="row mb-4 justify-content-center">
        <div class="col-md-4">
            <label for="reportDate" class="fw-bold">Chọn ngày:</label>
            <input type="date" id="reportDate" class="form-control shadow-sm" value="{{ now()->toDateString() }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100 shadow" onclick="fetchCashierReport()">
                Xem thống kê
            </button>
        </div>
    </div>

    <!-- Card lớn chứa toàn bộ thống kê -->
    <div class="card shadow-lg">
        <!-- <div class="card-header bg-primary text-white text-center fw-bold">
            Thống kê thu ngân
        </div> -->
        <div class="card-body">
            <div class="list-group">
                <div class="list-group-item d-flex justify-content-between">
                    <span class="fw-bold">Tổng số đơn hàng</span>
                    <span id="totalOrders" class="fw-bold text-primary">0</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="fw-bold">Tổng giá trị đơn hàng</span>
                    <span id="totalRevenue" class="text-success fw-bold">0 VND</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="fw-bold">Tổng giảm giá</span>
                    <span id="totalDiscount" class="text-danger fw-bold">0 VND</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="fw-bold">Tiền mặt nhận</span>
                    <span id="totalCashReceived" class="text-primary fw-bold">0 VND</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="fw-bold">Chuyển khoản nhận</span>
                    <span id="totalBankReceived" class="text-primary fw-bold">0 VND</span>
                </div>
                <div class="list-group-item d-flex justify-content-between bg-warning-subtle">
                    <span class="fw-bold text-danger">Tổng thực nhận (đã trừ tiền thối)</span>
                    <span id="totalActualReceived" class="text-danger fw-bold">0 VND</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
async function fetchCashierReport() {
    let date = document.getElementById("reportDate").value;
    let url = `{{ route("staff_counter.reports.getTotal") }}?date=${date}`;

    try {
        const response = await fetch(url);
        const data = await response.json();

        // Định dạng tiền theo chuẩn Việt Nam (VND)
        const formatCurrency = (value) => new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(value);

        document.getElementById("totalOrders").innerText = data.totalOrders;
        document.getElementById("totalRevenue").innerText = formatCurrency(data.totalRevenue);
        document.getElementById("totalDiscount").innerText = formatCurrency(data.totalDiscount);
        document.getElementById("totalCashReceived").innerText = formatCurrency(data.totalCashReceived);
        document.getElementById("totalBankReceived").innerText = formatCurrency(data.totalBankReceived);
        document.getElementById("totalActualReceived").innerText = formatCurrency(data.totalActualReceived);
    } catch (error) {
        console.error("Lỗi khi tải dữ liệu thống kê:", error);
        alert("Không thể tải dữ liệu. Vui lòng thử lại!");
    }
}

// Tải dữ liệu ngay khi trang load
fetchCashierReport();
</script>
@endsection