@extends('admin/layouts/master')

@section('custom-css')
<style>
.modal-dialog-centered {
    display: flex;
    align-items: center;
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
}

.list-group-item {
    border: none;
    padding: 0.5rem 0;
    background-color: transparent;
}

.modal-body {
    padding: 1.5rem;
}
</style>
@section('title')
Quản lý hóa đơn
@endsection

@section('feature-title')
Quản lý hóa đơn
@endsection

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="btn-close"
            data-bs-dismiss="alert" aria-label="Close"></p>
    @endif
    @endforeach
</div>


<form method="GET" action="{{ route('admin.payment.index') }}"
    class="row g-2 mb-3 align-items-center justify-content-center">
    <div class="col-md-12 col-lg-3">
        <!-- Nhóm dropdown và nút reload -->
        <div class="d-flex">
            <div class="dropdown w-100">
                <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="dateDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Lọc theo ngày
                </button>
                <div class="dropdown-menu p-3" aria-labelledby="dateDropdown">
                    <div class="mb-2">
                        <label for="start_date" class="form-label">Từ ngày:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="mb-2">
                        <label for="end_date" class="form-label">Đến ngày:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="{{ request('end_date') }}">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Áp dụng</button>
                </div>
            </div>

            <a href="{{ route('admin.payment.index') }}" class="btn btn-outline-secondary ms-2" title="Xóa bộ lọc">
                <i class="fa-solid fa-rotate-right"></i>
            </a>
        </div>
    </div>
    <!-- Khung tìm kiếm -->
    <div class="col-md-12 col-lg-4">
        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm hóa đơn..."
            value="{{ request('search') }}">
    </div>
    <div class="col-md-12 col-lg-2">
        <button class="btn btn-bg rounded " type="submit">Tìm kiếm</button>
    </div>
</form>





<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.payment.exportExcel') }}" class="btn btn-success">Xuất Excel</a>
</div>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'payment_id', 'sort_direction' => $sortField == 'payment_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã hóa đơn
                        @if($sortField == 'payment_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'employee_name', 'sort_direction' => $sortField == 'employee_name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Nhân viên xử lý
                        @if($sortField == 'employee_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'customer_name', 'sort_direction' => $sortField == 'customer_name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Khách hàng
                        @if($sortField == 'customer_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'total_price', 'sort_direction' => $sortField == 'total_price' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tổng tiền
                        @if($sortField == 'total_price')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'discount_amount', 'sort_direction' => $sortField == 'discount_amount' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tiền giảm giá
                        @if($sortField == 'discount_amount')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'final_price', 'sort_direction' => $sortField == 'final_price' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tiền thanh toán
                        @if($sortField == 'final_price')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'payment_method', 'sort_direction' => $sortField == 'payment_method' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Phương thức thanh toán
                        @if($sortField == 'payment_method')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>

                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'payment_time', 'sort_direction' => $sortField == 'payment_time' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Thời gian thanh toán
                        @if($sortField == 'payment_time')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
            <tr>
                <td class="text-center">{{ $payment->payment_id }}</td>
                <td>{{ $payment->employee->name }}</td>
                <td>{{ $payment->order->customer->name }}</td>
                <td class="text-center">{{ number_format($payment->order->total_price, 0, ',', '.') }} đ</td>
                <td class="text-center">{{ number_format($payment->discount_amount, 0, ',', '.') }} đ</td>
                <td class="text-center">{{ number_format($payment->final_price, 0, ',', '.') }} đ</td>
                <td class="text-center">
                    @php
                    $method = [
                    'cash' => 'Tiền mặt',
                    'bank_transfer' => 'Chuyển khoản'
                    ]
                    @endphp
                    {{ $method[$payment->payment_method] ?? 'Không xác định' }}
                </td>
                <td>{{ $payment->payment_time->format('H:i:s d/m/Y') }}</td>
                <td class="text-center">
                    <button class="btn btn-info btn-sm view-payment-detail" data-id="{{ $payment->payment_id }}">
                        <i class="fa-solid fa-circle-info"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal chi tiết hóa đơn -->
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
                    <!-- Thông tin thanh toán -->
                    <div class="col">
                        <h6 class="mb-2">Thông tin thanh toán</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Mã hóa đơn:</strong> <span id="detailPaymentId"></span>
                            </li>
                            <li class="list-group-item"><strong>Nhân viên xử lý:</strong> <span
                                    id="detailEmployeeName"></span></li>
                            <li class="list-group-item"><strong>Khách hàng:</strong> <span
                                    id="detailCustomerName"></span></li>
                            <li class="list-group-item"><strong>Phương thức:</strong> <span
                                    id="detailPaymentMethod"></span></li>
                            <li class="list-group-item"><strong>Loại đơn hàng:</strong> <span
                                    id="detailOrderType"></span>
                            </li>
                            <li class="list-group-item"><strong>Bàn:</strong> <span id="detailTable"></span>
                            </li>
                            <li class="list-group-item"><strong>Khuyến mãi:</strong> <span id="detailPromotion"></span>
                            </li>
                            <li class="list-group-item"><strong>Tổng tiền:</strong> <span id="detailTotalPrice"></span>
                            </li>
                            <li class="list-group-item"><strong>Tiền giảm giá:</strong> <span
                                    id="detailDiscountAmount"></span></li>
                            <li class="list-group-item"><strong>Tiền thanh toán:</strong> <span
                                    id="detailFinalPrice"></span></li>
                            <li class="list-group-item"><strong>Tổng đã nhận:</strong> <span
                                    id="detailAmountReceived"></span>
                            </li>
                            <li class="list-group-item"><strong>Tổng thừa:</strong> <span id="detailChange"></span>
                            </li>
                            <li class="list-group-item"><strong>Thời gian:</strong> <span id="detailPaymentTime"></span>
                            </li>
                        </ul>
                    </div>

                </div>
                <hr>
                <!-- Chi tiết sản phẩm -->
                <h6>Chi tiết sản phẩm đã mua</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                            </tr>
                        </thead>
                        <tbody id="detailOrderItems">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
$(document).ready(function() {
    $('.view-payment-detail').on('click', function() {
        let paymentId = $(this).data('id');
        $.ajax({
            url: '/admin/payment/' + paymentId,
            type: 'GET',
            success: function(data) {
                $('#detailPaymentId').text(data.payment_id);
                $('#detailEmployeeName').text(data.employee.name);
                let methodText = data.payment_method === 'cash' ? 'Tiền mặt' :
                    (data.payment_method === 'bank_transfer' ? 'Chuyển khoản' :
                        'Không xác định');
                let typeText = data.order.order_type === 'dine_in' ? 'Dùng tại quán' :
                    (data.order.order_type === 'takeaway' ? 'Mang đi' :
                        'Không xác định');
                $('#detailPaymentMethod').text(methodText);
                $('#detailOrderType').text(typeText);
                $('#detailTable').text(data.order.table ? data.order.table.table_number :
                    '');
                $('#detailPromotion').text(data.promotion ? data.promotion
                    .name : 'Không có');
                $('#detailDiscountAmount').text(data.discount_amount);
                $('#detailFinalPrice').text(data.final_price);
                $('#detailAmountReceived').text(data.amount_received);
                $('#detailChange').text(data.amount_received - data.final_price);
                $('#detailPaymentTime').text(moment(data.payment_time).format(
                    'HH:mm:ss DD/MM/YYYY'));

                $('#detailCustomerName').text(data.order.customer.name);
                $('#detailTotalPrice').text(data.order.total_price);

                let orderItemsHtml = '';

                data.order.orderItems.forEach(function(orderItem) {
                    orderItemsHtml += `
                          <tr>
                            <td>${orderItem.item ? orderItem.item.name : 'N/A'}</td>
                            <td>${orderItem.quantity}</td>
                            <td>${orderItem.item ? orderItem.item.price : 'N/A'}</td>
                          </tr>
                        `;
                });

                $('#detailOrderItems').html(orderItemsHtml);

                $('#paymentDetailModal').modal('show');
            },
            error: function() {
                alert('Không lấy được thông tin chi tiết hóa đơn.');
            }
        });
    });
});
</script>
@endsection