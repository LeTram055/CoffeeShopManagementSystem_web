<?php

namespace App\Http\Controllers\StaffBarista;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\Models\OrderItems;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Ingredients;
use App\Models\IngredientLogs;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {   
        try {
            $orders = Orders::with(['orderItems.item', 'table'])->get();
            foreach ($orders as $order) {
                $order->table_number = $order->table ? $order->table->table_number : null;
                foreach ($order->orderItems as $orderItem) {
                    if ($orderItem->item) {
                        if ($orderItem->item) {
                            // Kiểm tra nếu đường dẫn đã là URL đầy đủ, thì giữ nguyên
                            if (!filter_var($orderItem->item->image_url, FILTER_VALIDATE_URL)) {
                                $orderItem->item->image_url = url(Storage::url('uploads/' . $orderItem->item->image_url));
                            }
                        }

                    }
                }
            }
            return response()->json(['success' => true, 'data' => $orders], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi lấy danh sách đơn hàng', 'error' => $e->getMessage()], 500);
        }
        
    }

    public function completeOrder(Request $request, $id)
    {
        $order = Orders::with('orderItems.item.ingredients.ingredient')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }

        // Cập nhật trạng thái đơn hàng
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
                    $stock->save();

                    // Lưu log thay đổi nguyên liệu với chi tiết món ăn và đơn hàng
                    IngredientLogs::create([
                        'ingredient_id' => $ingredient->ingredient_id,
                        'employee_id' => $request->employee_id,
                        'quantity_change' => -$quantityUsed,
                        'reason' => "Dùng cho món '{$menuItem->name}' trong đơn hàng #{$order->order_id}",
                        'changed_at' => now(),
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Cập nhật đơn hàng thành công']);
    }


}