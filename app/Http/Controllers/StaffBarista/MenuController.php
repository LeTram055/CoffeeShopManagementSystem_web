<?php

namespace App\Http\Controllers\StaffBarista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\MenuItems;
use App\Models\Categories;

class MenuController extends Controller
{
    // Lấy danh sách menu
    public function index()
    {
        try {
            $menuItems = MenuItems::with('ingredients.ingredient')->get();

            // Gán đường dẫn đầy đủ cho mỗi ảnh
            foreach ($menuItems as $item) {
                $item->image_url = url(Storage::url('uploads/' . $item->image_url)); // Đường dẫn đầy đủ
            }

            return response()->json(['success' => true, 'data' => $menuItems], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi lấy menu', 'error' => $e->getMessage()], 500);
        }
    }

    public function getCategory() {
        try {
            $categories = Categories::all();
            return response()->json(['success' => true, 'data' => $categories], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi lấy danh mục', 'error' => $e->getMessage()], 500);
        }
    }

    public function toggleAvailability($id)
{
    try {
        $menuItem = MenuItems::findOrFail($id);
        $menuItem->is_available = !$menuItem->is_available;
        $menuItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => $menuItem
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi cập nhật',
            'error' => $e->getMessage()
        ], 500);
    }
}
}