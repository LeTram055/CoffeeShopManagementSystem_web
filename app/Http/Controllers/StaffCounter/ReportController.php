<?php

namespace App\Http\Controllers\StaffCounter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Payments;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('staff_counter.report.index');
    }
    public function getTotal(Request $request)
    {
        // Lấy ngày cần thống kê, mặc định là hôm nay
        $date = $request->input('date', Carbon::today()->toDateString());

        // Thống kê tổng số đơn hàng trong ngày
        $totalOrders = Orders::whereDate('created_at', $date)->count();

        // Tổng giá trị các đơn hàng
        $totalRevenue = Orders::whereDate('created_at', $date)->sum('total_price');

        // Tổng số tiền giảm giá từ khuyến mãi
        $totalDiscount = Payments::whereDate('payment_time', $date)->sum('discount_amount');

        // Thực nhận tiền mặt
        $totalCashReceived = Payments::whereDate('payment_time', $date)
            ->where('payment_method', 'cash')
            ->sum('amount_received');
        $totalCash = Payments::whereDate('payment_time', $date)
            ->where('payment_method', 'cash')
            ->sum('final_price');

        $totalCashReceived = $totalCashReceived - ($totalCashReceived - $totalCash);

        // Thực nhận chuyển khoản
        $totalBankReceived = Payments::whereDate('payment_time', $date)
            ->where('payment_method', 'bank_transfer')
            ->sum('amount_received');

        $totalBank = Payments::whereDate('payment_time', $date)
            ->where('payment_method', 'bank_transfer')
            ->sum('final_price');

        $totalBankReceived = $totalBankReceived - ($totalBankReceived - $totalBank);

        $totalActualReceived = $totalCashReceived + $totalBankReceived;

        return response()->json([
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalDiscount' => $totalDiscount,
            'totalCashReceived' => $totalCashReceived,
            'totalBankReceived' => $totalBankReceived,
            'totalActualReceived' => $totalActualReceived,
        ]);
    }
}