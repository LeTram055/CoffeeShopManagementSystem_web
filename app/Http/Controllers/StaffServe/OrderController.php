<?php

namespace App\Http\Controllers\StaffServe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Orders;
use App\Models\Tables;
use App\Models\Customers;
use App\Models\MenuItems;
use App\Models\OrderItems;
use App\Models\Promotions;
use App\Models\Payments;

class OrderController extends Controller
{
    public function getTable()
    {
        $tables = Tables::with('status')->get();
        return response()->json(['success' => true, 'data' => $tables], 200);
    }

    public function getCustomers()
    {
        $customers = Customers::all();
        return response()->json(['success' => true, 'data' => $customers], 200);
    }

    public function addCustomer(Request $request)
    {
        // Kiểm tra số điện thoại đã tồn tại chưa
        $existingCustomer = Customers::where('phone_number', $request->phone_number)->first();

        if ($existingCustomer) {
            return response()->json(['success' => false, 'message' => 'Số điện thoại đã tồn tại!'], 400);
        }

        // Nếu chưa tồn tại, tạo khách hàng mới
        $customer = Customers::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
        ]);

        return response()->json(['success' => true, 'data' => $customer], 201);
    }

    public function getMenu()
    {
        $menu = MenuItems::where('is_available', 1)->get();
        foreach ($menu as $item) {
            $item->image_url = url(Storage::url('uploads/' . $item->image_url)); // Đường dẫn đầy đủ
        }
        return response()->json(['success' => true, 'data' => $menu], 200);
    }

    public function createOrder(Request $request)
    {
        $order = Orders::create([
            'table_id' => $request->table_id,
            'customer_id' => $request->customer_id,
            'order_type' => $request->order_type,
            'total_price' => $request->total_price,
            'status' => $request->status,
            'created_at' => $request->created_at,
        ]);

        foreach ($request->items as $item) {
            OrderItems::create([
                'order_id' => $order->order_id,
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'note' => $item['note'],
            ]);
        }

        $table = Tables::findOrFail($request->table_id);
        $table->status_id = 2; 
        $table->save();

        return response()->json(['message' => 'Cập nhật thành công']);
    }

    public function getOrderByTableId(Request $request)
    {
        try {
            $order = Orders::where('table_id', $request->tableId) 
                ->with(['orderItems', 'table', 'customer'])
                ->latest('created_at') // Lấy đơn hàng mới nhất
                ->first();
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
            
            return response()->json(['success' => true, 'data' => $order], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi lấy danh sách đơn hàng', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateOrder(Request $request)
    {
        
        try{
            $order = Orders::findOrFail($request->order_id);
            $order->update([
                'total_price' => $request->total_price,
            ]);
            $order->orderItems()->delete();

            foreach ($request->items as $item) {
                OrderItems::create([
                    'order_id' => $order->order_id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'note' => $item['note'],
                ]);
            }

            return response()->json(['message' => 'Cập nhật thành công']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Lỗi khi cập nhật đơn hàng', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function cancelOrder($orderId)
    {
        try {
            $order = Orders::findOrFail($orderId);
            $order->status = "cancelled";
            $order->save();

            $table = Tables::findOrFail($order->table_id);
            $table->status_id = 1; 
            $table->save();

            return response()->json(['message' => 'Hủy đơn hàng thành công']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi hủy đơn hàng', 'error' => $e->getMessage()], 500);
        }
    }

    public function eligiblePromotions($order_id)
    {
        $order = Orders::findOrFail($order_id);
        $orderTotal = $order->total_price;
        $date = $order->created_at;

        // Lấy các khuyến mãi có điều kiện hợp lệ
        $promotions = Promotions::where('is_active', 1)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('min_order_value', '<=', $orderTotal)
            ->get();

        // Tính số tiền giảm hiệu quả cho mỗi khuyến mãi
        $promotions = $promotions->map(function($promotion) use ($orderTotal) {
            if ($promotion->discount_type === 'percentage') {
                // Nếu giảm theo phần trăm, số tiền giảm = tổng đơn * (phần trăm / 100)
                $promotion->effective_discount = $orderTotal * ($promotion->discount_value / 100);
            } else {
                // Nếu giảm tiền cố định, số tiền giảm = discount_value
                $promotion->effective_discount = $promotion->discount_value;
            }
            return $promotion;
        });

        // Sắp xếp theo số tiền giảm hiệu quả giảm dần
        $promotions = $promotions->sortByDesc('effective_discount')->values();

        return response()->json(['success' => true, 'data' => $promotions], 200);
    }

    public function createPayment(Request $request)
    {
        $payment = Payments::create([
            'order_id' => $request->order_id,
            'employee_id' => $request->employee_id,
            'promotion_id' => $request->promotion_id == 0 ? null : $request->promotion_id,
            'discount_amount' => $request->discount_amount,
            'final_price' => $request->final_price,
            'payment_method' => $request->payment_method,
            'amount_received' => $request->amount_received,
            'payment_time' => $request->payment_time,
        ]);

        $order = Orders::findOrFail($request->order_id);
        
        $order->status = 'pending_payment';
        $order->save();

        $table = Tables::findOrFail($order->table_id);
        
        $table->status_id = 1; 
        $table->save();

        return response()->json(['message' => 'Cập nhật thành công']);
    }
}