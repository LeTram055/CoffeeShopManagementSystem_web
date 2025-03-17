<?php

namespace App\Http\Controllers\StaffBaristas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ingredients;
use App\Models\IngredientLogs;
use Illuminate\Support\Facades\Auth;


class IngredientController extends Controller
{
    public function index(Request $request)
    {
        
        $sortField = $request->get('sort_field', 'ingredient_id'); 
        $sortDirection = $request->get('sort_direction', 'asc'); 

        $query = Ingredients::query();
        
        if ($sortField == 'name') {
                $query->orderByRaw("CONVERT($sortField USING utf8mb4) COLLATE utf8mb4_unicode_ci $sortDirection");
            } elseif ($sortField == 'unit') {
                $query->orderByRaw("CONVERT($sortField USING utf8mb4) COLLATE utf8mb4_unicode_ci $sortDirection");
            } elseif ($sortField == 'ingredient_id' || $sortField == 'quantity' || $sortField == 'min_quantity') {
                $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
            }
            elseif ($sortField === 'last_updated') {
                $query->orderBy('last_updated', $sortDirection);
            } else {
                $query->orderBy('ingredient_id', 'asc');
            }

            $ingredients = $query->get();

        return view('staff_baristas.ingredient.index', compact('ingredients', 'sortField', 'sortDirection'));
    }


    public function update(Request $request, $id)
    {
        $ingredient = Ingredients::findOrFail($id);

        $validatedData = $request->validate([
            'change_value' => 'required|numeric|min:1',
            'change_type' => 'required|in:increase,decrease',
            'reason' => 'required'
        ], [
            'change_value.required' => 'Vui lòng nhập số lượng thay đổi.',
            'change_value.numeric' => 'Số lượng thay đổi phải là số.',
            'change_value.min' => 'Số lượng thay đổi phải lớn hơn 0.',
            'change_type.required' => 'Vui lòng chọn loại thay đổi.',
            'change_type.in' => 'Loại thay đổi không hợp lệ.',
            'reason.required' => 'Vui lòng nhập lý do thay đổi.'
            
        ]);

        $quantityChange = $validatedData['change_value'];

        if ($validatedData['change_type'] === 'decrease' && $ingredient->quantity < $quantityChange) {
            return back()->with('error', 'Số lượng giảm không được lớn hơn số lượng hiện có.');
        }

        // Cập nhật số lượng nguyên liệu
        $ingredient->quantity = $validatedData['change_type'] === 'increase'
            ? $ingredient->quantity + $quantityChange
            : $ingredient->quantity - $quantityChange;
        $ingredient->last_updated = now();

        $ingredient->save();

        // Lưu vào logs
        IngredientLogs::create([
            'ingredient_id' => $ingredient->ingredient_id,
            'quantity_change' => $validatedData['change_type'] === 'increase' ? $quantityChange : -$quantityChange,
            'reason' => $request->reason,
            'employee_id' => Auth::user()->employee_id,
            'changed_at' => now()
        ]);

        return redirect()->route('staff_baristas.ingredient.index')->with('alert-success', 'Cập nhật nguyên liệu thành công.');

    }
}