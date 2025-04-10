<?php

namespace App\Http\Controllers\StaffServe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Payments;
use App\Models\Setting;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function getPaidOrders(Request $request)
    {
        $startDate = $request->has('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
    $endDate = $request->has('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        $payments = Payments::with('order.orderItems', 'order.customer', 'order.table')
            ->whereHas('order', function ($query) {
                $query->where('order_type', 'dine_in');
            });

        if ($startDate) {
            $payments->where('payment_time', '>=', $startDate);
        }
        if ($endDate) {
            $payments->where('payment_time', '<=', $endDate);
        }

        $payments = $payments->orderBy('payment_id', 'desc')->get();

        // $payments = Payments::with('order.orderItems', 'order.customer', 'order.table')->whereHas('order', function ($query) {
        //     $query->where('order_type', 'dine_in');
        // })
        // ->orderBy('payment_id', 'desc')
        // ->get();

        foreach($payments as $payment) {
            $order = $payment->order;
            $order->table_number = $order->table ? $order->table->table_number : null;
            foreach ($order->orderItems as $orderItem) {
                if ($orderItem->item) {
                    // Kiểm tra nếu đường dẫn đã là URL đầy đủ, thì giữ nguyên
                    if (!filter_var($orderItem->item->image_url, FILTER_VALIDATE_URL)) {
                        $orderItem->item->image_url = url(Storage::url('uploads/' . $orderItem->item->image_url));
                    }
                }

            }
        }
        return response()->json(['success' => true, 'data' => $payments], 200);
    }

    public function getInvoice($paymentId)
    {
        $payment = Payments::with('employee', 'promotion', 'order.orderItems', 'order.customer', 'order.table')
            ->where('payment_id', $paymentId)
            ->firstOrFail();
        $setting = Setting::first();

        $invoiceData = [
            'payment' => $payment,
            'order' => $payment->order,
            'customer' => $payment->order->customer,
            'employee' => $payment->employee,
            'promotion' => $payment->promotion,
            'table' => $payment->order->table,
            'orderItems' => $payment->order->orderItems,
            'setting' => $setting,
        ];

        $pdf = PDF::loadView('staff_serve.invoice', $invoiceData);
        
        return $pdf->download('HoaDonThanhToan_' . $payment->payment_id . '.pdf');
    }
}