<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Payments;
use App\Models\MenuItems;
use App\Models\MenuIngredients;
use App\Models\Ingredients;
use App\Models\IngredientLogs;
use App\Models\Salaries;
use App\Models\WorkSchedules;
use Carbon\Carbon;

class ReportController extends Controller
{

    // Tổng doanh thu theo ngày, tuần, tháng, năm

    public function revenueSummaryPage()
    {
        return view('admin.report.revenue_summary');
    }
    public function revenueSummary(Request $request)
    {
        $timeFrame = $request->input('timeFrame', 'daily');
        $startDate = $request->input('startDate') ?: Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->input('endDate') ?: Carbon::now()->endOfMonth()->toDateString();

        $query = Payments::query();
        if ($startDate && $endDate) {
            $query->whereBetween('payment_time', [$startDate, $endDate]);
        }

        $payments = $query->get();
        $revenue = [];

        foreach ($payments as $payment) {
            $dateKey = match ($timeFrame) {
                'weekly' => Carbon::parse($payment->payment_time)->startOfWeek()->format('Y-m-d'),
                'monthly' => Carbon::parse($payment->payment_time)->format('Y-m'),
                'yearly' => Carbon::parse($payment->payment_time)->format('Y'),
                default => Carbon::parse($payment->payment_time)->format('Y-m-d'),
            };

            if (!isset($revenue[$dateKey])) {
                $revenue[$dateKey] = 0;
            }
            $revenue[$dateKey] += $payment->final_price;
        }

        return response()->json(array_map(fn($date, $total) => [
            'date' => $date,
            'total_revenue' => $total
        ], array_keys($revenue), array_values($revenue)));
    }


    // Doanh thu theo sản phẩm
    public function revenueByProductPage()
    {
        return view('admin.report.revenue_by_product');
    }

    public function revenueByProduct(Request $request)
    {
        $startDate = $request->query('startDate') ?: Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->query('endDate') ?: Carbon::now()->endOfMonth()->toDateString();

        $query = OrderItems::query();

        if ($startDate && $endDate) {
            $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }

        $orderItems = $query->get();
        $products = [];

        foreach ($orderItems as $orderItem) {
            $menuItem = MenuItems::find($orderItem->item_id);
            if (!$menuItem) continue;

            if (!isset($products[$menuItem->name])) {
                $products[$menuItem->name] = [
                    'name' => $menuItem->name,
                    'total_revenue' => 0
                ];
            }
            $products[$menuItem->name]['total_revenue'] += $orderItem->quantity * $menuItem->price;
        }

        return response()->json(array_values($products));
    }

    // Doanh thu theo thời gian trong ngày (khung giờ cao điểm)
    public function revenueByHourPage()
    {
        return view('admin.report.revenue_by_hour');
    }

    public function revenueByHour(Request $request)
    {
        $startDate = $request->query('startDate') ?: Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->query('endDate') ?: Carbon::now()->endOfMonth()->toDateString();

        $query = Payments::query();

        if ($startDate && $endDate) {
            $query->whereBetween('payment_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $payments = $query->get();
        $revenueByHour = array_fill(0, 24, 0);

        foreach ($payments as $payment) {
            $hour = Carbon::parse($payment->payment_time)->hour;
            $revenueByHour[$hour] += $payment->final_price;
        }

        return response()->json(array_map(fn($hour, $total) => [
            'hour' => $hour,
            'total_revenue' => $total
        ], array_keys($revenueByHour), array_values($revenueByHour)));
    }

    //Doanh thu theo hình thức phục vụ
    public function revenueByOrderTypePage()
    {
        return view('admin.report.revenue_by_order_type');
    }
    public function revenueByOrderType(Request $request)
    {
        $startDate = $request->query('startDate') ?: Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->query('endDate') ?: Carbon::now()->endOfMonth()->toDateString();

        $query = Payments::query()->with('order');

        if ($startDate && $endDate) {
            $query->whereBetween('payment_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $payments = $query->get();

        $revenueByType = [
            'dine_in' => 0,
            'takeaway' => 0
        ];

        foreach ($payments as $payment) {
            $orderType = optional($payment->order)->order_type;
            if (isset($revenueByType[$orderType])) {
                $revenueByType[$orderType] += $payment->final_price;
            }
        }

        return response()->json([
            ['order_type' => 'dine_in', 'total_revenue' => $revenueByType['dine_in']],
            ['order_type' => 'takeaway', 'total_revenue' => $revenueByType['takeaway']],
        ]);
    }



    // Lợi nhuận ròng sau khi trừ chi phí nguyên liệu
    public function netProfitPage()
    {
        return view('admin.report.net_profit');
    }

    public function netProfit(Request $request)
    {
        $query = OrderItems::query();

        // Lấy ngày đầu tiên và ngày cuối cùng của tháng hiện tại nếu không có bộ lọc
        $fromDate = $request->from_date ? $request->from_date . ' 00:00:00' : Carbon::now()->startOfMonth()->toDateTimeString();
        $toDate = $request->to_date ? $request->to_date . ' 23:59:59' : Carbon::now()->endOfMonth()->toDateTimeString();

        // 1. Doanh thu và chi phí khuyến mãi từ payments
        $payments = Payments::whereBetween('payment_time', [$fromDate, $toDate])->get();
        $totalRevenue = $payments->sum('final_price');
        $totalPromotion = $payments->sum('discount_amount');

        // 2. Chi phí nguyên liệu xuất (log export & adjustment quantity < 0)
        $ingredientExportCost = IngredientLogs::whereIn('log_type', ['export', 'adjustment'])
            ->where('quantity_change', '<', 0)
            ->whereBetween('changed_at', [$fromDate, $toDate])
            ->get()
            ->sum(function ($log) {
                return abs($log->quantity_change) * $log->new_cost_price;
            });

        // 3. Chi phí nhập nguyên liệu
        $ingredientImportCost = IngredientLogs::where('log_type', 'import')
            ->where('quantity_change', '>', 0)
            ->whereBetween('changed_at', [$fromDate, $toDate])
            ->get()
            ->sum(function ($log) {
                return $log->quantity_change * $log->price;
            });

        // 4. Chi phí lương: từ bảng salaries theo tháng
        $salaryCost = WorkSchedules::with('employee')
            ->whereBetween('work_date', [Carbon::parse($fromDate)->toDateString(), Carbon::parse($toDate)->toDateString()])
            ->where('status', 'completed')
            ->get()
            ->sum(function ($schedule) {
                return $schedule->work_hours * ($schedule->employee->hourly_rate ?? 0);
            });

        // $month = Carbon::parse($fromDate)->month;
        // $year = Carbon::parse($fromDate)->year;
        // $salaryCost = Salaries::where('month', $month)
        //     ->where('year', $year)
        //     ->sum('final_salary');

        // 5. Chi phí thực sự của order
        $realOrderCost = Orders::whereBetween('created_at', [$fromDate, $toDate])
            ->where('status', '!=', 'cancelled')    
            ->sum('total_price');
            
        //Tông chi phí
        $totalCost = $ingredientExportCost + $salaryCost;

        return response()->json([
            'total_revenue' => $totalRevenue,
            'ingredient_export_cost' => $ingredientExportCost,
            'ingredient_import_cost' => $ingredientImportCost,
            'salary_cost' => $salaryCost,
            'real_order_cost' => $realOrderCost,
            'promotion_cost' => $totalPromotion,
            'total_cost' => $totalCost,
            'net_profit' => $totalRevenue - $totalCost,
        ]);
    }


    public function bestSellingProductsPage()
    {
        return view('admin.report.best_selling');
    }

    public function bestSellingProducts(Request $request)
{
    $query = OrderItems::selectRaw('item_id, SUM(quantity) as total_sold')
        ->groupBy('item_id')
        ->orderByDesc('total_sold')
        ->with('item');

    // Kiểm tra nếu có khoảng thời gian
    // if ($request->has('from_date') || $request->has('to_date')) {
    //     $fromDate = $request->from_date ? $request->from_date . ' 00:00:00' : null;
    //     $toDate = $request->to_date ? $request->to_date . ' 23:59:59' : now(); // Lấy ngày hiện tại nếu không có

    //     $query->whereHas('order', function ($q) use ($fromDate, $toDate) {
    //         if ($fromDate) {
    //             $q->where('created_at', '>=', $fromDate);
    //         }
    //         if ($toDate) {
    //             $q->where('created_at', '<=', $toDate);
    //         }
    //     });
    // }

    // Lấy ngày đầu tiên và ngày cuối cùng của tháng hiện tại
    $fromDate = $request->from_date ? $request->from_date . ' 00:00:00' : Carbon::now()->startOfMonth()->toDateTimeString();
    $toDate = $request->to_date ? $request->to_date . ' 23:59:59' : Carbon::now()->endOfMonth()->toDateTimeString();

    // Áp dụng bộ lọc ngày
    $query->whereHas('order', function ($q) use ($fromDate, $toDate) {
        $q->whereBetween('created_at', [$fromDate, $toDate]);
    });

    $bestSellingProducts = $query->get();

    return response()->json($bestSellingProducts);
}



}