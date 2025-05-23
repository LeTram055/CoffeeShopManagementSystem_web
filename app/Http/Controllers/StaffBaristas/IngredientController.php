<?php

namespace App\Http\Controllers\StaffBaristas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ingredients;
use App\Models\IngredientLogs;
use Illuminate\Support\Facades\Auth;
use App\Events\LowStockEvent;
use Illuminate\Support\Facades\Log;


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
            'change_value' => 'required|numeric|min:0',
            
            'reason' => 'required'
        ], [
            'change_value.required' => 'Vui lòng nhập số lượng thay đổi.',
            'change_value.numeric' => 'Số lượng thay đổi phải là số.',
            'change_value.min' => 'Số lượng thay đổi phải lớn hơn 0.',
            
            'reason.required' => 'Vui lòng nhập lý do thay đổi.'
            
        ]);

        $quantityChange = $request->change_value;
        
        if ($ingredient->quantity < $quantityChange) {
            return back()->with('error', 'Số lượng giảm không được lớn hơn số lượng hiện có.');
        }

        // Cập nhật số lượng nguyên liệu
        
        $ingredient->quantity = $ingredient->quantity - $quantityChange;
        $ingredient->last_updated = now();

        $ingredient->save();

        // Lưu vào logs
        IngredientLogs::create([
            'ingredient_id' => $ingredient->ingredient_id,
            'quantity_change' => -$quantityChange,
            'reason' => $request->reason,
            'price' => null,
            'new_cost_price' => $ingredient->cost_price,
            'log_type' => 'export',
            'employee_id' => Auth::user()->employee_id,
            'changed_at' => now(),
            
            
        ]);

        // Kiểm tra số lượng nguyên liệu
        if ($ingredient->quantity <= $ingredient->min_quantity) {
            // Gửi thông báo
            
            broadcast(new LowStockEvent($ingredient))->toOthers();
            Log::info("LowStockEvent đã được phát cho: " . $ingredient->name);
        }
        return redirect()->route('staff_baristas.ingredient.index')->with('alert-success', 'Cập nhật nguyên liệu thành công.');

    }
}