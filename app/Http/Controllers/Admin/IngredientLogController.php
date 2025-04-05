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
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định tăng dần
        $perPage = $request->input('per_page', 10);
        
        // Thêm join vào truy vấn chính để có thể tìm kiếm theo ingredient_name và employee_name
        $query = IngredientLogs::query()
            ->leftJoin('ingredients', 'ingredient_logs.ingredient_id', '=', 'ingredients.ingredient_id')
            ->leftJoin('employees', 'ingredient_logs.employee_id', '=', 'employees.employee_id')
            ->select('ingredient_logs.*', 'ingredients.name as ingredient_name', 'employees.name as employee_name');

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('ingredient_logs.reason', 'like', '%' . $searchTerm . '%')
                ->orWhere('ingredients.name', 'like', '%' . $searchTerm . '%')
                ->orWhere('employees.name', 'like', '%' . $searchTerm . '%')
                ->orWhere('ingredient_logs.quantity_change', 'like', '%' . $searchTerm . '%')
                ->orWhere('ingredient_logs.price', 'like', '%' . $searchTerm . '%')
                ->orWhere('ingredient_logs.new_cost_price', 'like', '%' . $searchTerm . '%');

                $logTypeMapping = [
                    'nhập' => 'import',
                    'xuất' => 'export',
                    'điều chỉnh' => 'adjustment'
                ];
                if (array_key_exists(mb_strtolower($searchTerm), $logTypeMapping)) {
                    $q->orWhere('ingredient_logs.log_type', $logTypeMapping[mb_strtolower($searchTerm)]);
                }
                

            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $searchTerm)) { 
                // Nếu nhập ngày theo định dạng DD/MM/YYYY
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $searchTerm)->format('Y-m-d');
                $q->orWhereDate('changed_at', $date);
            } elseif (preg_match('/^\d{2}\/\d{4}$/', $searchTerm)) { 
                // Nếu nhập tháng/năm theo MM/YYYY
                [$month, $year] = explode('/', $searchTerm);
                $q->orWhere(function ($query) use ($month, $year) {
                    $query->whereMonth('changed_at', $month)
                          ->whereYear('changed_at', $year);
                });
            } elseif (preg_match('/^\d{4}$/', $searchTerm)) { 
                // Nếu nhập chỉ năm YYYY
                $q->orWhereYear('changed_at', $searchTerm);
            }
            });
        }

        if ($sortField === 'ingredient_name') {
            $query->orderBy('ingredients.name', $sortDirection);
        } elseif ($sortField === 'employee_name') {
            $query->orderBy('employees.name', $sortDirection);
        } elseif (in_array($sortField, ['log_id', 'quantity_change', 'reason', 'changed_at', 'price', 'new_cost_price'])) {
            $query->orderBy("ingredient_logs.$sortField", $sortDirection);
        } elseif ($sortField === 'log_type') {
        // Ánh xạ các giá trị log_type sang tiếng Việt
        $query->orderByRaw("FIELD(log_type, 'import', 'export', 'adjustment') $sortDirection");
        } else {
            $query->orderBy('log_id', 'asc');
        }

        $logs = $query->paginate($perPage)->appends(request()->except('page'));

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
}