<?php
namespace App\Http\Controllers\Admin;

use App\Models\Shifts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ShiftsExport;

class ShiftController extends Controller
{
    public function index(Request $request)
{
    $sortField = $request->input('sort_field', 'shift_id'); 
    $sortDirection = $request->input('sort_direction', 'asc'); 

    $query = Shifts::query();

    // Tìm kiếm
    if ($request->filled('search')) {
        $searchTerm = $request->input('search');
        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%')
              ->orWhere('start_time', 'like', '%' . $searchTerm . '%')
              ->orWhere('end_time', 'like', '%' . $searchTerm . '%');

            // Nếu nhập giờ theo định dạng HH:MM
            if (preg_match('/^\d{2}:\d{2}$/', $searchTerm)) {
                $q->orWhereTime('start_time', $searchTerm)
                  ->orWhereTime('end_time', $searchTerm);
            }
        });
    }

    // Sắp xếp
    if (in_array($sortField, ['name', 'start_time', 'end_time'])) {
        $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
    } elseif ($sortField == 'shift_id') {
        $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
    } else {
        $query->orderByRaw("CONVERT(shift_id USING utf8) COLLATE utf8_unicode_ci asc");
    }

    $shifts = $query->get();

    return view('admin.shift.index')
        ->with('shifts', $shifts)
        ->with('sortField', $sortField)
        ->with('sortDirection', $sortDirection);
}


    public function destroy(Request $request)
    {
        $shift = Shifts::find($request->shift_id);
        $shift->delete();
        Session::flash('alert-success', 'Ca làm việc đã được xóa');
        return redirect()->route('admin.shift.index');
    }

    public function exportExcel()
    {
        return Excel::download(new ShiftsExport, 'shifts.xlsx');
    }

    public function create()
    {
        return view('admin.shift.create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:shifts,name',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ],
        [
            'name.required' => 'Vui lòng nhập tên ca',
            'name.unique' => 'Tên ca làm việc đã tồn tại',
            'start_time.required' => 'Vui lòng nhập thời gian bắt đầu',
            'end_time.required' => 'Vui lòng nhập thời gian kết thúc',
            'end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu'
        ]);

        $shift = new Shifts();
        $shift->name = $request->name;
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->save();

        Session::flash('alert-success', 'Ca làm việc đã được thêm.');
        return redirect()->route('admin.shift.index');
    }

    public function edit(Request $request)
    {
        $shift = Shifts::find($request->shift_id);
        return view('admin.shift.edit')
        ->with('shift', $shift);
    }

    public function update(Request $request)
    {
        $shift = Shifts::find($request->shift_id);
        $request->validate([
            'name' => 'required|unique:shifts,name,' . $request->shift_id . ',shift_id',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ],
        [
            'name.required' => 'Vui lòng nhập tên ca',
            'name.unique' => 'Tên ca làm việc đã tồn tại',
            'start_time.required' => 'Vui lòng nhập thời gian bắt đầu',
            'end_time.required' => 'Vui lòng nhập thời gian kết thúc',
            'end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu'
        ]);



        $shift->name = $request->name;
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->save();

        Session::flash('alert-success', 'Ca làm việc đã được cập nhật.');
        return redirect()->route('admin.shift.index');
    }
}