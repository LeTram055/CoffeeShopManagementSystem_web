<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hóa đơn - #{{ $order->payments->first()->payment_id }}</title>

    <style>
    body {
        font-family: 'DejaVu Sans', sans-serif;
    }

    .store-info {
        text-align: center;

    }

    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 20px;
        border: 1px solid #eee;
    }

    .invoice-header {
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table td {

        vertical-align: top;
    }

    table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    table tr.item td {
        padding: 5px;
        border-bottom: 1px solid #eee;
    }

    .price {
        text-align: left;
        font-weight: bold;
    }

    .payment-info {
        margin-top: 20px;
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="store-info">
            <h1>{{ $setting->store_name }}</h1>
            <p>&#x2691; </i> {{ $setting->address }}</p>
            <p>&#x260E; {{ $setting->phone_number }}</p>
        </div>
        <div class="invoice-header">
            <h2>Hóa đơn</h2>
        </div>
        @php

        //$payment = $order->payments->first();
        @endphp
        <table>
            <tr>
                <td>
                    <p><strong>Mã:</strong> {{ $payment->payment_id }}</p>
                </td>
                <td>
                    <p><strong>Loại:</strong>
                        @if($order->order_type === 'dine_in')
                        Dùng tại quán
                        @else
                        Mang về
                        @endif

                    </p>
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Khách hàng:</strong> {{ $customer ? $customer->name : 'N/A' }}
                </td>
                <td>
                    <strong>Nhân viên:</strong> {{ $employee ? $employee->name : 'N/A' }}
                </td>
            </tr>

        </table>
        <div style="margin-bottom: 20px;">
            @if($order->order_type === 'dine_in')
            <p><strong>Bàn:</strong> {{ $table ? 'Bàn ' . $table->table_number : '' }}</p>

            @endif

            <p><strong>Thời gian đặt hàng:</strong> {{ $order->created_at->format('H:i:s d/m/Y') }}</p>
            <p><strong>Thời gian thanh toán:</strong> {{ $payment->payment_time->format('H:i:s d/m/Y') }}</p>
            <p><strong>Phương thức thanh toán:</strong>
                {{ $payment->payment_method == 'cash' ? 'Tiền mặt' : 'Chuyển khoản' }}</p>
            @if($promotion)
            <p>
                <strong>Khuyến mãi:</strong>
                {{ $promotion->name }} - Giảm
                {{ number_format($promotion->discount_value, 0, ',', '.') }}
                {{ $promotion->discount_type === 'percentage' ? '%' : 'đ' }}
            </p>
            @else
            <p><strong>Khuyến mãi:</strong></p>
            @endif

        </div>
        <table>
            <tr class="heading">
                <td style="text-align: center;">Tên món</td>
                <td style="text-align: center;">Số lượng</td>
                <td style="text-align: right;">Đơn giá</td>
                <td style="text-align: right;">Thành tiền</td>
            </tr>
            @foreach($orderItems as $item)
            <tr class="item">
                <td style="text-align: center;">{{ $item->item->name }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">{{ number_format($item->item->price, 0, ',', '.') }} VND</td>
                <td style="text-align: right;">{{ number_format($item->quantity * $item->item->price, 0, ',', '.') }}
                    VND</td>
            </tr>
            @endforeach
            <tr class="item" style=" border-top: 5px solid #eee;">
                <td colspan="3" class="price">Tổng tiền:</td>
                <td style="text-align: right;">{{ number_format($order->total_price, 0, ',', '.') }} VND</td>
            </tr>
            <tr class="item">
                <td colspan="3" class="price">Giảm giá:</td>
                <td style="text-align: right;">{{ number_format($payment->discount_amount, 0, ',', '.') }} VND</td>
            </tr>
            <tr class="item">
                <td colspan=" 3" class="price">Tiền phải trả:</td>
                <td style="text-align: right; font-weight:bold">
                    {{ number_format($payment->final_price, 0, ',', '.') }} VND</td>
            </tr>
            <tr class="item">
                <td colspan=" 3" class="price">Số tiền khách đưa:</td>
                <td style="text-align: right;">{{ number_format($payment->amount_received, 0, ',', '.') }} VND</td>
            </tr>
            <tr class="item">
                <td colspan=" 3" class="price">Tiền thừa:</td>
                <td style="text-align: right;">
                    {{ number_format($payment->amount_received - $payment->final_price, 0, ',', '.') }}
                    VND</td>
            </tr>
        </table>
        <p style="font-style: italic; text-align: center; font-weight: bold;">Xin cảm ơn Quý Khách. Hẹn gặp lại !</p>
    </div>
</body>

</html>