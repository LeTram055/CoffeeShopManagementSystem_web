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

    // public function getProfile(Request $request)
    // {
    // $employee = Employees::where('username', $request->username)->first(); // Lấy thông tin nhân viên đang đăng nhập
    //     return response()->json($employee);
    // }

    public function getWorkSchedules(Request $request) 
    {
        $employee = Employees::where('username', $request->query('username'))->firstOrFail();
        $query = $employee->workSchedules()->with('shift');

        if ($request->has('month')) {
            $query->whereMonth('work_date', $request->query('month'));
        }
        if ($request->has('year')) {
            $query->whereYear('work_date', $request->query('year'));
        }

        return response()->json($query->get());
    }

    public function getBonusesPenalties(Request $request) {
        $employee = Employees::where('username', $request->query('username'))->firstOrFail();
        $query = $employee->bonusesPenalties();

        if ($request->has('month')) {
            $query->whereMonth('date', $request->query('month'));
        }
        if ($request->has('year')) {
            $query->whereYear('date', $request->query('year'));
        }

        return response()->json($query->get());
    }

    public function getSalaries(Request $request) {
        $employee = Employees::where('username', $request->query('username'))->firstOrFail();
        $query = $employee->salaries();

        if ($request->has('month')) {
            $query->where('month', $request->query('month'));
        }
        if ($request->has('year')) {
            $query->where('year', $request->query('year'));
        }

        return response()->json($query->get());
    }

}