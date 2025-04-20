<?php
namespace App\Http\Controllers\Admin;

use App\Exports\WorkSchedulesExport;
use App\Http\Controllers\Controller;
use App\Models\WorkSchedules;
use App\Models\Employees;
use App\Models\Shifts;
use App\Models\Salaries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;

class WorkScheduleController extends Controller
{
    public function index(Request $request)
    {
        
        $sortField = $request->input('sort_field', 'schedule_id'); 
        $sortDirection = $request->input('sort_direction', 'asc'); 
        $perPage = $request->input('per_page', 10);

        // Giới hạn các trường hợp lệ để sắp xếp
        $validSortFields = ['schedule_id', 'employee_id', 'shift_id', 'work_date', 'status'];
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'schedule_id';
        }

        $query = WorkSchedules::with('employee', 'shift');

        // Xử lý tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('employee', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                })->orWhereHas('shift', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });

                // Tìm kiếm theo trạng thái
                $statusMap = [
                    'hoàn thành' => 'completed',
                    'chờ xử lý' => 'pending',
                    'vắng mặt' => 'absent',
                ];

                $searchTermLower = mb_strtolower($searchTerm, 'UTF-8');

                if (array_key_exists($searchTermLower, $statusMap)) {
                    $q->orWhere('status', $statusMap[$searchTermLower]);
                }

                // Tìm theo ngày (DD)
                if (preg_match('/^\d{2}$/', $searchTerm)) { 
                    $q->orWhereDay('work_date', $searchTerm);
                } 
                // Tìm theo ngày/tháng (DD/MM)
                elseif (preg_match('/^\d{2}\/\d{2}$/', $searchTerm)) {
                    [$day, $month] = explode('/', $searchTerm);
                    $q->orWhereDay('work_date', $day)
                        ->whereMonth('work_date', $month);
                } 
                // Tìm theo ngày/tháng/năm (DD/MM/YYYY)
                elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $searchTerm)) {
                    $date = \Carbon\Carbon::createFromFormat('d/m/Y', $searchTerm)->format('Y-m-d');
                    $q->orWhereDate('work_date', $date);
                }
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            try {
                $start = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
                $query->whereBetween('work_date', [$start, $end]);
            } catch (\Exception $e) {
                
            }
        }


        // Xử lý sắp xếp
        if ($sortField === 'work_date') {
        $query->orderBy($sortField, $sortDirection);
        } elseif ($sortField === 'status') {
        $query->orderByRaw("FIELD(status, 'completed', 'pending', 'absent') $sortDirection");
        } elseif ($sortField === 'schedule_id') {
        $query->orderByRaw("CAST(schedule_id AS UNSIGNED) $sortDirection");
        } elseif ($sortField === 'employee_id') {
        $query->leftJoin('employees', 'work_schedules.employee_id', '=', 'employees.employee_id')
            ->select('work_schedules.*', 'employees.name as employee_name') 
            ->orderBy('employees.name', $sortDirection);

        } elseif ($sortField === 'shift_id') {
        $query->join('shifts', 'work_schedules.shift_id', '=', 'shifts.shift_id')
                ->orderBy('shifts.name', $sortDirection);
        } else {
        $query->orderBy($sortField, $sortDirection);
        }


        $schedules = $query->paginate($perPage)->appends(request()->except('page'));

        return view('admin.workschedule.index', compact('schedules', 'sortField', 'sortDirection'));
    }

    public function scheduleView(Request $request)
    {
        // Lấy khoảng thời gian của tuần hiện tại
        // $startDate = now()->startOfWeek();
        // $endDate = now()->endOfWeek();
        // Lấy ngày bắt đầu và kết thúc từ yêu cầu
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfWeek();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfWeek();


        // Lấy danh sách ca làm việc, chỉ lấy giờ và phút
        $shifts = Shifts::select('shift_id', 'name', 'start_time', 'end_time')->get();

        // Lấy lịch làm việc từ database, kèm theo thông tin nhân viên và ca làm việc
        $workSchedules = WorkSchedules::with(['employee' => function ($query) {
                $query->select('employee_id', 'name', 'role'); // Lấy vai trò nhân viên
            }, 'shift'])
            ->whereBetween('work_date', [$startDate, $endDate])
            ->get();

        // Danh sách vai trò nhân viên bằng tiếng Việt
        $rolesTranslation = [
            'staff_counter' => 'Nhân viên quầy',
            'staff_serve' => 'Nhân viên phục vụ',
            'staff_barista' => 'Nhân viên pha chế',
        ];

        // Nhóm dữ liệu theo ngày và ca làm việc
        $schedulesByDateAndShift = [];
        foreach ($workSchedules as $schedule) {
            $dateKey = $schedule->work_date->format('Y-m-d');
            $shiftKey = $schedule->shift_id;

            $roleVietnamese = $rolesTranslation[$schedule->employee->role] ?? $schedule->employee->role;

            $schedulesByDateAndShift[$dateKey][$shiftKey][] = [
                'name' => $schedule->employee->name,
                'role' => $roleVietnamese
            ];
        }

        return view('admin.workschedule.schedule_view', compact('shifts', 'schedulesByDateAndShift', 'startDate', 'endDate'));
    }

    public function exportExcel()
    {
        return Excel::download(new WorkSchedulesExport, 'work_schedules.xlsx');
    }

    public function destroy(Request $request)
    {
        $schedule = WorkSchedules::find($request->schedule_id);
        // $employeeId = $schedule->employee_id;
        // $workDate = Carbon::parse($schedule->work_date);
        // $month = $workDate->month;
        // $year = $workDate->year;

        // // Kiểm tra bảng lương đã được trả chưa
        // $salary = Salaries::where('employee_id', $employeeId)
        //     ->where('month', $month)
        //     ->where('year', $year)
        //     ->where('status', 'paid')
        //     ->first();

        // if ($salary) {
        //     Session::flash('alert-danger', 'Không thể xóa lịch làm việc vì bảng lương của nhân viên trong tháng này đã được trả.');
        //     return redirect()->route('admin.workschedule.index');
        // }

        if ($schedule->status == 'completed') {
            Session::flash('alert-danger', 'Không thể xóa lịch làm việc đã được hoàn thành');
            return redirect()->route('admin.workschedule.index');
        }
        $schedule->delete();
        Session::flash('alert-success', 'Lịch làm việc đã được xóa');
        return redirect()->route('admin.workschedule.index');
    }
    

    public function create()
    {
        $employees = Employees::where('status', 'active')
                            ->where('role', '!=', 'admin')
                            ->get();
        $shifts = Shifts::all();
        return view('admin.workschedule.create', compact('employees', 'shifts'));
    }


    public function save(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'shift_id' => 'required|exists:shifts,shift_id',
            'work_date' => 'required|date',
            // 'status' => 'required|in:scheduled,completed,absent',
            // 'work_hours' => 'required|numeric|min:0|max:24',
        ],
        [
            'employee_id.required' => 'Vui lòng chọn nhân viên.',
            'employee_id.exists' => 'Nhân viên không tồn tại.',
            'shift_id.required' => 'Vui lòng chọn ca làm việc.',
            'shift_id.exists' => 'Ca làm việc không tồn tại.',
            'work_date.required' => 'Vui lòng chọn ngày làm việc.',
            'work_date.date' => 'Ngày làm việc không hợp lệ.',
            // 'status.required' => 'Vui lòng chọn trạng thái.',
            // 'status.in' => 'Trạng thái không hợp lệ.',
            // 'work_hours.required' => 'Vui lòng nhập số giờ làm việc.',
            // 'work_hours.numeric' => 'Số giờ làm việc phải là số.',
            // 'work_hours.min' => 'Số giờ làm việc không được nhỏ hơn 0.',
            // 'work_hours.max' => 'Số giờ làm việc không được lớn hơn 24.',
        ]);

        $employeeId = $request->employee_id;
        $workDate = Carbon::parse($request->work_date);
        $month = $workDate->month;
        $year = $workDate->year;

        // Kiểm tra bảng lương đã được trả chưa
        $salary = Salaries::where('employee_id', $employeeId)
            ->where('month', $month)
            ->where('year', $year)
            ->where('status', 'paid')
            ->first();

        if ($salary) {
            Session::flash('alert-danger', 'Không thể thêm lịch làm việc vì bảng lương của nhân viên trong tháng này đã được trả.');
            return redirect()->route('admin.workschedule.index');
        }

        WorkSchedules::create([
            'employee_id' => $request->employee_id,
            'shift_id' => $request->shift_id,
            'work_date' => $request->work_date,
            'status' => 'scheduled',
            'work_hours' => 0,
        ]);

        Session::flash('alert-success', 'Phân công ca làm việc thành công.');
        return redirect()->route('admin.workschedule.index');
    }

    public function edit(Request $request)
    {
        $schedule = WorkSchedules::findOrFail($request->schedule_id);
        
        $employeeId = $schedule->employee_id;
        $workDate = Carbon::parse($schedule->work_date);
        $month = $workDate->month;
        $year = $workDate->year;

        // Kiểm tra bảng lương đã được trả chưa
        $salary = Salaries::where('employee_id', $employeeId)
            ->where('month', $month)
            ->where('year', $year)
            ->where('status', 'paid')
            ->first();

        if ($salary) {
            Session::flash('alert-danger', 'Không thể sửa lịch làm việc vì bảng lương của nhân viên trong tháng này đã được trả.');
            return redirect()->route('admin.workschedule.index');
        }

        $employees = Employees::where('status', 'active')
                            ->where('role', '!=', 'admin')
                            ->get();
        $shifts = Shifts::all();

        return view('admin.workschedule.edit', compact('schedule', 'employees', 'shifts'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'shift_id' => 'required|exists:shifts,shift_id',
            'work_date' => 'required|date',
            'status' => 'required|in:scheduled,completed,absent',
            'work_hours' => 'required|numeric|min:0|max:24',
        ],
        [
            'employee_id.required' => 'Vui lòng chọn nhân viên.',
            'employee_id.exists' => 'Nhân viên không tồn tại.',
            'shift_id.required' => 'Vui lòng chọn ca làm việc.',
            'shift_id.exists' => 'Ca làm việc không tồn tại.',
            'work_date.required' => 'Vui lòng chọn ngày làm việc.',
            'work_date.date' => 'Ngày làm việc không hợp lệ.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'work_hours.required' => 'Vui lòng nhập số giờ làm việc.',
            'work_hours.numeric' => 'Số giờ làm việc phải là số.',
            'work_hours.min' => 'Số giờ làm việc không được nhỏ hơn 0.',
            'work_hours.max' => 'Số giờ làm việc không được lớn hơn 24.',
        ]);

        $schedule = WorkSchedules::findOrFail($request->schedule_id);

        $workHours = $request->work_hours;
            if ($request->status === 'completed' && $workHours == 0) {
                $shift = Shifts::findOrFail($request->shift_id);
                $start = Carbon::parse($shift->start_time);
                $end = Carbon::parse($shift->end_time);

                // Nếu ca kết thúc sau 0h (qua ngày), xử lý đúng thời gian
                if ($end->lessThanOrEqualTo($start)) {
                    $end->addDay();
                }

                $workHours = $end->diffInMinutes($start) / 60; // Tính số giờ
            }

        $workHours = abs($workHours); // Đảm bảo số giờ là dương

        $schedule->update([
            'employee_id' => $request->employee_id,
            'shift_id' => $request->shift_id,
            'work_date' => $request->work_date,
            'status' => $request->status,
            'work_hours' => $workHours,
        ]);

        
        Session::flash('alert-success', 'Cập nhật lịch làm việc thành công.');
        return redirect()->route('admin.workschedule.index');
    }


}