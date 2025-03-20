<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customers;
use App\Models\Employees;
use App\Models\Tables;
use App\Models\Ingredients;
use App\Models\MenuItems;
use App\Models\Promotions;
use App\Models\Orders;
use App\Models\Categories;
use App\Models\Payments;
use App\Models\OrderItems;
use Carbon\Carbon;
class HomeController extends Controller
{

    public function index()
{
    $totalCustomers = Customers::count();
    $totalEmployees = Employees::count();
    $totalTables = Tables::count();
    $totalIngredients = Ingredients::count();
    $totalMenuItems = MenuItems::count();
    $totalCategories = Categories::count();
    $totalValidPromotions = Promotions::where('is_active', 1)
        ->where('end_date', '>=', Carbon::today())
        ->count();
    // Tính tổng đơn hàng hôm nay
    $totalOrders = Orders::whereDate('created_at', Carbon::today())->count();

    
    // Tính tổng thu hôm nay
    $totalRevenueToday = Payments::whereDate('payment_time', Carbon::today())->sum('final_price');

    // Lấy danh sách 10 món bán chạy nhất trong ngày hôm nay
    $topSellingItems = OrderItems::selectRaw('menu_items.name, SUM(order_items.quantity) as total_sold')
        ->join('menu_items', 'order_items.item_id', '=', 'menu_items.item_id')
        ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
        ->whereDate('orders.created_at', Carbon::today()) // Chỉ lấy đơn hàng của ngày hôm nay
        ->groupBy('menu_items.name')
        ->orderByDesc('total_sold')
        ->limit(5)
        ->get();



    return view('admin.home.index', compact(
        'totalCustomers', 'totalEmployees', 'totalTables', 'totalIngredients', 'totalMenuItems', 
        'totalCategories', 'totalValidPromotions', 'totalOrders', 'totalRevenueToday', 'topSellingItems'
    ));
}


}