@extends('admin.layouts.master')

@section('title')
Thống kê lượt bán sản phẩm
@endsection

@section('feature-title')
Thống kê lượt bán sản phẩm
@endsection

@section('content')
<div class="container">
    <div class="row mb-4 d-flex justify-content-center">
        <div class="col-md-2 d-flex align-items-end">
            <a class="btn btn-outline-secondary w-100" href={{ route('admin.reports.bestSellingPage') }}>Làm
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
            <button class="btn btn-bg w-100" onclick="fetchBestSellingProducts()">Lọc</button>
        </div>
    </div>

    <table class="table table-striped table-hover my-4">
        <thead>
            <tr>
                <th class="text-center">Số thứ tự</th>
                <th class="text-center">Tên sản phẩm</th>
                <th class="text-center">Số lượng bán</th>
                <th class="text-center">Giá tiền</th>
                <th class="text-center">Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody id="bestSellingTable" class="table-group-divider">
            <tr>
                <td colspan="5" class="text-center">Đang tải...</td>
            </tr>
        </tbody>
    </table>
</div>



@endsection

@section('custom-scripts')
<script>
// Thiết lập ngày bắt đầu và kết thúc mặc định là tháng hiện tại
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


async function fetchBestSellingProducts() {
    let fromDate = document.getElementById("fromDate").value;
    let toDate = document.getElementById("toDate").value;

    // Nếu chỉ có từ ngày mà không có đến ngày, thì lấy đến ngày là hôm nay
    if (fromDate && !toDate) {
        toDate = new Date().toISOString().split("T")[0]; // Lấy ngày hiện tại
    }

    // Nếu chỉ có đến ngày mà không có từ ngày, thì bỏ qua bộ lọc
    if (!fromDate && toDate) {
        fromDate = "";
    }

    let url = `{{ route("admin.reports.bestSelling") }}`;
    if (fromDate || toDate) {
        url += `?from_date=${fromDate}&to_date=${toDate}`;
    }

    const response = await fetch(url);
    const products = await response.json();

    let tableBody = "";
    if (products.length > 0) {
        const formatter = new Intl.NumberFormat("vi-VN", {
            minimumFractionDigits: 0,
        });

        products.forEach((product, index) => {
            const revenue = product.total_sold * product.item.price;
            tableBody += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td class="text-center">${product.item.name}</td>
                    <td class="text-center">${product.total_sold}</td>
                    <td class="text-center">${formatter.format(product.item.price)} VNĐ</td>
                    <td class="text-center">${formatter.format(revenue)} VNĐ</td>
                </tr>
            `;
        });
    } else {
        tableBody = `<tr><td colspan="5" class="text-center">Không có dữ liệu</td></tr>`;
    }

    document.getElementById("bestSellingTable").innerHTML = tableBody;
}

// Gọi khi trang load
fetchBestSellingProducts();
</script>
@endsection