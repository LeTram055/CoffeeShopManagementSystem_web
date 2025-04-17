<?php

namespace App\Http\Controllers\StaffCounter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Payments;
use App\Models\Shifts;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index()
    {
        // Lấy danh sách ca làm việc
        $shifts = Shifts::all();
    
        return view('staff_counter.report.index', compact('shifts'));
    }
    public function getTotal(Request $request)
    {
        // Lấy ngày cần thống kê, mặc định là hôm nay
        $date = $request->input('date', Carbon::today()->toDateString());
        $shiftId = $request->input('shift_id');
        // Nếu có shift_id, lấy thông tin ca làm việc
        if ($shiftId) {
            

            $shift = Shifts::findOrFail($shiftId);
            // $startTime = Carbon::parse($date . ' ' . $shift->start_time);
            // $endTime = Carbon::parse($date . ' ' . $shift->end_time);

            $startTime = Carbon::parse($date)->setTimeFrom(Carbon::parse($shift->start_time));
            $endTime = Carbon::parse($date)->setTimeFrom(Carbon::parse($shift->end_time));

            
            Log::info("Start Time: $startTime, End Time: $endTime");

            // Nếu ca kết thúc vào ngày hôm sau
            if ($endTime->lessThan($startTime)) {
                $endTime->addDay();
            }

            // Truy vấn trong khoảng thời gian ca làm
            $orders = Orders::whereBetween('created_at', [$startTime, $endTime])->get();
            $payments = Payments::whereBetween('payment_time', [$startTime, $endTime])->get();
        } else {
            // Nếu không có shift_id, lấy tất cả đơn hàng và thanh toán trong ngày
            $orders = Orders::whereDate('created_at', $date)->get();
            $payments = Payments::whereDate('payment_time', $date)->get();
        }
        // Tính toán

        // Thống kê tổng số đơn hàng trong ngày
        $totalOrders = $orders->count();

        // Tổng giá trị các đơn hàng
        $totalRevenue = $orders->sum('total_price');

        // Tổng số tiền giảm giá từ khuyến mãi
        $totalDiscount = $payments->sum('discount_amount');

        // Đơn hàng bị hủy
        $totalCanceledOrders = $orders->where('status', 'cancelled')->count();
        $totalCanceledRevenue = $orders->where('status', 'cancelled')->sum('total_price');
        
        // Thực nhận tiền mặt
        $totalCashReceived = $payments->where('payment_method', 'cash')->sum('amount_received');
            
        $totalCash = $payments->where('payment_method', 'cash')->sum('final_price');

        $totalCashReceived = $totalCashReceived - ($totalCashReceived - $totalCash);

        // Thực nhận chuyển khoản
        $totalBankReceived = $payments->where('payment_method', 'bank_transfer')->sum('amount_received');

        $totalBank = $payments->where('payment_method', 'bank_transfer')->sum('final_price');

        $totalBankReceived = $totalBankReceived - ($totalBankReceived - $totalBank);

        $totalActualReceived = $totalCashReceived + $totalBankReceived;

        return response()->json([
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalDiscount' => $totalDiscount,
            'totalCashReceived' => $totalCashReceived,
            'totalBankReceived' => $totalBankReceived,
            'totalActualReceived' => $totalActualReceived,
            'totalCanceledOrders' => $totalCanceledOrders,
            'totalCanceledRevenue' => $totalCanceledRevenue,
        ]);
    }

}