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
use App\Events\OrderIssueEvent;
use App\Models\OrderItems;
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

        // Kiểm tra xem có món nào gặp trục trặc không
        $hasIssues = $order->orderItems()->where('status', 'issue')->exists();
            if ($hasIssues) {
                return response()->json(['message' => 'Không thể hoàn thành đơn hàng vì có món gặp trục trặc!'], 400);
        }

        // Cập nhật trạng thái các món trong đơn hàng thành 'completed'
        foreach ($order->orderItems as $orderItem) {
            OrderItems::where('order_id', $orderItem->order_id)
                        ->where('item_id', $orderItem->item_id)
                        ->update([
                            'status' => 'completed',
                'completed_quantity' => $orderItem->quantity,
                        ]);
        }

        // Giảm số lượng nguyên liệu tương ứng với món ăn
        foreach ($order->orderItems as $orderItem) {
            $menuItem = $orderItem->item;

            if ($menuItem->ingredients->isEmpty()) {
                continue;
            }
            foreach ($menuItem->ingredients as $ingredient) {
                $stock = Ingredients::where('ingredient_id', $ingredient->ingredient->ingredient_id)->first();
                if ($stock) {
                    $quantityUsed = $ingredient->quantity_per_unit * ($orderItem->quantity - $orderItem->completed_quantity);
                    $stock->quantity -= $quantityUsed;
                    $stock->reserved_quantity -= $quantityUsed;

                    if ($ingredient->reserved_quantity < 0) {
                        $ingredient->reserved_quantity = 0;
                    }

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
        if ($order->order_type == 'takeaway') {
            $order->status = 'pending_payment';
        } else {
            $order->status = 'received';
        }

        $order->save();
        if($order->order_type == 'dine_in') {
            broadcast(new OrderCompletedEvent($order))->toOthers();
        } else {
            broadcast(new NewOrderEvent($order, 'completed'))->toOthers();
        }
        
        return response()->json(['message' => 'Đơn hàng đã hoàn thành!'], 200);
    }

    public function reportIssue(Request $request, $id)
    {
        $order = Orders::with('orderItems')->find($id);
        
        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng!'], 404);
        }

        // Lưu lý do gặp trục trặc cho món
        $itemId = $request->input('item_id');
        $reason = $request->input('reason');
        $orderId = $order->order_id;

        // $order->orderItems()
        // ->where('item_id', $itemId)
        // ->update(['status' => 'issue']);

        // Tìm món ăn trong đơn hàng
        $orderItem = $order->orderItems()->where('item_id', $itemId)->first();

        if ($orderItem) {
            // Cập nhật trạng thái status
            $orderItem->update(['status' => 'issue']);

            // Lấy tên món ăn
            $itemName = $orderItem->item->name;

            // Phát thông báo cho nhân viên phục vụ
            broadcast(new OrderIssueEvent($orderId, $itemName, $reason))->toOthers();

            return response()->json(['message' => 'Lý do đã được gửi!'], 200);
        } else {
            return response()->json(['message' => 'Không tìm thấy món ăn!'], 404);
        }
    }
}