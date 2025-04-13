<?php

namespace App\Http\Controllers\StaffBaristas;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Models\MenuItems;

class MenuController extends Controller
{
    public function index()
{
    $categories = Categories::all();
    return view('staff_baristas.menu.index')
    ->with('categories', $categories);
}

public function toggleAvailability(Request $request, $id)
{
    $item = MenuItems::findOrFail($id);
    if (!$request->is_available) {
    // Kiểm tra nếu không có lý do
        if (!$request->has('reason') || empty(trim($request->reason))) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập lý do khi tắt trạng thái sản phẩm.'
            ], 422);
        }

        // Cập nhật trạng thái và lưu lý do
        $item->is_available = false;
        $item->reason = $request->reason;
        $item->save();
    } else {
        // Nếu bật trạng thái (is_available = true), xóa lý do
        $item->is_available = true;
        $item->reason = null;
        $item->save();
    }

    return response()->json([
        'success' => true,
        'status' => $item->is_available,
        'message' => $item->is_available ? 'Sản phẩm đã được cập nhật có sẵn.' : 'Sản phẩm đã được cập nhật không có sẵn.'
    ]);
}

}