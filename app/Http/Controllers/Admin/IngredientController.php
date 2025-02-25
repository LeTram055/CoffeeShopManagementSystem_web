<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use App\Models\Ingredients;
use App\Models\IngredientLogs;
use App\Exports\IngredientsExport;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'ingredient_id'); // Mặc định sắp xếp theo ID
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định sắp xếp tăng dần

        $query = Ingredients::query();

        if ($request->filled('search')) {
        $searchTerm = $request->input('search');

        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%')
              ->orWhere('unit', 'like', '%' . $searchTerm . '%')
              ->orWhere('quantity', 'like', '%' . $searchTerm . '%')
              ->orWhere('min_quantity', 'like', '%' . $searchTerm . '%');

            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $searchTerm)) { 
                // Nếu nhập ngày theo định dạng DD/MM/YYYY
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $searchTerm)->format('Y-m-d');
                $q->orWhereDate('last_updated', $date);
            } elseif (preg_match('/^\d{2}\/\d{4}$/', $searchTerm)) { 
                // Nếu nhập tháng/năm theo MM/YYYY
                [$month, $year] = explode('/', $searchTerm);
                $q->orWhere(function ($query) use ($month, $year) {
                    $query->whereMonth('last_updated', $month)
                          ->whereYear('last_updated', $year);
                });
            } elseif (preg_match('/^\d{4}$/', $searchTerm)) { 
                // Nếu nhập chỉ năm YYYY
                $q->orWhereYear('last_updated', $searchTerm);
            }
        });
    }
        // Xử lý sắp xếp
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

        return view('admin.ingredient.index')
            ->with('ingredients', $ingredients)
            ->with('sortField', $sortField)
            ->with('sortDirection', $sortDirection);
    }

    // Xuất Excel
    public function exportExcel()
    {
        return Excel::download(new IngredientsExport, 'ingredients.xlsx');
    }

    // Xóa nguyên liệu
    public function destroy(Request $request)
    {
        $ingredient = Ingredients::find($request->input('ingredient_id'));
        if ($ingredient->menuIngredients()->count() > 0) {
            Session::flash('alert-danger', 'Không thể xóa nguyên liệu này vì nó đang được sử dụng.');
            return redirect()->route('admin.ingredient.index');
        }

        $ingredient->delete();
        Session::flash('alert-success', 'Xóa nguyên liệu thành công');
        return redirect()->route('admin.ingredient.index');
    }

    // Thêm nguyên liệu
    public function create()
    {
        return view('admin.ingredient.create');
    }

    // Lưu nguyên liệu
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:ingredients,name',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required',
            'min_quantity' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Vui lòng nhập tên nguyên liệu',
            'name.unique' => 'Nguyên liệu đã tồn tại',
            'quantity.required' => 'Vui lòng nhập số lượng',
            'quantity.numeric' => 'Số lượng phải là số',
            'unit.required' => 'Vui lòng nhập đơn vị tính',
            'min_quantity.required' => 'Vui lòng nhập số lượng tối thiểu',
            'min_quantity.numeric' => 'Số lượng tối thiểu phải là số',
        ]);

        $ingredient = new Ingredients();
        $ingredient->name = $request->name;
        $ingredient->quantity = $request->quantity;
        $ingredient->unit = $request->unit;
        $ingredient->min_quantity = $request->min_quantity;
        $ingredient->save();

        $ingredientLog = new IngredientLogs();
        $ingredientLog->ingredient_id = $ingredient->ingredient_id;
        $ingredientLog->quantity_change = $ingredient->quantity; 
        $ingredientLog->reason = 'Thêm mới nguyên liệu';
        $ingredientLog->employee_id = 2; 
        $ingredientLog->changed_at = now();
        $ingredientLog->save();

        Session::flash('alert-success', 'Thêm nguyên liệu thành công');
        return redirect()->route('admin.ingredient.index');
    }

    //Sửa nguyên liệu
    public function edit(Request $request)
    {
        $ingredient = Ingredients::find($request->ingredient_id);
        return view('admin.ingredient.edit')->with('ingredient', $ingredient);
    }

    // Cập nhật nguyên liệu
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:ingredients,name,' . $request->ingredient_id . ',ingredient_id',
            'change_value' => 'nullable|numeric|min:0',
            'unit' => 'required',
            'min_quantity' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Vui lòng nhập tên nguyên liệu',
            'name.unique' => 'Nguyên liệu đã tồn tại',
            'change_value.numeric' => 'Số lượng thay đổi phải là số',
            'unit.required' => 'Vui lòng nhập đơn vị tính',
            'min_quantity.required' => 'Vui lòng nhập số lượng tối thiểu',
            'min_quantity.numeric' => 'Số lượng tối thiểu phải là số',
        ]);

        $ingredient = Ingredients::find($request->ingredient_id);
        
        $oldQuantity = $ingredient->quantity;

        if ($request->filled('change_value')) {
            $changeValue = $request->change_value;
            if ($request->change_type === 'increase') {
                $newQuantity = $oldQuantity + $changeValue;
            } else {
                $newQuantity = $oldQuantity - $changeValue;
            }
        } else {
            $newQuantity = $oldQuantity; // Nếu không có thay đổi
        }

        $ingredient->name = $request->name;
        $ingredient->quantity = $newQuantity;
        $ingredient->unit = $request->unit;
        $ingredient->min_quantity = $request->min_quantity;
        $ingredient->save();

        if ($oldQuantity != $newQuantity) {
            $ingredientLog = new IngredientLogs();
            $ingredientLog->ingredient_id = $ingredient->ingredient_id;
            $ingredientLog->quantity_change = $newQuantity - $oldQuantity;
            $ingredientLog->reason = $request->reason ?? 'Cập nhật số lượng nguyên liệu';
            $ingredientLog->employee_id = Auth::user()->employee_id;
            $ingredientLog->changed_at = now();
             $ingredientLog->save();
        
        }

        Session::flash('alert-success', 'Cập nhật nguyên liệu thành công');
        return redirect()->route('admin.ingredient.index');
    }
}