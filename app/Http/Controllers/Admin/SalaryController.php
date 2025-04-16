<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalariesExport;
use App\Models\Salaries;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'salary_id'); 
        $sortDirection = $request->input('sort_direction', 'asc'); 
        $perPage = $request->input('per_page', 10);
        
        $query = Salaries::with('employee'); 

        // Tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('employee', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('username', 'like', '%' . $searchTerm . '%');
                })
                ->orWhere('salary_id', 'like', '%' . $searchTerm . '%')
                ->orWhere('month', 'like', '%' . $searchTerm . '%')
                ->orWhere('year', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('month')) {
            $query->where('month', $request->input('month'));
        }

        if ($request->filled('year')) {
            $query->where('year', $request->input('year'));
        }

        // Sắp xếp
        if (in_array($sortField, ['salary_id', 'month', 'year', 'total_hours', 'salary_per_hour', 'total_salary', 'total_bonus_penalty', 'final_salary', 'status'])) {
            $query->orderBy($sortField, $sortDirection);
        } elseif ($sortField === 'employee_id') {
        $query->join('employees', 'salaries.employee_id', '=', 'employees.employee_id')
                  ->orderBy('employees.name', $sortDirection);

        } else {
            $query->orderBy('salary_id', 'asc'); // Default sorting
        }

        $salaries = $query->paginate($perPage)->appends(request()->except('page'));

        $employees = Employees::where('status', 'active')
                                ->where('role', '!=', 'admin')
                                ->get();

        return view('admin.salary.index')
            ->with('salaries', $salaries)
            ->with('sortField', $sortField)
            ->with('sortDirection', $sortDirection)
            ->with('employees', $employees);
    }


    public function create()
    {
        $employees = Employees::where('status', 'active')
                                ->where('role', '!=', 'admin')
                                ->get();
        return view('admin.salary.create', compact('employees'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'employee_id' => 'nullable|exists:employees,employee_id', // Optional employee selection
        ]);

        $month = $request->input('month');
        $year = $request->input('year');
        $employeeId = $request->input('employee_id');

        $employees = Employees::where('status', 'active')
                                ->where('role', '!=', 'admin')
                                ->get();
        

        // Nếu không chọn nhân viên, tạo bảng lương cho tất cả nhân viên
        if (is_null($employeeId)) {
            foreach ($employees as $employee) {
                $salaryData = $employee->calculateSalary($month, $year);
                $finalSalary = $salaryData['total_salary'] + $salaryData['total_bonus_penalty'];

                // Kiểm tra nếu bảng lương đã tồn tại với trạng thái "đã trả"
                $existingSalary = Salaries::where('employee_id', $employee->employee_id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->where('status', 'paid')
                    ->first();

                if ($existingSalary) {
                    continue;
                }
                // Lưu vào database với trạng thái 'pending'
                Salaries::updateOrCreate(
                    ['employee_id' => $employee->employee_id, 'month' => $month, 'year' => $year],
                    [
                        'total_hours' => $salaryData['total_hours'],
                        'salary_per_hour' => $salaryData['salary_per_hour'],
                        'total_salary' => $salaryData['total_salary'],
                        'total_bonus_penalty' => $salaryData['total_bonus_penalty'],
                        'final_salary' => $salaryData['total_salary'] + $salaryData['total_bonus_penalty'],
                        'status' => 'pending' // Trạng thái chờ
                    ]
                );
            }
        } else {
            // Nếu chọn nhân viên cụ thể
            $employee = Employees::findOrFail($employeeId);
            $salaryData = $employee->calculateSalary($month, $year);

            // Kiểm tra nếu bảng lương đã tồn tại với trạng thái "đã trả"
            $existingSalary = Salaries::where('employee_id', $employee->employee_id)
                ->where('month', $month)
                ->where('year', $year)
                ->where('status', 'paid')
                ->first();

            if ($existingSalary) {
                // Nếu trạng thái là "đã trả"
                
                    return response()->json([
                    'status' => 'error',
                    'message' => 'Bảng lương đã tồn tại và đã được trả cho nhân viên này.'
                ], 400);
                
            }

            Salaries::updateOrCreate(
                ['employee_id' => $employee->employee_id, 'month' => $month, 'year' => $year],
                [
                    'total_hours' => $salaryData['total_hours'],
                    'salary_per_hour' => $salaryData['salary_per_hour'],
                    'total_salary' => $salaryData['total_salary'],
                    'total_bonus_penalty' => $salaryData['total_bonus_penalty'],
                    'final_salary' => $salaryData['total_salary'] + $salaryData['total_bonus_penalty'],
                    'status' => 'pending' // Trạng thái chờ
                ]
            );
        }

        return response()->json([
                    'status' => 'success',
                    'message' => 'Bảng lương được tạo thành công.'
                ], 200);
    }

    public function exportExcel(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        return Excel::download(new SalariesExport($month, $year), 'salaries.xlsx');
    }

    public function update(Request $request)
    {
        $salary = Salaries::findOrFail($request->salary_id);
        // $salary->status = $request->status;
        // $salary->save();

        // Session::flash('alert-success', 'Cập nhật trạng thái thành công.');
        // return back();

        if ($salary->status == 'paid') {
            return redirect()->back()->withErrors([
                'error' => 'Bảng lương này đã được trả trước đó.'
            ]);
        }

        $salary->update([
            'status' => 'paid',
            'paid_date' => now(), // Lưu ngày trả lương
        ]);

        Session::flash('alert-success', 'Bảng lương đã được xác nhận trả thành công.');
        return redirect()->route('admin.salary.index');
    }


    public function showDetails($salary_id)
    {
        // Lấy thông tin lương
        $salary = Salaries::with(['employee', 'employee.workSchedules', 'employee.bonusesPenalties'])
            ->findOrFail($salary_id);

        return view('admin.salary.details', compact('salary'));
    }

    
    public function exportPdf($salaryId)
    {

        $salary = Salaries::with(['employee', 'employee.workSchedules', 'employee.bonusesPenalties'])
        ->findOrFail($salaryId);
        // Tạo view PDF
        $pdf = PDF::loadView('admin.salary.pdf', compact('salary'));

        // Xuất file PDF
        return $pdf->stream('salaries_'. ($salary->employee->name) . 'tháng' .  ($salary->month) . '_' . ($salary->year) . '.pdf');
    }
}