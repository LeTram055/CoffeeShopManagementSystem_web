<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Employees;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        // Validate dữ liệu đầu vào
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Tìm nhân viên theo username
        $employee = Employees::where('username', $request->username)->first();

        if (!$employee) {
            return back()->withErrors(['username' => 'Tên đăng nhập không tồn tại.'])->withInput();
        }

        if ($employee->role != 'admin' && $employee->role != 'staff_counter' && $employee->role != 'staff_barista') {
            return back()->withErrors(['username' => 'Tài khoản không có quyền truy cập.'])->withInput();
        }

        // Kiểm tra mật khẩu 
        if (!Hash::check($request->password, $employee->password)) {
            return back()->withErrors(['password' => 'Mật khẩu không đúng.'])->withInput();
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $employee = Auth::user();

            // Kiểm tra role để chuyển hướng phù hợp
            if ($employee->role === 'admin') {
                return redirect()->route('admin.home.index');
            } elseif ($employee->role === 'staff_counter') {
                return redirect()->route('staff_counter.home.index');
            } elseif ($employee->role === 'staff_barista') {
                return redirect()->route('staff_baristas.order.index');
            } else {
                // Nếu role không hợp lệ, đăng xuất và thông báo lỗi
                Auth::logout();
                return back()->withErrors(['username' => 'Tài khoản không có quyền truy cập.']);
            }
        }
        return back()->withErrors(['username' => 'Đăng nhập không thành công.'])->withInput();

    }

    // Xử lý logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('alert-info', 'Đăng xuất thành công.');
    }

    // Hiển thị form đổi mật khẩu
    public function showChangePasswordForm()
    {
        return view('auth.change_password');
    }

    // Xử lý đổi mật khẩu
    public function updatePassword(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'current_password'          => 'required',
            'new_password'              => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required'     => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min'          => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed'    => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $employee = Employees::find(Auth::id());

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $employee->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        // Cập nhật mật khẩu mới
        $employee->password = Hash::make($request->new_password);
        $employee->save();

        return redirect()->route('login')->with('alert-info', 'Mật khẩu đã được thay đổi thành công. Vui lòng đăng nhập lại.');
    }
}