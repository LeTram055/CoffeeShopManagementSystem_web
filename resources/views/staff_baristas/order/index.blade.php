@extends('staff_baristas.layouts.master')
@section('title', 'Đơn hàng')
@section('feature-title', 'Đơn hàng')

@section('custom-css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
.order-container {
    display: flex;
    flex-wrap: wrap;
    height: 600px;
    justify-content: center;

    padding: 5px 0px 10px 0px;
}

.order-list,
.order-details {
    padding: 15px;
    overflow-y: auto;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {


    .order-list,
    .order-details {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;

    }
}


@media (min-width: 768px) {
    .order-list {
        width: 28%;
        border-right: 1px solid #ddd;
        margin-right: 1%;
    }

    .order-details {
        width: 68%;
        margin-left: 1%;
    }
}

.order-item {
    cursor: pointer;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.order-item:hover {
    background-color: #f8f9fa;
}

.nav-tabs .nav-link {
    cursor: pointer;
}
</style>
@endsection

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }} position-relative">
        {{ Session::get('alert-' . $msg) }}
        <button type="button" class="btn-close position-absolute end-0 me-2" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </p>
    @endif
    @endforeach
</div>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="orderToast" class="toast align-items-center text-white bg-success border-0" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <strong id="toastOrderId"></strong> vừa được tạo!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>

<div class="order-container">
    <!-- Danh sách đơn hàng -->
    <div class="order-list">
        <h3 class="title2">Đơn đặt món</h3>
        <div id="orders">
            @foreach($orders as $order)
            <div class="order-item" data-id="{{ $order->order_id }}" data-status="{{ $order->status }}">
                <strong>Đơn #{{ $order->order_id }}</strong> -
                {{ $order->table_id ? 'Bàn số ' . $order->table_id : 'Mang đi' }}
                <br> Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}
            </div>
            @endforeach
        </div>
    </div>

    <!-- Chi tiết đơn hàng -->
    <div class="order-details">
        <h3 class="title2">Chi tiết đơn đặt</h3>
        <div id="order-detail">
            <p>Chọn đơn hàng để xem chi tiết</p>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.socket.io/4.0.1/socket.io.min.js"></script>
<script>
$(document).ready(function() {
    $('.order-item').click(function() {
        let orderId = $(this).data('id');
        $.get("{{ url('staff_baristas/order/detail') }}/" + orderId, function(data) {
            $('#order-detail').html(data);
        });
    });

    const socket = io("http://localhost:3000");

    socket.on("connect", () => {
        console.log("Connected to WebSocket server");
    });

    socket.on("order.created", (order) => {
        console.log("Đơn hàng mới nhận được:", order);

        let orderId = order.data.order.order_id;

        let toastHtml = `
        <div class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>Đơn hàng #${orderId}</strong> vừa được tạo!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>`;

        // Thêm vào danh sách thông báo
        $('.toast-container').append(toastHtml);

        // Hiển thị Toast
        let newToast = $('.toast-container .toast').last();
        let toast = new bootstrap.Toast(newToast[0], {
            autohide: false
        });
        toast.show();
    });

});
</script>
@endsection