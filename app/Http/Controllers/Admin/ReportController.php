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

        $query->whereHas('order', function ($q) use ($fromDate, $toDate) {
            $q->whereBetween('created_at', [$fromDate, $toDate]);
        });

        $orderItems = $query->get();
        $totalRevenue = 0;
        $totalCost = 0;

        foreach ($orderItems as $orderItem) {
            $menuItem = MenuItems::find($orderItem->item_id);
            if (!$menuItem) continue;

            $totalRevenue += $orderItem->quantity * $menuItem->price;

            $menuIngredients = MenuIngredients::where('item_id', $menuItem->item_id)->get();
            foreach ($menuIngredients as $menuIngredient) {
                $ingredient = Ingredients::find($menuIngredient->ingredient_id);
                if (!$ingredient) continue;
                $totalCost += $orderItem->quantity * $menuIngredient->quantity * $ingredient->cost;
            }
        }

        return response()->json([
            'total_revenue' => $totalRevenue,
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