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
use App\Events\NewOrderEvent;
use Illuminate\Support\Facades\Log;

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
        // Kiểm tra trạng thái bàn
        $table = Tables::findOrFail($request->table_id);
        if ($table->status_id != 1) { 
            return response()->json([
                'success' => false,
                'message' => 'Bàn đã được sử dụng, không thể tạo đơn hàng mới.',
            ], 422);
        }

        $errors = [];

        foreach ($request->items as $itemData) {
            $item = MenuItems::find($itemData['item_id']);

            $maxServings = $item->calculateMaxServings();
            if ($maxServings === 0) {
                $errors[] = "Món '{$item->name}' không còn nguyên liệu để phục vụ.";
            }
            if ($itemData['quantity'] > $maxServings) {
                $errors[] = "Món '{$item->name}' chỉ có thể phục vụ tối đa {$maxServings} phần.";
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo đơn hàng',
                'errors'  => $errors,
            ], 422);
        }

        $order = Orders::create([
            'table_id' => $request->table_id,
            'customer_id' => $request->customer_id,
            'order_type' => $request->order_type,
            'total_price' => $request->total_price,
            'status' => $request->status,
            'created_at' => $request->created_at,
        ]);

        foreach ($request->items as $itemData) {
            OrderItems::create([
                'order_id' => $order->order_id,
                'item_id' => $itemData['item_id'],
                'quantity' => $itemData['quantity'],
                'note' => $itemData['note'],
            ]);

            $item = MenuItems::find($itemData['item_id']);
            $quantityOrdered = $itemData['quantity'];

            foreach ($item->ingredients as $menuIngredient) {
                $ingredient = $menuIngredient->ingredient;

                // Tổng lượng nguyên liệu cần dùng
                $requiredAmount = $quantityOrdered * $menuIngredient->quantity_per_unit;

                // Cập nhật reserved_quantity
                $ingredient->reserved_quantity += $requiredAmount;
                $ingredient->save();
            }
        }

        $table = Tables::findOrFail($request->table_id);
        $table->status_id = 2; 
        $table->save();

        broadcast(new NewOrderEvent($order, 'created'))->toOthers();


        return response()->json(['message' => 'Tạo đơn hàng thành công']);
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

    // public function updateOrder(Request $request)
    // {
        
    //     try{
    //         $order = Orders::with('orderItems.item.ingredients')->findOrFail($request->order_id);

            

    //         foreach ($order->orderItems as $oldItem) {
    //             $menuItem = $oldItem->item;

    //             foreach ($menuItem->ingredients as $menuIngredient) {
    //                 $ingredient = $menuIngredient->ingredient;
    //                 $usedAmount = $oldItem->quantity * $menuIngredient->quantity_per_unit;

    //                 $ingredient->reserved_quantity -= $usedAmount;
    //                 if ($ingredient->reserved_quantity < 0) {
    //                     $ingredient->reserved_quantity = 0;
    //                 }
    //                 $ingredient->save();
    //             }
    //         }

    //         $errors = [];

    //         if ($request->has('items')) {
    //             foreach ($request->items as $itemData) {
    //                 $menuItem = MenuItems::find($itemData['item_id']);

    //                 if ($menuItem) {
    //                     $maxServings = $menuItem->calculateMaxServings(); // Hàm bạn đã định nghĩa
    //                     if ($itemData['quantity'] > $maxServings) {
    //                         $errors[] = "Món '{$menuItem->name}' chỉ có thể phục vụ tối đa {$maxServings} phần.";
    //                     }
    //                 }
    //             }
    //         }

    //         if (!empty($errors)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Không thể tạo đơn hàng',
    //                 'errors'  => $errors,
    //             ], 422);
    //         }

    //         $table = Tables::findOrFail($order->table_id);
    //         $table->status_id = 1; // Trả bàn về trạng thái trống
    //         $table->save();

            
    //         $tableNew = Tables::findOrFail($request->table_id);
    //         $tableNew->status_id = 2; // Đặt bàn mới thành trạng thái đang sử dụng

    //         $tableNew->save();
        
    //         $order->update([
    //             'total_price' => $request->total_price,
    //             'table_id' => $request->table_id,
    //         ]);
    //         $order->orderItems()->delete();

    //         foreach ($request->items as $item) {
    //             $menuItem = MenuItems::find($item['item_id']);
    //             if ($menuItem) {
    //                 OrderItems::create([
    //                     'order_id' => $order->order_id,
    //                     'item_id' => $item['item_id'],
    //                     'quantity' => $item['quantity'],
    //                     'note' => $item['note'],
    //                 ]);

    //                 // Cập nhật reserved_quantity mới
    //                 foreach ($menuItem->ingredients as $menuIngredient) {
    //                     $ingredient = $menuIngredient->ingredient;
    //                     $usedAmount = $item['quantity'] * $menuIngredient->quantity_per_unit;

    //                     $ingredient->reserved_quantity += $usedAmount;
    //                     $ingredient->save();
    //                 }
    //             }
                
    //         }
    //         broadcast(new NewOrderEvent($order, 'updated'))->toOthers();
    //         return response()->json(['message' => 'Cập nhật thành công']);
    //         } catch (\Exception $e) {
    //             return response()->json(['success' => false, 'message' => 'Lỗi khi cập nhật đơn hàng', 'error' => $e->getMessage()], 500);
    //     }
    // }

    
    public function updateOrder(Request $request)
    {
        try {
            $order = Orders::with('orderItems.item.ingredients')->findOrFail($request->order_id);

            $errors = [];

            if ($request->has('items')) {
                foreach ($request->items as $itemData) {
                    $menuItem = MenuItems::find($itemData['item_id']);

                    if ($menuItem) {
                        $maxServings = $menuItem->calculateMaxServings(); // Hàm bạn đã định nghĩa
                        if ($itemData['quantity'] > $maxServings) {
                            $errors[] = "Món '{$menuItem->name}' chỉ có thể phục vụ tối đa {$maxServings} phần.";
                        }
                    }
                }
            }

            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể tạo đơn hàng',
                    'errors'  => $errors,
                ], 422);
            }

            
            // Duyệt qua các món trong đơn hàng hiện tại
            foreach ($order->orderItems as $orderItem) {
                if ($orderItem->status === 'completed') {
                    // Nếu món đã hoàn thành, giữ lại và không xóa
                    continue;
                }

                // Nếu món chưa hoàn thành (order hoặc issue), giải phóng reserved_quantity và xóa
                foreach ($orderItem->item->ingredients as $menuIngredient) {
                    $ingredient = $menuIngredient->ingredient;
                    $usedAmount = $orderItem->quantity * $menuIngredient->quantity_per_unit;

                    $ingredient->reserved_quantity -= $usedAmount;
                    if ($ingredient->reserved_quantity < 0) {
                        $ingredient->reserved_quantity = 0;
                    }
                    $ingredient->save();
                }

                // Xóa món chưa hoàn thành
                
                OrderItems::where('order_id', $orderItem->order_id)
                    ->where('item_id', $orderItem->item_id)
                    ->delete();
            }

            // Duyệt qua các món mới từ yêu cầu
            foreach ($request->items as $itemData) {
                $orderItem = OrderItems::where('order_id', $order->order_id)
                    ->where('item_id', $itemData['item_id'])->first();

                if ($orderItem && $orderItem->status === 'completed') {
                    // Nếu món đã hoàn thành, chỉ cập nhật số lượng nếu cần
                    $newQuantity = $itemData['quantity'];
                    $quantityToAdd = $newQuantity - $orderItem->quantity;

                    if ($quantityToAdd > 0) {
                        // Cập nhật reserved_quantity
                        foreach ($orderItem->item->ingredients as $menuIngredient) {
                            $ingredient = $menuIngredient->ingredient;
                            $usedAmount = $quantityToAdd * $menuIngredient->quantity_per_unit;

                            $ingredient->reserved_quantity += $usedAmount;
                            $ingredient->save();
                        }
                    }



                    //Cập nhật số lượng mới
                    // $orderItem->quantity = $newQuantity;
                    // $orderItem->note = $itemData['note']; // Cập nhật ghi chú nếu có
                    // $orderItem->save();
                    OrderItems::where('order_id', $orderItem->order_id)
                        ->where('item_id', $orderItem->item_id)
                        ->update([
                            'quantity' => $newQuantity,
                            
                            'note' => $itemData['note']
                        ]);

                } else {
                    // Thêm món mới
                    $menuItem = MenuItems::find($itemData['item_id']);
                    if ($menuItem) {
                        OrderItems::create([
                            'order_id' => $order->order_id,
                            'item_id' => $itemData['item_id'],
                            'quantity' => $itemData['quantity'],
                            'note' => $itemData['note'],
                            'completed_quantity' => 0, // Món mới chưa hoàn thành
                            'status' => 'order', // Món mới có trạng thái 'order'
                        ]);

                        // Cập nhật reserved_quantity
                        foreach ($menuItem->ingredients as $menuIngredient) {
                            $ingredient = $menuIngredient->ingredient;
                            $usedAmount = $itemData['quantity'] * $menuIngredient->quantity_per_unit;

                            $ingredient->reserved_quantity += $usedAmount;
                            $ingredient->save();
                        }
                    }
                }
            }

            $table = Tables::findOrFail($order->table_id);
            $table->status_id = 1; // Trả bàn về trạng thái trống
            $table->save();

            
            $tableNew = Tables::findOrFail($request->table_id);
            $tableNew->status_id = 2; // Đặt bàn mới thành trạng thái đang sử dụng

            $tableNew->save();
        
            $order->update([
                'total_price' => $request->total_price,
                'table_id' => $request->table_id,
                'status' => 'confirmed'
            ]);
            
            broadcast(new NewOrderEvent($order, 'updated'))->toOthers();
            return response()->json(['message' => 'Cập nhật đơn hàng thành công!']);
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật đơn hàng: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi khi cập nhật đơn hàng', 'error' => $e->getMessage()], 500);
        }
    }

    // public function updateOrder(Request $request)
    // {
    //     try {
    //         $order = Orders::with('orderItems.item.ingredients')->findOrFail($request->order_id);

    //         $errors = [];

    //         // Kiểm tra số lượng món có vượt quá giới hạn không
    //         if ($request->has('items')) {
    //             foreach ($request->items as $itemData) {
    //                 $menuItem = MenuItems::find($itemData['item_id']);

    //                 if ($menuItem) {
    //                     $maxServings = $menuItem->calculateMaxServings();
    //                     if ($itemData['quantity'] > $maxServings) {
    //                         $errors[] = "Món '{$menuItem->name}' chỉ có thể phục vụ tối đa {$maxServings} phần.";
    //                     }
    //                 }
    //             }
    //         }

    //         if (!empty($errors)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Không thể cập nhật đơn hàng',
    //                 'errors'  => $errors,
    //             ], 422);
    //         }

    //         // Duyệt qua các món trong đơn hàng hiện tại
    //         foreach ($order->orderItems as $orderItem) {
                
    //             if ($orderItem->status === 'completed') {
    //                 // Nếu món đã hoàn thành, chỉ cập nhật số lượng nếu cần
    //                 $newItem = collect($request->items)->firstWhere('item_id', $orderItem->item_id);
    //                 if ($newItem) {
    //                     $newQuantity = $newItem['quantity'];
    //                     $quantityToAdd = $newQuantity - $orderItem->quantity;

    //                     if ($quantityToAdd > 0) {
    //                         // Cập nhật reserved_quantity
    //                         foreach ($orderItem->item->ingredients as $menuIngredient) {
    //                             $ingredient = $menuIngredient->ingredient;
    //                             $usedAmount = $quantityToAdd * $menuIngredient->quantity_per_unit;

    //                             $ingredient->reserved_quantity += $usedAmount;
    //                             $ingredient->save();
    //                         }
    //                     }

    //                     // Cập nhật số lượng mới
    //                     $orderItem->quantity = $newQuantity;
    //                     $orderItem->note = $newItem['note'] ?? $orderItem->note;
    //                     $orderItem->save();
    //                 }
    //                 continue;
    //             }

    //             // Nếu món chưa hoàn thành, giải phóng reserved_quantity và xóa
    //             foreach ($orderItem->item->ingredients as $menuIngredient) {
    //                 $ingredient = $menuIngredient->ingredient;
    //                 $usedAmount = $orderItem->quantity * $menuIngredient->quantity_per_unit;

    //                 $ingredient->reserved_quantity -= $usedAmount;
    //                 if ($ingredient->reserved_quantity < 0) {
    //                     $ingredient->reserved_quantity = 0;
    //                 }
    //                 $ingredient->save();
    //             }

    //             // Xóa món chưa hoàn thành
    //             $orderItem->delete();
    //         }

    //         // Duyệt qua các món mới từ yêu cầu
    //         foreach ($request->items as $itemData) {
    //             $orderItem = $order->orderItems->where('item_id', $itemData['item_id'])->first();

    //             if (!$orderItem) {
    //                 // Thêm món mới
    //                 $menuItem = MenuItems::find($itemData['item_id']);
    //                 if ($menuItem) {
    //                     OrderItems::create([
    //                         'order_id' => $order->order_id,
    //                         'item_id' => $itemData['item_id'],
    //                         'quantity' => $itemData['quantity'],
    //                         'note' => $itemData['note'],
    //                         'completed_quantity' => 0,
    //                         'status' => 'order',
    //                     ]);

    //                     // Cập nhật reserved_quantity
    //                     foreach ($menuItem->ingredients as $menuIngredient) {
    //                         $ingredient = $menuIngredient->ingredient;
    //                         $usedAmount = $itemData['quantity'] * $menuIngredient->quantity_per_unit;

    //                         $ingredient->reserved_quantity += $usedAmount;
    //                         $ingredient->save();
    //                     }
    //                 }
    //             }
    //         }

    //         // Cập nhật trạng thái bàn cũ về trống
    //         $table = Tables::findOrFail($order->table_id);
    //         $table->status_id = 1;
    //         $table->save();

    //         // Cập nhật trạng thái bàn mới
    //         $tableNew = Tables::findOrFail($request->table_id);
    //         $tableNew->status_id = 2;
    //         $tableNew->save();

    //         // Cập nhật thông tin đơn hàng
    //         $order->update([
    //             'total_price' => $request->total_price,
    //             'table_id' => $request->table_id,
    //             'status' => 'confirmed',
    //         ]);

    //         return response()->json(['message' => 'Cập nhật đơn hàng thành công!']);
    //     } catch (\Exception $e) {
    //         Log::error('Lỗi khi cập nhật đơn hàng: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Lỗi khi cập nhật đơn hàng', 'error' => $e->getMessage()], 500);
    //     }
    // }
    
    public function cancelOrder($orderId)
    {
        try {
            $order = Orders::findOrFail($orderId);
            $order->status = "cancelled";
            $order->save();

            $table = Tables::findOrFail($order->table_id);
            $table->status_id = 1; 
            $table->save();

            foreach ($order->orderItems as $orderItem) {
                $menuItem = $orderItem->item;

                foreach ($menuItem->ingredients as $menuIngredient) {
                    $ingredient = $menuIngredient->ingredient;

                    $usedAmount = $orderItem->quantity * $menuIngredient->quantity_per_unit;
                    $ingredient->reserved_quantity -= $usedAmount;

                    if ($ingredient->reserved_quantity < 0) {
                        $ingredient->reserved_quantity = 0;
                    }

                    $ingredient->save();
                }
            }

            broadcast(new NewOrderEvent($order, 'cancelled'))->toOthers();
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

        broadcast(new NewOrderEvent($order, 'payment'))->toOthers();
        return response()->json(['message' => 'Cập nhật thành công']);
    }
}