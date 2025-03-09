<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Models\Employees;

class AuthAppController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $employee = Employees::where('username', $credentials['username'])->first();

        if (!$employee) {
            return response()->json(['message' => 'Tên đăng nhập không đúng'], 401);
        }

        if (!Hash::check($credentials['password'], $employee->password)) {
            return response()->json(['message' => 'Mật khẩu không đúng'], 401);
        }
        // Chỉ cho phép staff_barista và staff_serve đăng nhập
        if (!in_array($employee->role, ['staff_barista', 'staff_serve'])) {
            return response()->json(['message' => 'Bạn không có quyền đăng nhập'], 403);
        }

        return response()->json($employee);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);

        $employee = Employees::where('username', $request->username)->first();

        if (!$employee) {
            return response()->json(['message' => 'Tên đăng nhập không đúng'], 404);
        }

        if (!Hash::check($request->old_password, $employee->password)) {
            return response()->json(['message' => 'Mật khẩu cũ không đúng'], 400);
        }

        $employee->password = Hash::make($request->new_password);
        $employee->save();

        return response()->json(['message' => 'Đổi mật khẩu thành công']);
    }
}