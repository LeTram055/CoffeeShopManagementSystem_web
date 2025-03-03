<?php

namespace App\Http\Controllers\StaffCounter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

use App\Models\MenuItems;
use App\Models\Categories;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Tables;
use App\Models\Customers;
use App\Models\TableStatuses;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $categories = Categories::with(['items' => function($q) {
            $q->where('is_available', 1);
        }])->get();

        $tables = Tables::whereHas('status', function ($q) {
            $q->where('name', 'Trống');
        })->with('status')->get();

        $customers = Customers::get();
        
        return view('staff_counter.order.index')
        ->with('categories',$categories)
        ->with('tables',$tables)
        ->with('customers',$customers);
    }

    public function saveCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|numeric|digits:10|unique:customers,phone_number',
            'notes' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên khách hàng',
            'phone_number.required' => 'Vui lòng nhập số điện thoại',
            'phone_number.numeric' => 'Số điện thoại phải là số',
            'phone_number.digits' => 'Số điện thoại phải có 10 chữ số',
            'phone_number.unique' => 'Số điện thoại đã tồn tại',
        ]);

        $customer = Customers::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Khách hàng đã được thêm thành công',
            'customer_id' => $customer->customer_id,
            'name' => $customer->name,
            'phone_number' => $customer->phone_number,
        ]);
    }

    public function save(Request $request)
    {
        // Định nghĩa các rules xác thực
        $rules = [
            'order_type' => 'required|in:dine_in,takeaway',
            'items'      => 'required|array|min:1',
            'items.*.id' => 'required|exists:menu_items,item_id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.note' => 'nullable|string',
            'customer_id' => 'required|exists:customers,customer_id',
        ];

        $messages = [
            'order_type.required' => 'Vui lòng chọn loại đơn hàng',
            'order_type.in' => 'Loại đơn hàng không hợp lệ',
            'items.required' => 'Vui lòng chọn ít nhất 1 món ăn',
            'items.array' => 'Danh sách món ăn không hợp lệ',
            'items.min' => 'Danh sách món ăn không hợp lệ',
            'items.*.id.required' => 'Món ăn không hợp lệ',
            'items.*.id.exists' => 'Món ăn không tồn tại',
            'items.*.quantity.required' => 'Vui lòng nhập số lượng',
            'items.*.quantity.numeric' => 'Số lượng phải là số',
            'items.*.quantity.min' => 'Số lượng phải lớn hơn 0',
            'items.*.note.string' => 'Ghi chú không hợp lệ',
            'customer_id.required' => 'Vui lòng chọn khách hàng',
            'customer_id.exists' => 'Khách hàng không tồn tại',
        ];

        // Nếu đơn hàng là dùng tại chỗ thì bắt buộc phải có bàn
        if ($request->order_type === 'dine_in') {
            $rules['table_id'] = 'required|exists:tables,table_id';
            $messages['table_id.required'] = 'Vui lòng chọn bàn';
            $messages['table_id.exists'] = 'Bàn không tồn tại';
        } else {
            $request->merge(['table_id' => null]);
        }

        $validated = $request->validate($rules, $messages);

        

        // Tính tổng tiền đơn hàng
        $totalPrice = 0;
        foreach ($validated['items'] as $itemData) {
            $item = MenuItems::find($itemData['id']);
            $totalPrice += $item->price * $itemData['quantity'];
        }

        $tableId = $validated['table_id'] ?? null;
        if($validated['order_type'] === 'takeaway'){
            $status = 'pending_payment';
        } else {
            $status = 'confirmed';
        }

        // Tạo đơn hàng mới 
        $order = Orders::create([
            'table_id'   => $tableId,
            'customer_id'=> $validated['customer_id'],
            'order_type' => $validated['order_type'],
            'total_price'=> $totalPrice,
            'status'     => $status,
            'created_at' => Carbon::now(),
        ]);

        if ($tableId) {
        // Lấy trạng thái "Đang sử dụng" từ bảng table_statuses
        $status =TableStatuses::where('name', 'Đang sử dụng')->first();
        if ($status) {
            $table = Tables::find($tableId);
            if ($table) {
                $table->status_id = $status->status_id;
                $table->save();
            }
        }
    }

        // Tạo các bản ghi chi tiết đơn hàng
        foreach ($validated['items'] as $itemData) {
            OrderItems::create([
                'order_id' => $order->order_id,
                'item_id'  => $itemData['id'],
                'quantity' => $itemData['quantity'],
                'note'     => $itemData['note'] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã được tạo thành công',
            'order_id'=> $order->order_id,
        ]);
    }
}