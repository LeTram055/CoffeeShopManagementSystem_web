@extends('staff_counter.layouts.master')
@section('title', 'Đơn hàng')
@section('feature-title', 'Đơn hàng')

@section('custom-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* Các style chung */
/* Layout chia 2 cột */
.order-container {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.dinein-column {
    flex: 1 1 48%;
    border-right: 1px solid #ccc;
    padding: 1rem;
    max-height: 80vh;
    overflow-y: auto;
}

.takeaway-column {
    flex: 1 1 48%;
    padding: 1rem;
    max-height: 80vh;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .order-container {
        flex-direction: column;
    }

    .dinein-column,
    .takeaway-column {
        flex: 1 1 100%;
        border-right: none;
        max-height: none;
    }
}

.order-list {
    margin-top: 1rem;
}

.order-card {
    margin-bottom: 1rem;
}

.nav-tabs .nav-link {
    cursor: pointer;
}

/* Modal xem */
#orderDetailModal .modal-body p {
    margin-bottom: 0.5rem;
}

/* Modal sửa */
.order-edit-table input[type=number],
.order-edit-table input[type=text] {
    width: 80px;
}

.custom-tabs .nav-link {
    color: #555;
    font-weight: 500;
    border-radius: 8px 8px 0 0;
    transition: all 0.3s ease-in-out;
    padding: 6px 15px;
}

.custom-tabs .nav-link:hover {
    color: #000;
    background: #f8f9fa;
    border-color: #dee2e6 #dee2e6 transparent;
}

.custom-tabs .nav-link.active {
    color: #fff;
    background: #0049ab;
    border-color: #0049ab #0049ab transparent;
    font-weight: bold;
    box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.1);
}

.text-end {
    text-align: right !important;
}

.form-label {
    font-weight: bold;
    margin-bottom: 0.5rem;
    /* Khoảng cách giữa label và input */
}

.form-control {
    border-radius: 8px;
    /* Bo góc input */
}


.offcanvas {
    max-width: 300px;
    z-index: 200;
}

.filter-button {
    position: fixed;
    top: 70px;
    right: 20px;
    z-index: 90;
    border-radius: 40px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 5px 20px;
}

.input-group {
    flex-wrap: nowrap;
}
</style>
@endsection

@section('content')
<div class="container">

    <div id="notification" style="display:none;"></div>
    <!-- Nút mở bộ lọc -->
    <button class="btn btn-secondary filter-button" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas">
        Bộ lọc
    </button>

    <!-- Offcanvas Bộ lọc -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel"
        data-bs-backdrop="false">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filterOffcanvasLabel">Bộ lọc</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form method="GET" action="{{ route('staff_counter.confirmorder.index') }}">
                <div class="mb-3">
                    <label for="start_date" class="form-label">Từ ngày</label>
                    <input type="date" id="start_date" name="start_date" class="form-control"
                        value="{{ request('start_date', $startDate) }}">
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">Đến ngày</label>
                    <input type="date" id="end_date" name="end_date" class="form-control"
                        value="{{ request('end_date', $endDate) }}">
                </div>
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
                <a href="{{ route('staff_counter.confirmorder.index') }}" class="btn btn-secondary w-100 mt-2">Hủy bộ
                    lọc</a>
            </form>
        </div>
    </div>

    <div class="order-container">
        <!-- Đơn hàng tại chỗ -->
        <div class="dinein-column">
            <h4 class="title2 mb-3">Đơn hàng tại chỗ</h4>
            <ul class="nav nav-tabs custom-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ request('dine_in_status', 'pending_payment') == 'pending_payment' ? 'active' : '' }}"
                        href="{{ route('staff_counter.confirmorder.index', ['dine_in_status' => 'pending_payment',
                        'takeaway_status' => request('takeaway_status', 'pending_payment'),
                        'start_date' => request('start_date', $startDate),
                        'end_date' => request('end_date', $endDate),
                          ]) }}">Chờ thanh toán</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('dine_in_status') == 'received' ? 'active' : '' }}" href="{{ route('staff_counter.confirmorder.index', ['dine_in_status' => 'received',
                        'takeaway_status' => request('takeaway_status', 'pending_payment'),
                        'start_date' => request('start_date', $startDate),
                        'end_date' => request('end_date', $endDate),
                         ]) }}">
                        Đã nhận món
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('dine_in_status') == 'all' ? 'active' : '' }}" href="{{ route('staff_counter.confirmorder.index', ['dine_in_status' => 'all', 
                        'takeaway_status' => request('takeaway_status', 'pending_payment'),
                        'start_date' => request('start_date', $startDate),
                        'end_date' => request('end_date', $endDate),
                        ]) }}">Tất
                        cả</a>
                </li>
            </ul>
            <div class="order-list mt-3">
                @foreach ($dineInOrders as $order)
                <div class="card order-card">
                    <div class="card-body">
                        <h5>Bàn: {{ $order->table->table_number ?? 'Không xác định' }}</h5>
                        @php
                        $statusLabels = [
                        'pending_payment' => 'Chờ thanh toán',
                        'paid' => 'Đã thanh toán',
                        'cancelled' => 'Đã hủy',
                        'confirmed' => 'Đã xác nhận',
                        'received' => 'Đã nhận món'
                        ];
                        @endphp
                        <p>Trạng thái: <strong>{{ $statusLabels[$order->status] ?? 'Không xác định' }}</strong></p>
                        <p>Tổng tiền: {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
                        <button class="btn btn-primary btn-sm view-order-btn" data-id="{{ $order->order_id }}">Xem chi
                            tiết</button>

                        @if($order->status == 'pending_payment')

                        <button class="btn btn-warning btn-sm payment-btn" data-id="{{ $order->order_id }}">Thanh
                            toán</button>
                        @endif

                        @if($order->status == 'received')
                        <button class="btn btn-warning btn-sm provisional-invoice-btn"
                            data-id="{{ $order->order_id }}">In hóa đơn tạm tính</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Đơn hàng mang đi -->
        <div class="takeaway-column">
            <h4 class="title2 mb-3">Đơn hàng mang đi</h4>
            <ul class="nav nav-tabs custom-tabs">
                @php
                $currentTakeawayStatus = request('takeaway_status', 'pending_payment');
                @endphp

                <li class="nav-item">
                    <a class="nav-link {{ $currentTakeawayStatus == 'pending_payment' ? 'active' : '' }}" href="{{ route('staff_counter.confirmorder.index', ['takeaway_status' => 'pending_payment', 
                        'dine_in_status' => request('dine_in_status', 'pending_payment'),
                        'start_date' => request('start_date', $startDate),
                        'end_date' => request('end_date', $endDate),
                        ]) }}">
                        Chờ thanh toán
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentTakeawayStatus == 'confirmed' ? 'active' : '' }}" href="{{ route('staff_counter.confirmorder.index', ['takeaway_status' => 'confirmed', 
                        'dine_in_status' => request('dine_in_status', 'pending_payment'),
                        'start_date' => request('start_date', $startDate),
                        'end_date' => request('end_date', $endDate),
                        ]) }}">
                        Chờ nhận món
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ $currentTakeawayStatus == 'all' ? 'active' : '' }}" href="{{ route('staff_counter.confirmorder.index', ['takeaway_status' => 'all', 
                        'dine_in_status' => request('dine_in_status', 'pending_payment'),
                        'start_date' => request('start_date', $startDate),
                        'end_date' => request('end_date', $endDate),
                        ]) }}">
                        Tất cả
                    </a>
                </li>

            </ul>
            <div class="order-list mt-3">
                @foreach ($takeawayOrders as $order)
                <div class="card order-card">
                    <div class="card-body">
                        <h5>Khách hàng: {{ $order->customer->name ?? 'Không xác định' }}</h5>
                        @php
                        $statusLabels = [
                        'pending_payment' => 'Chờ thanh toán',
                        'paid' => 'Đã thanh toán',
                        'cancelled' => 'Đã hủy',
                        'confirmed' => 'Đã xác nhận',
                        'received' => 'Đã nhận món'
                        ];
                        @endphp
                        <p>Trạng thái: <strong>{{ $statusLabels[$order->status] ?? 'Không xác định' }}</strong></p>
                        <p>Tổng tiền: {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
                        <button class="btn btn-primary btn-sm view-order-btn" data-id="{{ $order->order_id }}">Xem chi
                            tiết</button>
                        @if($order->status == 'pending_payment' || $order->status == 'confirmed')
                        <button class="btn btn-success btn-sm edit-order-btn my-1"
                            data-id="{{ $order->order_id }}">Chỉnh
                            sửa</button>
                        @endif
                        @if($order->status == 'confirmed' && $order->orderItems->every(fn($item) =>
                        $item->completed_quantity == 0))
                        <button class="btn btn-danger btn-sm cancel-order-btn" data-id="{{ $order->order_id }}">Hủy
                            đơn</button>
                        @endif
                        @if($order->status == 'pending_payment')
                        <button class="btn btn-warning btn-sm payment-btn" data-id="{{ $order->order_id }}">Thanh
                            toán</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- Modal Xem chi tiết đơn hàng (read-only) -->
<div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-labelledby="paymentDetailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="paymentDetailModalLabel">Chi tiết hóa đơn</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Cột thông tin chung -->
                    <div class="col-md-6 pe-md-4">
                        <h6 class="mb-3 fw-bold" style="color: #0049ab;">Thông tin chung</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-start">Mã hóa đơn:</strong>
                                <span id="detailPaymentId" class="text-end"></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-start">Khách hàng:</strong>
                                <span id="detailCustomerName" class="text-end"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-start">Loại đơn hàng:</strong>
                                <span id="detailOrderType" class="text-end"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-start">Bàn:</strong>
                                <span id="detailTable" class="text-end"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-start" id="dynamicLabel"></strong>
                                <span id="dynamicValue" class="text-end"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-start">Thời gian đặt hàng:</strong>
                                <span id="detailOrderTime" class="text-end"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between" id="paymentTimeRow">
                                <strong class="text-start">Thời gian thanh toán:</strong>
                                <span id="detailPaymentTime" class="text-end"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between" id="paymentMethodRow">
                                <strong class="text-start">Phương thức thanh toán:</strong>
                                <span id="detailPaymentMethod" class="text-end"></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Cột thông tin thanh toán (ẩn nếu chưa thanh toán) -->
                    <div class="col-md-6 ps-md-4" id="paymentInfoColumn" style="display: none;">
                        <h6 class="mb-3 fw-bold" style="color: #0049ab;">Thông tin thanh toán</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-start">Mã thanh toán:</strong>
                                <span id="detailPaymentCode" class="text-end"></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-start">Khuyến mãi:</strong>
                                <span id="detailPromotion" class="text-end"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-start">Tổng tiền:</strong>
                                <span id="detailTotalPrice" class="text-end"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-start">Tiền giảm giá:</strong>
                                <span id="detailDiscountAmount" class="text-end"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-start">Tiền thanh toán:</strong>
                                <span id="detailFinalPrice" class="text-end"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-start">Tổng đã nhận:</strong>
                                <span id="detailAmountReceived" class="text-end"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-start">Tiền thừa:</strong>
                                <span id="detailChange" class="text-end"></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr>
                <!-- Chi tiết sản phẩm -->
                <h6 class="mb-3 fw-bold" style="color: #0049ab;">Chi tiết sản phẩm đã mua</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th class="text-center">Tên sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Giá</th>
                            </tr>
                        </thead>
                        <tbody id="detailOrderItems">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="viewInvoiceButton" style="display: none;">Xem hóa
                    đơn</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chỉnh sửa đơn hàng (cho đơn hàng mang đi confirmed) -->
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">

        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="editOrderModalLabel">Chỉnh sửa đơn hàng</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form id="editOrderForm">
                    <input type="hidden" id="editOrderId" name="order_id">
                    <div id="orderEditItemsContainer">
                        <!-- Bảng chỉnh sửa đơn hàng, danh sách món ăn sẽ được load qua AJAX -->
                    </div>
                    <button type="button" id="addItemBtn" class="btn btn-sm btn-success mt-2">Thêm món</button>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Lưu chỉnh sửa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Thêm Sản phẩm -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Chọn sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <!-- Khung tìm kiếm sản phẩm -->
                <div class="mb-3">
                    <input type="text" id="productSearch" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                </div>
                <!-- Container hiển thị danh sách sản phẩm-->
                <div id="itemGrid" class="row"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Thanh toán đơn hàng -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="paymentModalLabel">Thanh toán đơn hàng</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    @csrf
                    <input type="hidden" id="paymentOrderId" name="order_id">
                    <input type="hidden" id="paymentOrderType" name="order_type" value="">
                    <input type="hidden" id="paymentTotal" name="payment_total" value="0" data-original="0">
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Phương thức thanh toán</label>
                        <select id="paymentMethod" name="payment_method" class="form-select" required>
                            <option value="cash">Tiền mặt</option>
                            <option value="bank_transfer">Chuyển khoản</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="promotionSelectPayment" class="form-label">Khuyến mãi</label>
                        <select id="promotionSelectPayment" name="promotion_id" class="form-select">
                            <option value="">-- Chọn khuyến mãi --</option>
                            <!-- Danh sách khuyến mãi sẽ được load qua AJAX -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amountReceived" class="form-label">Tiền khách đưa</label>
                        <input type="number" id="amountReceived" name="amount_received" class="form-control" required>
                        <p id="amountError" class="text-danger" style="display: none;"></p>
                    </div>

                    <div class="mb-3">
                        <p>Tiền phải trả: <span id="finalAmount">0</span> VNĐ</p>
                    </div>
                    <div class="mb-3">
                        <p>Tiền thối: <span id="changeAmountPayment">0</span> VNĐ</p>
                    </div>
                    <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hóa Đơn -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Hóa đơn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <!-- Thêm iframe để hiển thị PDF -->
                <iframe id="invoiceIframe" src="" style="width: 100%; height: 600px; border: none;"
                    frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmAndPrintInvoice">Xác nhận và in hóa
                    đơn</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận thanh toán -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-labelledby="confirmPaymentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="confirmPaymentModalLabel">Xác nhận thanh toán</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <p>Vui lòng xác nhận rằng bạn đã nhận đủ tiền từ phục vụ.</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="confirmReceivedMoney">
                    <label class="form-check-label" for="confirmReceivedMoney">
                        Tôi xác nhận đã nhận đủ tiền.
                    </label>
                </div>
                <p id="confirmError" class="text-danger mt-2" style="display: none;">Bạn cần xác nhận trước khi tiếp
                    tục.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirmPaymentButton">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.view-order-btn').on('click', function() {
        let orderId = $(this).data('id');
        $.ajax({
            url: `/staff_counter/confirmorder/${orderId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let order = response.order;

                // Cập nhật thông tin chung
                $('#detailPaymentId').text(order.order_id);

                $('#detailCustomerName').text(order.customer ? order.customer.name : 'N/A');
                $('#detailOrderType').text(order.order_type === 'dine_in' ? 'Tại chỗ' :
                    'Mang đi');
                $('#detailTable').text(order.table ? 'Bàn ' + order.table.table_number :
                    '');
                $('#detailOrderTime').text(new Date(order.created_at).toLocaleString(
                    'vi-VN'));


                // Kiểm tra trạng thái thanh toán
                if (order.status === 'paid') {
                    let payment = order.payments[0];

                    // Nếu đã thanh toán, hiển thị nhân viên thanh toán
                    $('#dynamicLabel').text('Nhân viên thanh toán:');
                    $('#dynamicValue').text(payment.employee ? payment.employee.name :
                        'N/A');

                    $('#detailPaymentCode').text(payment.payment_id || '');
                    $('#detailPaymentTime').text(new Date(payment.payment_time)
                        .toLocaleString('vi-VN'));
                    $('#detailPaymentMethod').text(payment.payment_method === 'cash' ?
                        'Tiền mặt' : 'Chuyển khoản');

                    // Hiển thị các hàng liên quan đến thanh toán
                    $('#paymentTimeRow').attr('style', 'display: flex !important;');
                    $('#paymentMethodRow').attr('style', 'display: flex !important;');


                    $('#paymentInfoColumn').show(); // Hiển thị cột thông tin thanh toán

                    $('#detailPromotion').text(payment.promotion ? payment.promotion.name :
                        'Không có');
                    $('#detailTotalPrice').text(new Intl.NumberFormat('vi-VN').format(order
                        .total_price) + ' VNĐ');
                    $('#detailDiscountAmount').text(new Intl.NumberFormat('vi-VN').format(
                        payment.discount_amount) + ' VNĐ');
                    $('#detailFinalPrice').text(new Intl.NumberFormat('vi-VN').format(
                        payment
                        .final_price) + ' VNĐ');
                    $('#detailAmountReceived').text(new Intl.NumberFormat('vi-VN').format(
                        payment.amount_received) + ' VNĐ');
                    $('#detailChange').text(new Intl.NumberFormat('vi-VN').format(payment
                        .amount_received - payment.final_price) + ' VNĐ');

                    // Hiển thị nút "Xem hóa đơn"
                    $('#viewInvoiceButton').show().off('click').on('click', function() {
                        let invoiceUrl =
                            `/staff_counter/confirmorder/print-invoice/${orderId}`;
                        window.open(invoiceUrl, '_blank');
                    });
                } else {
                    // Nếu chưa thanh toán, hiển thị tổng tiền đơn hàng
                    $('#dynamicLabel').text('Tổng tiền đơn hàng:');
                    $('#dynamicValue').text(new Intl.NumberFormat('vi-VN').format(order
                        .total_price) + ' VNĐ');
                    $('#paymentInfoColumn').hide(); // Ẩn cột thông tin thanh toán
                    $('#viewInvoiceButton').hide();

                    // Ẩn các hàng liên quan đến thanh toán
                    $('#paymentTimeRow').attr('style', 'display: none !important;');
                    $('#paymentMethodRow').attr('style', 'display: none !important;');

                }

                // Cập nhật chi tiết sản phẩm
                let itemsHtml = '';
                order.order_items.forEach(function(item) {
                    itemsHtml += `
                        <tr>
                            <td class = "text-center">${item.item.name}</td>
                            <td class="text-center">${item.quantity}</td>
                            <td class = "text-center">${new Intl.NumberFormat('vi-VN').format(item.item.price)} VNĐ</td>
                        </tr>`;
                });
                $('#detailOrderItems').html(itemsHtml);

                // Hiển thị modal
                $('#paymentDetailModal').modal('show');
            },
            error: function() {
                alert('Lỗi khi tải thông tin hóa đơn.');
            }
        });
    });
});

$(document).ready(function() {
    // Hàm hiển thị thông báo
    function notify(message, type = 'success') {
        let alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                         </div>`;
        $('#notification').html(alertHtml).fadeIn();

        setTimeout(function() {
            $('#notification').fadeOut();
        }, 3000);
    }


    // Sự kiện click cho nút in hóa đơn được thêm vào modal chi tiết (nếu đơn hàng đã thanh toán)
    $(document).on('click', '.print-invoice-btn', function() {
        let orderId = $(this).data('id');
        $('#orderDetailModal').modal('hide');
        window.open('/staff_counter/confirmorder/print-invoice/' + orderId, '_blank');
    });

    let originalTotalQuantity = 0;

    //Khi nhấn nút "Chỉnh sửa" cho đơn hàng mang đi confirmed
    $('.edit-order-btn').on('click', function() {
        let orderId = $(this).data('id');
        $.ajax({
            url: `/staff_counter/confirmorder/${orderId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let order = response.order;
                $('#editOrderId').val(order.order_id);
                let html = `<p><strong>Mã hóa đơn:</strong> ${order.order_id}</p>`;
                html +=
                    `<p><strong>Khách hàng:</strong> ${order.customer ? order.customer.name : 'N/A'}</p>`;
                html +=
                    `<p><strong>Ngày đặt:</strong> ${new Date(order.created_at).toLocaleString('vi-VN')}</p>`;
                html +=
                    `<p><strong>Tổng tiền:</strong> ${new Intl.NumberFormat('vi-VN').format(order.total_price)} VNĐ</p>`;
                if (order.order_items.length > 0) {
                    html += `<h6>Chi tiết đơn hàng:</h6>`;
                    html +=
                        `<table class="table table-bordered order-edit-table">
                            <thead>
                                <tr>
                                    <th class="text-center">Tên món</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-center">Giá tiền</th>
                                    <th class="text-center">Ghi chú</th>
                                    <th class="text-center">Xóa</th>
                                </tr>
                            </thead>
                        <tbody>`;
                    order.order_items.forEach(function(item, index) {
                        originalTotalQuantity += item.quantity;
                        html += `<tr data-index="${index}" data-item-id="${item.item.item_id}" data-original-quantity="${item.quantity}">
                            <td>${item.item.name}</td>
                            <td class="text-center">
                                <div class="input-group input-group-sm justify-content-center" style="min-width: 100px">
                                <button class="btn btn-outline-secondary btn-decrease" type="button">−</button>
                                <input type="number" class="form-control quantity-input" value="${item.quantity}" data-index="${index}" data-completed-quantity="${item.completed_quantity}" style="max-width: 50px; text-align: center;">
                                <button class="btn btn-outline-secondary btn-increase" type="button">+</button>
                                </div>
                            </td>
                            <td class="text-end">${new Intl.NumberFormat('vi-VN').format(item.item.price)} VNĐ</td>
                            <td><input type="text" class="form-control note-input" value="${item.note ?? ''}" data-item-id="${item.item.item_id}" placeholder="Ghi chú..." style="width: 100%;" ${item.quantity === item.completed_quantity ? 'readonly' : ''}></td>
                            <td class="text-center">${item.completed_quantity > 0 ? '' : `<button class="btn btn-sm btn-danger remove-edit-item" data-item-id="${item.item.item_id}"><i class="fa-solid fa-trash"></i></button>`}</td>
                        </tr>`;
                    });
                    html += `</tbody></table>`;
                } else {
                    html += `<p>Không có sản phẩm nào.</p>`;
                }
                $('#orderEditItemsContainer').html(html);
                $('#editOrderModal').modal('show');
            },
            error: function() {
                notify('Lỗi khi tải đơn hàng để chỉnh sửa.', 'danger');
            }
        });
    });

    // Sự kiện giảm số lượng
    $(document).on('click', '.btn-decrease', function() {
        let input = $(this).closest('.input-group').find('.quantity-input');
        let currentVal = parseInt(input.val()) || 0;
        let completedQuantity = parseInt(input.data('completed-quantity')) || 1;
        if (currentVal > completedQuantity) {
            input.val(currentVal - 1).trigger('input');
        }
    });

    // Sự kiện tăng số lượng
    $(document).on('click', '.btn-increase', function() {
        let input = $(this).closest('.input-group').find('.quantity-input');
        let currentVal = parseInt(input.val()) || 0;
        input.val(currentVal + 1).trigger('input');
    });

    // Sự kiện kiểm soát số lượng nhập
    $(document).on('input', '.quantity-input', function() {
        let input = $(this);
        let currentVal = parseInt(input.val()) || 0;
        let completedQuantity = parseInt(input.data('completed-quantity')) || 1;

        // Nếu số lượng nhập nhỏ hơn số lượng đã hoàn thành, đặt lại giá trị bằng số lượng đã hoàn thành
        if (currentVal < completedQuantity) {
            input.val(completedQuantity);
        }

        // Cho phép chỉnh sửa ghi chú nếu số lượng thay đổi
        let noteInput = input.closest('tr').find('.note-input');
        if (currentVal !== completedQuantity) {

            noteInput.prop('readonly', false);
        } else {

            noteInput.prop('readonly', true);
        }
    });

    // Sự kiện xóa dòng sản phẩm khi nhấn nút "Xóa"
    $(document).on('click', '.remove-edit-item', function() {
        $(this).closest('tr').remove();
        // if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')) {
        //     $(this).closest('tr').remove();
        // }
    });



    // Xử lý lưu chỉnh sửa đơn hàng
    $('#editOrderForm').on('submit', function(e) {
        e.preventDefault();
        let orderId = $('#editOrderId').val();
        let items = [];
        let hasChanges = false;
        let currentTotalQuantity = 0;
        $('#orderEditItemsContainer table.order-edit-table tbody tr').each(function() {
            let itemId = $(this).data('item-id');
            let originalQuantity = parseInt($(this).data('original-quantity')) || 0;
            let quantity = $(this).find('.quantity-input').val();
            let note = $(this).find('.note-input').val();

            currentTotalQuantity += parseInt(quantity);

            // Kiểm tra nếu có sự thay đổi
            if (parseInt(quantity) !== originalQuantity) {
                console.log('Quantity changed for item ID:', itemId);
                hasChanges = true;
            }
            // Nếu số lượng sản phẩm > 0 thì thêm vào danh sách items
            if (parseInt(quantity) > 0) {
                items.push({
                    id: itemId,
                    quantity: quantity,
                    note: note
                });
            }
        });

        // So sánh tổng số lượng trước và sau
        if (currentTotalQuantity !== originalTotalQuantity) {
            hasChanges = true;
        }

        console.log('Has changes:', hasChanges);
        console.log('currentTotalQuantity:', currentTotalQuantity);
        console.log('originalTotalQuantity:', originalTotalQuantity);

        // Nếu không có thay đổi, hiển thị thông báo và dừng lại
        if (hasChanges === false) {
            notify('Không có thay đổi nào để cập nhật.', 'info');
            $('#editOrderModal').modal('hide');
            return;
        }

        if (items.length === 0) {
            notify('Vui lòng chọn ít nhất một sản phẩm để chỉnh sửa đơn hàng.', 'danger');
            $('#editOrderModal').modal('hide');
            return;
            // Dừng lại và không gửi yêu cầu lưu đơn hàng
        }
        $.ajax({
            url: `/staff_counter/confirmorder/update-takeaway/${orderId}`,
            type: 'POST',
            data: {
                items: items,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                notify(response.message, 'success');
                $('#editOrderModal').modal('hide');
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;

                let errorHtml = '';
                if (Array.isArray(errors)) {
                    // Trường hợp lỗi là mảng thông báo đơn thuần
                    errorHtml = errors.join('<br>');
                } else if (typeof errors === 'object') {
                    // Trường hợp lỗi validation theo field
                    $.each(errors, function(key, messages) {
                        errorHtml += messages.join('<br>') + '<br>';
                    });
                } else {
                    // Một lỗi không xác định
                    errorHtml = 'Đã xảy ra lỗi không xác định.';
                }


                notify(errorHtml, 'danger');
                $('#editOrderModal').modal('hide');

            }
        });
    });

    // Load danh sách sản phẩm khi mở modal Thêm sản phẩm
    let itemsList = []; // Biến toàn cục để lưu danh sách sản phẩm
    function loadItems() {
        $.ajax({
            url: '/staff_counter/confirmorder/menuitem',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                itemsList =
                    data;
                renderGrid(itemsList);
            },
            error: function() {
                alert('Lỗi khi tải danh sách sản phẩm.');
            }
        });
    }

    // Hàm hiển thị sản phẩm
    function renderGrid(items) {
        let gridHtml = '';
        $.each(items, function(i, item) {
            gridHtml += `
                <div class="col-6 col-md-4 mb-3">
                    <div class="card item-card" data-item-id="${item.item_id}">
                        <img src="/storage/uploads/${item.image_url}" class="card-img-top" alt="${item.name}" style="height: 100px; object-fit: cover;">
                        <div class="card-body p-2 text-center">
                            <h6 class="card-title">${item.name}</h6>
                            <p class="card-text">${new Intl.NumberFormat('vi-VN').format(item.price)} đ</p>
                            <button class="btn btn-sm btn-success select-item-btn" data-item-id="${item.item_id}">Chọn</button>
                        </div>
                    </div>
                </div>
            `;
        });
        $('#itemGrid').html(gridHtml);
    }

    // Khung tìm kiếm sản phẩm
    $('#productSearch').on('keyup', function() {
        let searchTerm = $(this).val().toLowerCase();
        let filteredItems = itemsList.filter(function(item) {
            return item.name.toLowerCase().indexOf(searchTerm) > -1;
        });
        renderGrid(filteredItems);
    });

    // Khi nhấn nút "Thêm món" trong modal chỉnh sửa đơn hàng, mở modal "Thêm sản phẩm"
    $('#addItemBtn').on('click', function() {
        loadItems();
        $('#addItemModal').modal('show');
    });

    // Khi người dùng nhấn nút "Chọn" trên một sản phẩm trong grid
    $(document).on('click', '.select-item-btn', function() {
        let itemId = $(this).data('item-id');
        // Tìm thông tin sản phẩm từ itemsList
        let item = itemsList.find(p => p.item_id == itemId);
        if (!item) {
            alert('Sản phẩm không tồn tại.');
            return;
        }
        let quantity = 1;
        let note = '';
        // Kiểm tra nếu sản phẩm đã có trong bảng, thì tăng số lượng
        let existingRow = $('#orderEditItemsContainer table.order-edit-table tbody tr[data-item-id="' +
            item.item_id + '"]');
        if (existingRow.length > 0) {
            let qtyInput = existingRow.find('.quantity-input');
            let currentQty = parseInt(qtyInput.val()) || 0;
            qtyInput.val(currentQty + quantity).trigger('change');
        } else {
            // Tính chỉ số dòng hiện tại để đặt giá trị data-index
            let index = $('#orderEditItemsContainer table.order-edit-table tbody tr').length;
            let newRow = `<tr data-item-id="${item.item_id}">
                <td>${item.name}</td>
                <td class="text-center">
                    <div class="input-group input-group-sm justify-content-center">
                        <button class="btn btn-outline-secondary btn-decrease" type="button">−</button>
                        <input type="number" class="form-control quantity-input" value="${quantity}" data-index="${index}" style="max-width: 50px; text-align: center;">
                        <button class="btn btn-outline-secondary btn-increase" type="button">+</button>
                    </div>
                </td>
                <td class="text-end">${new Intl.NumberFormat('vi-VN').format(item.price)} VNĐ
                </td>
                <td><input type="text" class="form-control note-input" value="${note}" data-item-id="${item.item_id}" placeholder="Ghi chú..." style="width: 100%;"></td>
                <td class="text-center"><button class="btn btn-sm btn-danger remove-edit-item" data-item-id="${item.item_id}"><i class="fa-solid fa-trash"></i></button></td>
            </tr>`;
            if ($('#orderEditItemsContainer table.order-edit-table tbody').length === 0) {
                $('#orderEditItemsContainer').html(
                    '<table class="table table-bordered order-edit-table"><thead><tr><th>Tên món</th><th class="text-center">Số lượng</th><th class="text-center">Giá tiền</th><th>Ghi chú</th><th class="text-center">Hành động</th></tr></thead><tbody></tbody></table>'
                );
            }
            $('#orderEditItemsContainer table.order-edit-table tbody').append(newRow);
        }
        $('#addItemModal').modal('hide');
    });

    // Xử lý nút hủy đơn hàng
    $(document).on('click', '.cancel-order-btn', function() {
        let orderId = $(this).data('id');
        if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')) {
            $.ajax({
                url: `/staff_counter/confirmorder/cancel/${orderId}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    notify(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function() {
                    notify('Lỗi khi hủy đơn hàng.', 'danger');
                }
            });
        }
    });

    // Khi nhấn nút "Thanh toán"
    $('.payment-btn').on('click', function() {
        let orderId = $(this).data('id');
        // Lưu order_id vào hidden input
        $('#paymentOrderId').val(orderId);
        $.ajax({
            url: `/staff_counter/confirmorder/${orderId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let order = response.order;
                // Lưu tổng tiền gốc
                $('#paymentTotal').val(order.total_price).data('original', order
                    .total_price);
                $('#finalAmount').text(new Intl.NumberFormat('vi-VN').format(order
                    .total_price));
                // Kiểm tra loại đơn hàng
                if (order.order_type === 'dine_in') {
                    // Dành cho đơn hàng tại chỗ: lưu type và load modal hóa đơn ngay
                    $('#paymentOrderType').val('dine_in');
                    // Xây dựng URL hóa đơn PDF (ẩn toolbar, navpanes, scrollbar nếu cần)
                    let invoiceUrl = '/staff_counter/confirmorder/print-invoice/' +
                        orderId + '#toolbar=0&navpanes=0&scrollbar=0';
                    $('#invoiceIframe').attr('src', invoiceUrl);
                    // Nếu modal thanh toán đang mở thì ẩn nó (trường hợp đã gọi trước đó)
                    $('#paymentModal').modal('hide');
                    // Hiển thị modal hóa đơn
                    $('#invoiceModal').modal('show');
                } else {
                    // Đơn hàng mang đi: lưu type và hiện modal thanh toán
                    $('#paymentOrderType').val('takeaway');
                    // Lấy danh sách khuyến mãi, … (mã hiện tại)
                    $.ajax({
                        url: `/staff_counter/confirmorder/eligible-promotions/${orderId}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(promotions) {
                            let options =
                                '<option value="">-- Chọn khuyến mãi --</option>';
                            promotions.forEach(function(promotion) {
                                options += `<option value="${promotion.promotion_id}" data-discount="${promotion.effective_discount}" data-type="${promotion.discount_type}">
                                            ${promotion.name} - Giảm: ${promotion.discount_value}${promotion.discount_type === 'percentage' ? '%' : 'đ'} (Đơn tối thiểu: ${new Intl.NumberFormat('vi-VN').format(promotion.min_order_value)} đ)
                                        </option>`;
                            });
                            $('#promotionSelectPayment').html(options);
                        },
                        error: function() {
                            notify('Lỗi khi tải danh sách khuyến mãi.',
                                'danger');
                        }
                    });
                    $('#paymentModal').modal('show');
                }
            },
            error: function() {
                notify('Lỗi khi tải thông tin đơn hàng.', 'danger');
            }
        });
    });

    // Khi chọn khuyến mãi, cập nhật tổng tiền thanh toán
    $('#promotionSelectPayment').on('change', function() {
        let selectedOption = $(this).find(':selected');
        // Lấy giá trị giảm
        let discount = parseFloat(selectedOption.data('discount')) || 0;
        let discountType = selectedOption.data('type') || 'fixed';
        // Lấy tổng tiền gốc (original total) từ data attribute (được gán khi nhấn nút Thanh toán)
        let originalTotal = parseFloat($('#paymentTotal').data('original')) || parseFloat($(
            '#paymentTotal').val());
        let newTotal = originalTotal;
        newTotal = originalTotal - discount;
        // Đảm bảo tổng tiền không âm
        newTotal = newTotal < 0 ? 0 : newTotal;
        console.log(newTotal);
        // Cập nhật hidden input và hiển thị dòng "Tiền phải trả"
        $('#paymentTotal').val(newTotal);
        $('#finalAmount').text(new Intl.NumberFormat('vi-VN').format(newTotal));
    });

    // Tính tiền thối khi nhập số tiền khách đưa
    $('#amountReceived').on('input', function() {
        let amountReceived = parseFloat($(this).val()) || 0;
        let total = parseFloat($('#paymentTotal').val()) || 0;
        let change = amountReceived - total;
        // Hiển thị tiền thối (nếu hợp lệ)
        $('#changeAmountPayment').text(new Intl.NumberFormat('vi-VN').format(change >= 0 ? change : 0));

        // Kiểm tra nếu số tiền khách đưa nhỏ hơn tổng tiền phải trả
        if (amountReceived < total) {
            $('#amountError').text('Số tiền khách đưa không đủ.').show();
        } else {
            $('#amountError').hide();
        }

    });

    //Khi nhấn in hóa đơn tạm thời
    $(document).on('click', '.provisional-invoice-btn', function() {
        let orderId = $(this).data('id');
        window.open(`/staff_counter/confirmorder/print-provisional-invoice/${orderId}`, '_blank');
    });

    // Xử lý thanh toán qua form
    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: '/staff_counter/confirmorder/payment_takeaway',
            type: 'POST',
            data: formData,
            success: function(response) {
                notify(response.message, 'success');

                $('#paymentModal').modal('hide');
                let orderId = $('#paymentOrderId').val();
                // Xây dựng URL hóa đơn PDF
                let invoiceUrl = '/staff_counter/confirmorder/print-invoice/' + orderId +
                    '#toolbar=0&navpanes=0&scrollbar=0';
                $('#invoiceIframe').attr('src', invoiceUrl);

                $('#invoiceModal').modal('show');
            },
            error: function(xhr) {
                notify('Lỗi khi thanh toán đơn hàng.', 'danger');
            }
        });
    });


    $('#confirmAndPrintInvoice').on('click', function() {
        $('#invoiceModal').modal('hide');
        let orderId = $('#paymentOrderId').val();
        let orderType = $('#paymentOrderType').val();
        if (orderType === 'dine_in') {
            // Đơn tại chỗ: hiển thị modal xác nhận
            $('#confirmPaymentModal').modal('show');

            // Xử lý khi nhấn nút "Xác nhận" trong modal
            $('#confirmPaymentButton').off('click').on('click', function() {
                if (!$('#confirmReceivedMoney').is(':checked')) {
                    $('#confirmError').show(); // Hiển thị lỗi nếu chưa tick checkbox
                    return;
                }

                $('#confirmError').hide(); // Ẩn lỗi nếu đã tick checkbox
                $('#confirmPaymentModal').modal('hide'); // Ẩn modal xác nhận

                // Đơn tại chỗ: cập nhật trạng thái thanh toán và in hóa đơn
                $.ajax({
                    url: `/staff_counter/confirmorder/mark-paid/${orderId}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        notify(response.message, 'success');
                        let invoiceUrl =
                            '/staff_counter/confirmorder/print-invoice/' + orderId;
                        let printWindow = window.open(invoiceUrl, '_blank');
                        printWindow.focus();
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function() {
                        notify('Lỗi khi cập nhật trạng thái đơn hàng.', 'danger');
                    }
                });
            });
        } else {
            // Đơn takeaway: đơn hàng đã được cập nhật thanh toán thông qua form
            let invoiceUrl = '/staff_counter/confirmorder/print-invoice/' + orderId;
            let printWindow = window.open(invoiceUrl, '_blank');
            printWindow.focus();
            setTimeout(function() {
                location.reload();
            }, 1000);
        }
    });

});
</script>
@endsection