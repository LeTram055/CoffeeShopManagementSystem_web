<?php

namespace App\Http\Controllers\StaffBaristas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Ingredients;
use App\Models\IngredientLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Events\OrderCompletedEvent;
use Illuminate\Support\Facades\Log; 
use App\Events\NewOrderEvent;
use App\Events\LowStockEvent;
class OrderController extends Controller
{
    public function index() {
    $orders = Orders::with(['orderItems.item', 'table', 'customer'])
                    ->where('status', 'confirmed') // Lọc đơn hàng có trạng thái "chờ nhận món"
                    ->get();
                    
    return view('staff_baristas.order.index')->with('orders', $orders);
}


    public function showDetail($id)
    {
        $order = Orders::with('orderItems.item')->findOrFail($id);
        return view('staff_baristas.order.detail')
            ->with('order', $order);
    }

    public function completeOrder(Request $request, $id)
    {
        $order = Orders::with('orderItems.item.ingredients')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng!'], 404);
        }
        if ($order->order_type == 'takeaway') {
            $order->status = 'pending_payment';
        } else {
            $order->status = 'received';
        }

        $order->save();

        // Giảm số lượng nguyên liệu tương ứng với món ăn
        foreach ($order->orderItems as $orderItem) {
            $menuItem = $orderItem->item;
            foreach ($menuItem->ingredients as $ingredient) {
                $stock = Ingredients::where('ingredient_id', $ingredient->ingredient->ingredient_id)->first();
                if ($stock) {
                    $quantityUsed = $ingredient->quantity_per_unit * $orderItem->quantity;
                    $stock->quantity -= $quantityUsed;
                    $ingredient->last_updated = now();
                    $stock->save();

                    // Lưu log thay đổi nguyên liệu với chi tiết món ăn và đơn hàng
                    IngredientLogs::create([
                        'ingredient_id' => $ingredient->ingredient_id,
                        'employee_id' => Auth::user()->employee_id,
                        'quantity_change' => -$quantityUsed,
                        'price' => null,
                        'new_cost_price' => $stock->cost_price,
                        'log_type' => 'export',
                        'reason' => "Dùng cho món '{$menuItem->name}' trong đơn hàng #{$order->order_id}",
                        'changed_at' => now(),
                    ]);
                }
                if ($stock->quantity <= $stock->min_quantity) {
                    broadcast(new LowStockEvent($stock))->toOthers();
                    
                }
            }
        }

        if($order->order_type == 'dine_in') {
            broadcast(new OrderCompletedEvent($order))->toOthers();
        } else {
            broadcast(new NewOrderEvent($order, 'completed'))->toOthers();
        }
        
        return response()->json(['message' => 'Đơn hàng đã hoàn thành!'], 200);
    }
}