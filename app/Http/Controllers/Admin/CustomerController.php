<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Customers;
use App\Exports\CustomersExport;

class CustomerController extends Controller
{
    // Hiển thị danh sách khách hàng
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'customer_id');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = Customers::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('phone_number', 'like', '%' . $searchTerm . '%');
        }

        if ($sortField == 'name') {
            $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
        } elseif ($sortField == 'customer_id' || $sortField == 'phone_number') {
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        } else {
            $query->orderByRaw("CONVERT(customer_id USING utf8) COLLATE utf8_unicode_ci asc");
        }

        $customers = $query->get();

        return view('admin.customer.index')
        ->with('customers', $customers)
        ->with('sortField', $sortField)
        ->with('sortDirection', $sortDirection);
    }

    //Xuất excel
    public function exportExcel()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }

    // Thêm khách hàng
    public function create()
    {
        return view('admin.customer.create');
    }

    // Lưu khách hàng mới
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|numeric|digits:10|unique:customers,phone_number',
            'notes' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên khách hàng',
            'phone_number.required' => 'Vui lòng nhập số điện thoại',
            'phone_number.numeric' => 'Số điện thoại phải là số',
            'phone_number.digits' => 'Số điện thoại phải có 10 chữ số',
            'phone_number.unique' => 'Số điện thoại đã tồn tại',
        ]);

        $customer = new Customers();
        $customer->name = $request->name;
        $customer->phone_number = $request->phone_number;
        $customer->notes = $request->notes;
        $customer->save();

        Session::flash('alert-success', 'Thêm khách hàng thành công');
        return redirect()->route('admin.customer.index');
    }

    // Sửa khách hàng
    public function edit(Request $request)
    {
        $customer = Customers::find($request->customer_id);
        return view('admin.customer.edit')
        ->with('customer', $customer);
    }

    // Cập nhật thông tin khách hàng
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'numeric|digits:10',
            'notes' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên khách hàng',
            'phone_number.required' => 'Vui lòng nhập số điện thoại',
            'phone_number.numeric' => 'Số điện thoại phải là số',
            'phone_number.digits' => 'Số điện thoại phải có 10 chữ số',
            
        ]);

        $customer = Customers::find($request->customer_id);
        $customer->name = $request->name;
        $customer->phone_number = $request->phone_number;
        $customer->notes = $request->notes;

        $customer->save();

        Session::flash('alert-success', 'Cập nhật khách hàng thành công');
        return redirect()->route('admin.customer.index');
    }
}