@extends('admin.layouts.master')

@section('title')
Thống kê sản phẩm bán chạy
@endsection

@section('feature-title')
Thống kê sản phẩm bán chạy
@endsection

@section('content')
<div class="container">
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
            <button class="btn btn-bg w-100" onclick="fetchBestSellingProducts()">Lọc</button>
        </div>
    </div>

    <table class="table table-striped table-hover">
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
            style: "currency",
            currency: "VND"
        });

        products.forEach((product, index) => {
            const revenue = product.total_sold * product.item.price;
            tableBody += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${product.item.name}</td>
                    <td class="text-center">${product.total_sold}</td>
                    <td class="text-center">${formatter.format(product.item.price)}</td>
                    <td class="text-center">${formatter.format(revenue)}</td>
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