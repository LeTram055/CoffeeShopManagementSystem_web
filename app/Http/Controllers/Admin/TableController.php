<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

use App\Models\Tables;
use App\Exports\TablesExport;
use App\Models\TableStatuses;

class TableController extends Controller
{
    public function index(Request $request)
    {

        $query = Tables::with('status');

        if ($request->filled('status')) {
            $query->where('status_id', $request->input('status'));
        }
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('table_number', 'like', '%' . $searchTerm . '%');
        }

        $tables = $query->get();
        $statuses = TableStatuses::all();

        return view('admin.table.index')
            ->with('tables', $tables)
            ->with('statuses', $statuses);
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new TablesExport, 'tables.xlsx');
    }

    public function destroy(Request $request)
    {
        $table = Tables::find($request->table_id);
        $table->delete();

        return redirect()->route('admin.table.index');
    }

    public function create()
    {   
        $statuses = TableStatuses::all();
        return view('admin.table.create')
            ->with('statuses', $statuses);
    }

    public function save(Request $request)
    {
        $request->validate([
            'table_number' => 'required|unique:tables,table_number',
            'capacity'     => 'required|numeric|min:1',
            
        ], [
            'table_number.required' => 'Vui lòng nhập số bàn',
            'table_number.unique'   => 'Số bàn đã tồn tại',
            'capacity.required'     => 'Vui lòng nhập số lượng ghế',
            'capacity.numeric'      => 'Số lượng ghế phải là số',
            'capacity.min'          => 'Số lượng ghế phải lớn hơn 0',
            
        ]);
        $table = new Tables();
        $table->table_number = $request->table_number;
        $table->capacity = $request->capacity;
        $table->status_id = $request->status_id ?? 1;
        $table->save();

        Session::flash('alert-success', 'Thêm mới bàn thành công');
        return redirect()->route('admin.table.index');
    }

    public function edit(Request $request)
    {
        $table = Tables::find($request->table_id);
        $statuses = TableStatuses::all();

        return view('admin.table.edit')
            ->with('table', $table)
            ->with('statuses', $statuses);
    }

    public function update(Request $request)
    {
        $request->validate([
            'table_number' => 'required|unique:tables,table_number,' . $request->table_id . ',table_id',
            'capacity'     => 'required|numeric|min:1',
            'status_id'    => 'required',
        ], [
            'table_number.required' => 'Vui lòng nhập số bàn',
            'table_number.unique'   => 'Số bàn đã tồn tại',
            'capacity.required'     => 'Vui lòng nhập số lượng ghế',
            'capacity.numeric'      => 'Số lượng ghế phải là số',
            'capacity.min'          => 'Số lượng ghế phải lớn hơn 0',
            'status_id.required'    => 'Vui lòng chọn trạng thái bàn',
        ]);

        $table = Tables::findOrFail($request->table_id);
        $table->table_number = $request->table_number;
        $table->capacity = $request->capacity;
        $table->status_id = $request->status_id;
        $table->save();

        Session::flash('alert-success', 'Cập nhật bàn thành công');
        return redirect()->route('admin.table.index');
    }
}