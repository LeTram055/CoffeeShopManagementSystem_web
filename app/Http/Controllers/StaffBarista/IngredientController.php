<?php

namespace App\Http\Controllers\StaffBarista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ingredients;
use App\Models\IngredientLogs;

class IngredientController extends Controller
{
    // Lấy danh sách tất cả nguyên liệu
    public function index()
    {
        $ingredients = Ingredients::all();
        return response()->json($ingredients);
    }

    public function updateQuantity(Request $request, $id)
{
    // $request->validate([
    //     'quantity' => 'required|numeric|min:0',
    //     'employee_id' => 'required|exists:employees,employee_id',
    //     'reason' => 'required|string',
    // ]);

    $ingredient = Ingredients::findOrFail($id);
    $oldQuantity = $ingredient->quantity;
    $newQuantity = $request->quantity;

    if ($oldQuantity != $newQuantity) {
        // Cập nhật số lượng nguyên liệu
        $quantity = $oldQuantity + $newQuantity;
        $ingredient->update(['quantity' => $quantity]);

        // Ghi log thay đổi số lượng
        IngredientLogs::create([
            'ingredient_id' => $ingredient->ingredient_id,
            'quantity_change' => $newQuantity,
            'reason' => $request->reason,
            'employee_id' => $request->employee_id,
            'changed_at' => now(),
        ]);
    }

    return response()->json(['message' => 'Cập nhật thành công']);
}

}