<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

use App\Models\IngredientLogs;
use App\Exports\IngredientLogsExport;

class IngredientLogController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'log_id'); // Sắp xếp theo ID mặc định
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định giảm dần

        $query = IngredientLogs::with(['ingredient', 'employee']);


        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('action', 'like', '%' . $searchTerm . '%');
        }

        if ($sortField === 'ingredient_name') {
            $query->join('ingredients', 'ingredient_logs.ingredient_id', '=', 'ingredients.ingredient_id')
                ->orderBy('ingredients.name', $sortDirection);
        } elseif ($sortField === 'employee_name') {
            $query->join('employees', 'ingredient_logs.employee_id', '=', 'employees.employee_id')
                ->orderBy('employees.name', $sortDirection);
        } elseif (in_array($sortField, ['log_id', 'quantity_change', 'reason', 'changed_at'])) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('log_id', 'asc');
        }


        $logs = $query->get(); // Phân trang

        return view('admin.ingredientlog.index')
        ->with('ingredientLogs', $logs)
            ->with('sortField', $sortField)
            ->with('sortDirection', $sortDirection);
    }

    // Xuất Excel
    public function exportExcel()
    {
        return Excel::download(new IngredientLogsExport, 'ingredientlogs.xlsx');
    }

    // Xóa log nguyên liệu
    public function destroy(Request $request)
    {
        $log = IngredientLogs::find($request->input('log_id'));
        if (!$log) {
            Session::flash('alert-danger', 'Không tìm thấy log nguyên liệu.');
            return redirect()->route('admin.ingredientlog.index');
        }

        $log->delete();
        Session::flash('alert-success', 'Xóa log nguyên liệu thành công');
        return redirect()->route('admin.ingredientlog.index');
    }
}