<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMapping;    
use Maatwebsite\Excel\Concerns\WithHeadings;   

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Payments;
use App\Models\MenuItems;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $payments =  Payments::with([
            'employee',
            'order.customer',
            'order.orderItems',
            'promotion',
            'order.table'
        ])->get();

        foreach ($payments as $payment) {
            if ($payment->order && $payment->order->orderItems) {
                $orderItems = $payment->order->orderItems->map(function ($orderItem) {
                    // Lấy thông tin sản phẩm dựa trên item_id
                    $item = MenuItems::find($orderItem->item_id);
                    // Gán thuộc tính 'item' trực tiếp vào orderItem
                    $orderItem->item = $item;
                    return $orderItem;
                });
                $payment->order->orderItems = $orderItems;
            }
        }
        return $payments;

    }

    public function headings(): array
    {
        return [
            'Mã hóa đơn',
            'Nhân viên xử lý',
            'Khách hàng',
            'Tổng tiền',
            'Tiền giảm giá',
            'Tiền thanh toán',
            'Phương thức thanh toán',
            'Loại đơn hàng',
            'Bàn',
            'Khuyến mãi',
            'Tiền đã nhận',
            'Tiền thừa',
            'Ngày thanh toán',
            'Mã đơn đặt',
            'Chi tiết sản phẩm'
        ];
    }

    public function map($payment): array
    {
        $orderItemsDetails = '';
        if ($payment->order && $payment->order->orderItems) {
            foreach ($payment->order->orderItems as $orderItem) {
                if ($orderItem->item) {
                    $orderItemsDetails .= $orderItem->item->name . ' (x' . $orderItem->quantity . ') - ' . $orderItem->item->price . '; ';
                }
            }
        }
        return [
            $payment->payment_id,
            $payment->employee->name,
            $payment->order->customer->name,
            $payment->order->total_price,
            $payment->order->discount_amount,
            $payment->final_price,
            $payment->payment_method,
            $payment->order->order_type,
            $payment->order->table->table_number ?? '',
            $payment->promotion ? $payment->promotion->name : '',
            $payment->amount_received,
            $payment->amount_received-$payment->final_price,
            $payment->payment_time->format('H:i:s d/m/Y'),
            $payment->order_id,
            $orderItemsDetails
        ];
    }
}