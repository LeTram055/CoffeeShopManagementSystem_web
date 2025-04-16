<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\BonusesPenalties;
use App\Models\Employees;
use App\Exports\BonusesPenaltiesExport;
use App\Models\Salaries;
use Carbon\Carbon;

class BonusPenaltyController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'bonus_penalty_id');
        $sortDirection = $request->input('sort_direction', 'asc');
        $perPage = $request->input('per_page', 10);

        $query = BonusesPenalties::with('employee');

        // Tìm kiếm 
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($q) use ($searchTerm) {
                $q->where('bonus_penalty_id', 'like', '%' . $searchTerm . '%')
                ->orWhere('amount', 'like', '%' . $searchTerm . '%')
                ->orWhere('reason', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('employee', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            });

            // Tìm kiếm theo ngày, ngày/tháng hoặc ngày/tháng/năm
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $searchTerm)) {
                // Định dạng ngày/tháng/năm
                $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $searchTerm)->format('Y-m-d');
                $query->orWhereDate('date', $formattedDate);
            } elseif (preg_match('/^\d{1,2}\/\d{1,2}$/', $searchTerm)) {
                // Định dạng ngày/tháng (không có năm, tìm kiếm trong năm hiện tại)
                $monthDay = explode('/', $searchTerm);
                $query->orWhereMonth('date', $monthDay[1])->whereDay('date', $monthDay[0]);
            } elseif (preg_match('/^\d{1,2}$/', $searchTerm)) {
                // Chỉ nhập ngày, tìm kiếm trong tháng/năm hiện tại
                $query->orWhereDay('date', $searchTerm);
            }
        }

        // Lọc theo khoảng ngày nếu có
        if ($request->filled('start_date') && $request->filled('end_date')) {
            try {
                $start = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
                $query->whereBetween('date', [$start, $end]);
            } catch (\Exception $e) {
                
            }
        }

        // Lọc theo tab
        if ($request->filled('type')) {
            $query->where('bonuses_penalties.type', $request->input('type'));
        }

        // Sắp xếp theo các cột
        if (in_array($sortField, ['bonus_penalty_id', 'amount', 'date'])) {
            $query->orderBy($sortField, $sortDirection);
        } elseif ($sortField == 'employee_name') {
            $query->join('employees', 'bonuses_penalties.employee_id', '=', 'employees.employee_id')
                ->orderBy('employees.name', $sortDirection);
        } else {
            $query->orderBy('bonus_penalty_id', 'asc');
        }

        $bonusesPenalties = $query->paginate($perPage)->appends(request()->except('page'));

        return view('admin.bonuspenalty.index', compact('bonusesPenalties', 'sortField', 'sortDirection'));
    }

    public function destroy(Request $request)
    {
        try {
            $bonusPenalty = BonusesPenalties::findOrFail($request->bonuspenalty_id);
            $employeeId = $bonusPenalty->employee_id;
            $date = Carbon::parse($bonusPenalty->date);
            $month = $date->month;
            $year = $date->year;

            // Kiểm tra bảng lương đã được trả chưa
            $salary = Salaries::where('employee_id', $employeeId)
                ->where('month', $month)
                ->where('year', $year)
                ->where('status', 'paid')
                ->first();

            if ($salary) {
                
                Session::flash('alert-danger', 'Không thể xóa thưởng/phạt vì bảng lương của nhân viên trong tháng này đã được trả.');
                return redirect()->route('admin.bonuspenalty.index');
            }
            
            $bonusPenalty->delete();

            Session::flash('alert-success', 'Xóa thành công');
            return redirect()->route('admin.bonuspenalty.index');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Có lỗi xảy ra khi xóa!');
            return redirect()->route('admin.bonuspenalty.index');
        }
    }

    public function exportExcel(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        return Excel::download(new BonusesPenaltiesExport($month, $year), 'bonuses_penalties.xlsx');
    }

    public function create()
    {
        $employees = Employees::where('status', 'active')
                            ->where('role', '!=', 'admin')
                            ->get();
        return view('admin.bonuspenalty.create', compact('employees'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bonus,penalty',
            'employee_id' => 'required|exists:employees,employee_id',
            'amount' => 'required|numeric',
            'reason' => 'required|string|max:255',
            'date' => 'required|date',
        ],
    [
            'type.required' => 'Vui lòng chọn loại',
            'type.in' => 'Loại không hợp lệ',
            'employee_id.required' => 'Vui lòng chọn nhân viên',
            'employee_id.exists' => 'Nhân viên không tồn tại',
            'amount.required' => 'Vui lòng nhập số tiền',
            'amount.numeric' => 'Số tiền phải là số',
            'reason.required' => 'Vui lòng nhập lý do',
            'reason.string' => 'Lý do phải là chuỗi',
            'reason.max' => 'Lý do không được vượt quá 255 ký tự',
            'date.required' => 'Vui lòng chọn ngày',
            'date.date' => 'Ngày không hợp lệ',
        ]);

        $employeeId = $request->employee_id;
        $date = Carbon::parse($request->date);
        $month = $date->month;
        $year = $date->year;

        // Kiểm tra bảng lương đã được trả chưa
        $salary = Salaries::where('employee_id', $employeeId)
            ->where('month', $month)
            ->where('year', $year)
            ->where('status', 'paid')
            ->first();

        if ($salary) {
                
            Session::flash('alert-danger', 'Không thể thêm thưởng/phạt vì bảng lương của nhân viên trong tháng này đã được trả.');
            return redirect()->route('admin.bonuspenalty.index');
        }

        $amount = $request->type === 'penalty' ? -abs($request->amount) : abs($request->amount);

        BonusesPenalties::create([
            'type' => $request->type,
            'employee_id' => $request->employee_id,
            'amount' => $amount,
            'reason' => $request->reason,
            'date' => $request->date,
        ]);

        Session::flash('alert-success', 'Thêm mới thành công');

        return redirect()->route('admin.bonuspenalty.index');
    }

    public function edit(Request $request)
    {
        $bonusPenalty = BonusesPenalties::findOrFail($request->bonus_penalty_id);
        $employeeId = $bonusPenalty->employee_id;
        $date = Carbon::parse($bonusPenalty->date);
        $month = $date->month;
        $year = $date->year;

        // Kiểm tra bảng lương đã được trả chưa
        $salary = Salaries::where('employee_id', $employeeId)
            ->where('month', $month)
            ->where('year', $year)
            ->where('status', 'paid')
            ->first();
        

        if ($salary) {
                
            Session::flash('alert-danger', 'Không thể sửa thưởng/phạt vì bảng lương của nhân viên trong tháng này đã được trả.');
            return redirect()->route('admin.bonuspenalty.index');
        }
        $employees = Employees::where('status', 'active')
                                ->where('role', '!=', 'admin')
                                ->get();
        return view('admin.bonuspenalty.edit', compact('bonusPenalty', 'employees'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bonus,penalty',
            'employee_id' => 'required|exists:employees,employee_id',
            'amount' => 'required|numeric',
            'reason' => 'required|string|max:255',
            'date' => 'required|date',
        ],
[
            'type.required' => 'Vui lòng chọn loại',
            'type.in' => 'Loại không hợp lệ',
            'employee_id.required' => 'Vui lòng chọn nhân viên',
            'employee_id.exists' => 'Nhân viên không tồn tại',
            'amount.required' => 'Vui lòng nhập số tiền',
            'amount.numeric' => 'Số tiền phải là số',
            'reason.required' => 'Vui lòng nhập lý do',
            'reason.string' => 'Lý do phải là chuỗi',
            'reason.max' => 'Lý do không được vượt quá 255 ký tự',
            'date.required' => 'Vui lòng chọn ngày',
            'date.date' => 'Ngày không hợp lệ',
        ]);


        $bonusPenalty = BonusesPenalties::findOrFail($request->bonus_penalty_id);
        
        $amount = $request->type === 'penalty' ? -abs($request->amount) : abs($request->amount);

        $bonusPenalty->update([
            'type' => $request->type,
            'employee_id' => $request->employee_id,
            'amount' => $amount,
            'reason' => $request->reason,
            'date' => $request->date,
        ]);

        Session::flash('alert-success', 'Cập nhật thành công');

        return redirect()->route('admin.bonuspenalty.index');
    }

}