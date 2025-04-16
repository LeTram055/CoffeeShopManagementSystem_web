<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Employees;
use App\Exports\EmployeesExport;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'employee_id'); 
        $sortDirection = $request->input('sort_direction', 'asc'); 
        $perPage = $request->input('per_page', 10);

        $query = Employees::query();
        $query->where('role', '!=', 'admin');

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('phone_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('address', 'like', '%' . $searchTerm . '%')
                  ->orWhere('username', 'like', '%' . $searchTerm . '%')
                  ->orWhere('role', 'like', '%' . $searchTerm . '%')
                  ->orWhere('status', 'like', '%' . $searchTerm . '%')
                  ->orWhere('hourly_rate', 'like', '%' . $searchTerm . '%');

                  if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $searchTerm)) { 
                    // Nếu nhập ngày theo định dạng DD/MM/YYYY
                    $date = \Carbon\Carbon::createFromFormat('d/m/Y', $searchTerm)->format('Y-m-d');
                    $q->orWhereDate('start_date', $date);
                } elseif (preg_match('/^\d{2}\/\d{4}$/', $searchTerm)) { 
                    // Nếu nhập tháng/năm theo MM/YYYY
                    [$month, $year] = explode('/', $searchTerm);
                    $q->orWhere(function ($query) use ($month, $year) {
                        $query->whereMonth('start_date', $month)
                            ->whereYear('start_date', $year);
                    });
                } elseif (preg_match('/^\d{4}$/', $searchTerm)) { 
                    // Nếu nhập chỉ năm YYYY
                    $q->orWhereYear('start_date', $searchTerm);
                }
            });
        }

        if ($request->filled('type')) {
            $query->where('employees.role', $request->input('type'));
        }

        if ($sortField == 'name' || $sortField == 'email' || $sortField == 'address' || $sortField == 'username' || $sortField == 'status') {
            $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
        } elseif ($sortField == 'role') {
            
            $query->orderByRaw("
                CASE 
                    
                    WHEN role = 'staff_counter' THEN 'Nhân viên thu ngân'
                    WHEN role = 'staff_serve' THEN 'Nhân viên phục vụ'
                    WHEN role = 'staff_barista' THEN 'Nhân viên pha chế'
                    ELSE 5
                END $sortDirection
            ");
        }
        elseif ($sortField == 'employee_id') {
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        } elseif ($sortField == 'phone_number') {
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        } elseif ($sortField == 'hourly_rate') {
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        } elseif ($sortField == 'start_date') {
            $query->orderByRaw("CAST($sortField AS DATE) $sortDirection");
        }
        else {
            $query->orderByRaw("CONVERT(employee_id USING utf8) COLLATE utf8_unicode_ci asc");
        }

        $employees = $query->paginate($perPage)->appends(request()->except('page'));

        return view('admin.employee.index')
            ->with('employees', $employees)
            ->with('sortField', $sortField)
            ->with('sortDirection', $sortDirection);
    
    }

    public function exportExcel()
    {
        return Excel::download(new EmployeesExport, 'employees.xlsx');
    }

    public function destroy(Request $request)
    {
        $employee = Employees::find($request->employee_id);
        if ($employee->ingredientLogs->count() > 0 || $employee->payments->count() > 0 || $employee->workSchedules->count() > 0 || $employee->salaries->count() > 0 || $employee->bonusesPenalties->count() > 0) {
            Session::flash('alert-danger', 'Không thể xóa nhân viên này vì đã có dữ liệu liên quan');
            return redirect()->route('admin.employee.index');
        }
        
        $employee->delete();

        Session::flash('alert-success', 'Xóa nhân viên thành công');
        return redirect()->route('admin.employee.index');
    }

    public function create()
    {
        return view('admin.employee.create');
    }

    public function save(Request $request)
    {
      

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:employees,username',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:employees,email',
            'phone_number' => 'required|numeric|digits_between:10,11|unique:employees,phone_number',
            'address' => 'nullable|string',
            'start_date' => 'required|date',
            'hourly_rate' => 'required|min:0'
        ], [
            'name.required' => 'Vui lòng nhập tên nhân viên',
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'username.unique' => 'Tên đăng nhập đã tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'phone_number.required' => 'Vui lòng nhập số điện thoại',
            'phone_number.numeric' => 'Số điện thoại phải là số',
            'phone_number.digits_between' => 'Số điện thoại phải có từ 10-11 số',
            'phone_number.unique' => 'Số điện thoại đã tồn tại',
            'start_date.required' => 'Vui lòng nhập ngày bắt đầu',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'hourly_rate' => 'Vui lòng nhập lương theo giờ',
            'hourly_rate.min' => 'Lương phải lớn hơn 0'
        ]);

        

        $employee = new Employees();
        $employee->name = $request->name;
        $employee->username = $request->username;
        $employee->password = bcrypt($request->password);
        $employee->role = $request->role;
        
        $employee->email = $request->email;
        $employee->phone_number = $request->phone_number;
        $employee->address = $request->address;
        $employee->start_date = $request->start_date;
        $employee->hourly_rate = $request->hourly_rate;
        $employee->save();

        Session::flash('alert-success', 'Thêm nhân viên thành công');
        return redirect()->route('admin.employee.index');
    }

    public function edit(Request $request)
    {
        $employee = Employees::find($request->employee_id);
        
        return view('admin.employee.edit')
        ->with('employee', $employee);
    }

    public function update(Request $request)
    {
        $employee = Employees::find($request->employee_id);

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:employees,username,' . $employee->employee_id . ',employee_id',
            
            'email' => 'required|email|unique:employees,email,' . $employee->employee_id . ',employee_id',
            'phone_number' => 'required|numeric|digits_between:10,11|unique:employees,phone_number,' . $employee->employee_id . ',employee_id',
            'address' => 'nullable|string',
            'start_date' => 'required|date',
            'hourly_rate' => 'required|min:0'
        ],
        [
            'name.required' => 'Vui lòng nhập tên nhân viên',
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'username.unique' => 'Tên đăng nhập đã tồn tại',
            
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'phone_number.required' => 'Vui lòng nhập số điện thoại',
            'phone_number.numeric' => 'Số điện thoại phải là số',
            'phone_number.digits_between' => 'Số điện thoại phải có từ 10-11 số',
            'phone_number.unique' => 'Số điện thoại đã tồn tại',
            'start_date.required' => 'Vui lòng nhập ngày bắt đầu',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'hourly_rate' => 'Vui lòng nhập lương theo giờ',
            'hourly_rate.min' => 'Lương phải lớn hơn 0'
        ]
    );

        $employee->name = $request->name;
        $employee->username = $request->username;
        $employee->role = $request->role;
        $employee->status = $request->status;
        $employee->email = $request->email;
        $employee->phone_number = $request->phone_number;
        $employee->address = $request->address;
        $employee->start_date = $request->start_date;
        $employee->hourly_rate = $request->hourly_rate;
        $employee->save();

        Session::flash('alert-success', 'Cập nhật nhân viên thành công');
        return redirect()->route('admin.employee.index');
    }

    
}