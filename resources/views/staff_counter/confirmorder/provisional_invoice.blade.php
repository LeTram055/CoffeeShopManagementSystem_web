<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hóa đơn tạm</title>

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
            <h2>Hóa đơn tạm</h2>
        </div>
        @php


        @endphp
        <table>

            <tr>
                <td>
                    <strong>Khách hàng:</strong> {{ $order->customer ? $order->customer->name : 'N/A' }}
                </td>

            </tr>

        </table>
        <div style="margin-bottom: 20px;">
            <p><strong>Bàn:</strong> {{ $order->table ? 'Bàn ' . $order->table->table_number : '' }}</p>
            <p><strong>Thời gian đặt hàng:</strong> {{ $order->created_at->format('H:i:s d/m/Y') }}</p>

        </div>
        <table>
            <tr class="heading">
                <td style="text-align: center;">Tên món</td>
                <td style="text-align: center;">Số lượng</td>
                <td style="text-align: right;">Đơn giá</td>
                <td style="text-align: right;">Thành tiền</td>
            </tr>
            @foreach($order->orderItems as $item)
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

        </table>
        <p style="font-style: italic; text-align: center; font-weight: bold;">Xin cảm ơn Quý Khách. Hẹn gặp lại !</p>
    </div>
</body>

</html>