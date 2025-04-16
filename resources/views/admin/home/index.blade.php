@extends('admin.layouts.master')

@section('title', 'Trang Tổng Quan')
@section('feature-title', 'Tổng quan')

@section('custom-css')
<style>
.card-custom {
    position: relative;
    background: white;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.5);
    padding-top: 30px;
    overflow: visible;
    border: none;

    width: 250px;
    height: 140px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

}

.icon-badge {
    position: absolute;
    top: -15px;
    left: -15px;
    background: white;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    padding: 10px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    z-index: 10;
}

.icon-badge i {
    font-size: 1.5rem;
}

.text-color {
    color: #0049ab !important;
}
</style>

@endsection

@section('content')

<div class="container mt-4">

    <div class="row text-center mt-4 d-flex flex-wrap justify-content-between">
        <!-- Tổng số khách hàng -->
        <div class="col-auto mb-4">
            <div class="card card-custom">
                <div class="icon-badge text-primary"><i class="fas fa-users"></i></div>
                <div class="card-body pt-1">
                    <h5 class="card-title text-primary">Khách hàng</h5>
                    <p class="card-text display-6 text-primary fw-bold mb-2">{{ $totalCustomers }}</p>
                </div>
            </div>
        </div>

        <!-- Tổng số nhân viên -->
        <div class="col-auto mb-4">
            <div class="card card-custom">
                <div class="icon-badge text-success"><i class="fas fa-user-tie"></i></div>
                <div class="card-body pt-1">
                    <h5 class="card-title text-success">Nhân viên</h5>
                    <p class="card-text display-6 text-success fw-bold mb-2">{{ $totalEmployees }}</p>
                </div>
            </div>
        </div>

        <!-- Tổng số bàn -->
        <div class="col-auto mb-4">
            <div class="card card-custom">
                <div class="icon-badge text-warning"><i class="fas fa-chair"></i></div>
                <div class="card-body pt-1">
                    <h5 class="card-title text-warning">Bàn</h5>
                    <p class="card-text display-6 text-warning fw-bold mb-2">{{ $totalTables }}</p>
                </div>
            </div>
        </div>

        <!-- Tổng nguyên liệu -->
        <div class="col-auto mb-4">
            <div class="card card-custom">
                <div class="icon-badge text-danger"><i class="fas fa-seedling"></i></div>
                <div class="card-body pt-1">
                    <h5 class="card-title text-danger">Nguyên liệu</h5>
                    <p class="card-text display-6 text-danger fw-bold mb-2">{{ $totalIngredients }}</p>
                </div>
            </div>
        </div>

        <!-- Tổng món ăn -->
        <div class="col-auto mb-4">
            <div class="card card-custom">
                <div class="icon-badge text-info"><i class="fas fa-utensils"></i></div>
                <div class="card-body pt-1">
                    <h5 class="card-title text-info">Món ăn</h5>
                    <p class="card-text display-6 text-info fw-bold mb-2">{{ $totalMenuItems }}</p>
                </div>
            </div>
        </div>

        <!-- Tổng đơn hàng -->
        <div class="col-auto mb-4">
            <div class="card card-custom">
                <div class="icon-badge text-secondary"><i class="fas fa-shopping-cart"></i></div>
                <div class="card-body pt-1">
                    <h5 class="card-title text-secondary">Đơn hàng hôm nay</h5>
                    <p class="card-text display-6 text-secondary fw-bold mb-2">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>

        <!-- Khuyến mãi hợp lệ -->
        <div class="col-auto mb-4">
            <div class="card card-custom">
                <div class="icon-badge text-dark"><i class="fas fa-gift"></i></div>
                <div class="card-body pt-1">
                    <h5 class="card-title text-dark">Khuyến mãi hợp lệ</h5>
                    <p class="card-text display-6 text-dark fw-bold mb-2">{{ $totalValidPromotions }}</p>
                </div>
            </div>
        </div>

        <!-- Tổng doanh thu hôm nay -->
        <div class="col-auto mb-4">
            <div class="card card-custom">
                <div class="icon-badge text-color"><i class="fas fa-dollar-sign"></i></div>
                <div class="card-body pt-1">
                    <h5 class="card-title text-color">Doanh thu hôm nay</h5>
                    <p class="card-text display-6 text-color fw-bold text-wrap mb-2">
                        {{ number_format($totalRevenueToday, 0, ',', '.') }}Đ
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- Danh sách 5 món bán chạy nhất -->
    <div class="row mb-4 mt-2">
        <div class="col-md-12">
            <div class="card shadow-lg border-2">
                <div class="card-body">
                    <h5 class="card-title text-center text-color"><i class="fas fa-star"></i> 5 Món Bán Chạy Nhất Hôm
                        Nay
                    </h5>
                    <table class="table table-hover">
                        <thead class="bg-primary text-white text-center">
                            <tr>
                                <th>#</th>
                                <th>Tên món</th>
                                <th>Số lượng đã bán</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($topSellingItems as $index => $item)
                            <tr>
                                <td class="fw-bold">{{ $index + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td class="fw-bold text-color">{{ $item->total_sold }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection