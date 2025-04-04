<?php

namespace App\Http\Controllers\StaffCounter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Tables;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\TableStatuses;
use App\Models\Payments;
use App\Models\Promotions;
use App\Models\MenuItems;
use App\Events\NewOrderEvent;

class ConfirmOrderController extends Controller
{
    public function index(Request $request)
    {
        $dineInStatus = $request->get('dine_in_status', 'pending_payment');
        $takeawayStatus = $request->get('takeaway_status', 'pending_payment');

        $dineInQuery = Orders::where('order_type', 'dine_in');
        $takeawayQuery = Orders::where('order_type', 'takeaway');

        // Lọc trạng thái đơn hàng
        if ($dineInStatus !== 'all') {
            $dineInQuery->where('status', $dineInStatus);
        }

        if ($takeawayStatus !== 'all') {
            if ($takeawayStatus === 'confirmed') {
                // Trạng thái "Chờ nhận món" tương ứng với "confirmed"
                $takeawayQuery->where('status', 'confirmed');
            } else {
                $takeawayQuery->where('status', $takeawayStatus);
            }
        }
        

        $dineInOrders = $dineInQuery->orderBy('created_at', 'desc')->get();
        $takeawayOrders = $takeawayQuery->orderBy('created_at', 'desc')->get();

    
        return view('staff_counter.confirmorder.index', [
            'dineInOrders' => $dineInOrders,
            'takeawayOrders' => $takeawayOrders,
            
            'dineInStatus' => $dineInStatus,
            'takeawayStatus' => $takeawayStatus,
        ]);

    }


    public function show($order_id)
    {
        $order = Orders::with(['table', 'customer', 'orderItems.item', 'payments.employee', 'payments.promotion'])->findOrFail($order_id);

        return response()->json([
            'order' => $order
        ]);
    }

    public function updateTakeaway(Request $request, $order_id)
    {
        $order = Orders::findOrFail($order_id);

        foreach ($order->orderItems as $oldItem) {
            $menuItem = $oldItem->item;

            foreach ($menuItem->ingredients as $menuIngredient) {
                $ingredient = $menuIngredient->ingredient;
                $usedAmount = $oldItem->quantity * $menuIngredient->quantity_per_unit;

                $ingredient->reserved_quantity -= $usedAmount;
                if ($ingredient->reserved_quantity < 0) {
                    $ingredient->reserved_quantity = 0;
                }
                $ingredient->save();
            }
        }

        $errors = [];

        if ($request->has('items')) {
            foreach ($request->items as $itemData) {
                $menuItem = MenuItems::find($itemData['id']);

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
        
        // Xóa tất cả các món ăn hiện tại
        $order->orderItems()->delete();
        $total = 0;

        // Thêm các món ăn mới
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                
                $menuItem = MenuItems::find($item['id']);

                if ($menuItem) {
                    $order->orderItems()->create([
                        'item_id'  => $item['id'],
                        'quantity' => $item['quantity'],
                        'note'     => $item['note'] ?? '',
                    ]);

                    $total += $item['quantity'] * $menuItem->price;

                    // Cập nhật reserved_quantity mới
                    foreach ($menuItem->ingredients as $menuIngredient) {
                        $ingredient = $menuIngredient->ingredient;
                        $usedAmount = $item['quantity'] * $menuIngredient->quantity_per_unit;

                        $ingredient->reserved_quantity += $usedAmount;
                        $ingredient->save();
                    }
                }
            }
        }

        // Cập nhật tổng tiền của đơn hàng
        $order->total_price = $total;
        $order->save();

        broadcast(new NewOrderEvent($order, 'updated'))->toOthers();

        return response()->json(['message' => 'Đơn hàng đã được cập nhật']);
    }
    public function showMenuItem()
        {
            $items = MenuItems::where('is_available', 1)->get();
            return response()->json($items);
        }

    public function cancelOrder(Request $request, $order_id)
    {
        $order = Orders::findOrFail($order_id);
        
        // Kiểm tra nếu đơn hàng đã được thanh toán hoặc đã hủy thì không cho hủy nữa
        if ($order->status === 'paid' || $order->status === 'cancelled') {
            return response()->json(['message' => 'Đơn hàng không thể hủy'], 400);
        }

        // Trừ lại nguyên liệu đã giữ (reserved_quantity)
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
        
        $order->status = 'cancelled';
        $order->save();

        broadcast(new NewOrderEvent($order, 'cancelled'))->toOthers();

        return response()->json(['message' => 'Đơn hàng đã được hủy']);
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

        return response()->json($promotions);
    }

    public function paymentTakeaway(Request $request)
    {
        $order = Orders::findOrFail($request->order_id);
        
        // Kiểm tra số tiền khách đưa
        // if ($request->amount_received < $order->total_price) {
        //     return response()->json(['message' => 'Số tiền khách đưa không đủ'], 400);
        // }

        // Tính toán số tiền khuyến mãi nếu có
        $discountAmount = 0;
        if ($request->promotion_id) {
        $promotion = Promotions::find($request->promotion_id);
        if ($promotion && $promotion->is_active) {
            // Kiểm tra điều kiện khuyến mãi (đơn hàng tối thiểu)
            if ($order->total_price >= $promotion->min_order_value) {
                if ($promotion->discount_type === 'percentage') {
                    // Nếu là giảm theo phần trăm: discount = tổng tiền * (phần trăm giảm / 100)
                    $discountAmount = $order->total_price * ($promotion->discount_value / 100);
                } else {
                    // Ngược lại, nếu là giảm tiền cố định
                    $discountAmount = $promotion->discount_value;
                }
            }
        }
    }

        // Tính toán giá cuối cùng
        $finalPrice = $order->total_price - $discountAmount;

        // Tạo bản ghi thanh toán
        Payments::create([
            'order_id' => $order->order_id,
            'amount_received' => $request->amount_received,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
            'payment_time' => Carbon::now()->format('Y-m-d H:i:s'),
            'payment_method' => $request->payment_method,
            'promotion_id' => $request->promotion_id,
            'employee_id' => Auth::user()->employee_id,
        ]);

        // Cập nhật trạng thái đơn hàng
        $order->status = 'paid';
        $order->save();

        return response()->json(['message' => 'Thanh toán thành công']);
    }

    public function markPaid(Request $request, $order_id)
    {
        $order = Orders::findOrFail($order_id);
        $order->status = 'paid';
        $order->save();

        return response()->json(['message' => 'Đơn hàng đã được cập nhật thành công.']);
    }

        
    public function printInvoice(Request $request, $order_id)
    {
        $order = Orders::with(['customer', 'orderItems.item'])->findOrFail($order_id);
        $pdf = PDF::loadView('staff_counter.confirmorder.invoice', compact('order'));
        return $pdf->stream('invoice_' . $order->order_id . '.pdf');
    }

}