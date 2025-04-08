<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Setting;


class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::first(); // Lấy thông tin quán
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
        ],
    [
            'store_name.required' => 'Tên quán không được để trống.',
            'address.required' => 'Địa chỉ không được để trống.',
            'phone_number.required' => 'Số điện thoại không được để trống.',
        ]);

        

        $setting = Setting::first();
        $setting->update($request->all());

        Session::flash('alert-success', 'Thông tin quán đã được cập nhật.');
        return redirect()->route('admin.settings.edit');
    }
}