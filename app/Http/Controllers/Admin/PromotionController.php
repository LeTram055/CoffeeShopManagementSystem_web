<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use App\Models\Promotions;
use App\Exports\PromotionsExport;

class PromotionController extends Controller
{
    // Hiển thị danh sách quảng cáo
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'promotion_id'); // Mặc định sắp xếp theo promotion_id
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định tăng dần

        $query = Promotions::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', "%$searchTerm%")
              ->orWhere('discount_type', 'like', "%$searchTerm%")
              ->orWhere('discount_value', 'like', "%$searchTerm%")
              ->orWhere('min_order_value', 'like', "%$searchTerm%");
        });
        }

        if ($sortField == 'name' || $sortField == 'discount_type') {
            $query->orderByRaw("CONVERT($sortField USING utf8mb4) COLLATE utf8mb4_unicode_ci $sortDirection");
        } elseif ($sortField == 'discount_value' || $sortField == 'min_order_value') {
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        } elseif ($sortField == 'start_date' || $sortField == 'end_date') {
            $query->orderBy($sortField,$sortDirection);
        
        } else {
            $query->orderBy('promotion_id', $sortDirection);
        }

        $promotions = $query->get();
        $validPromotions = $query->where('is_active', 1)
        ->whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now())
        ->get();
        
        return view('admin.promotion.index')
            ->with('promotions', $promotions)
            ->with('validPromotions', $validPromotions)
            ->with('sortField', $sortField)
            ->with('sortDirection', $sortDirection);
    }

    // Xuất Excel
    public function exportExcel()
    {
        return Excel::download(new PromotionsExport, 'promotions.xlsx');
    }

    // Xóa 
    public function destroy(Request $request)
    {
        $promotion = Promotions::find($request->input('promotion_id'));
        if ($promotion->order->count() > 0) {
            Session::flash('alert-danger', 'Không thể xóa quảng cáo này vì nó đang được sử dụng.');
            return redirect()->route('admin.promotion.index');
        }
        $promotion->delete();

        Session::flash('alert-success', 'Xóa quảng cáo thành công');
        return redirect()->route('admin.promotion.index');
    }

    // thêm
    public function create()
    {
        return view('admin.promotion.create');
    }

    // Lưu
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:promotions,name',
            'discount_type' => 'required',
            'discount_value' => 'required|numeric',
            'min_order_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ],
        [
            'name.required' => 'Vui lòng nhập tên quảng cáo',
            'name.unique' => 'Quảng cáo đã tồn tại',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm giá',
            'discount_value.numeric' => 'Giá trị giảm giá phải là số',
            'min_order_value.requuired' => 'Vui lòng nhập giá trị tối thiểu áp dụng khuyến mãi',
            'min_order_value.numeric' => 'Giá trị tối thiểu phải là số',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc',
        ]
    );

        $promotion = new Promotions();
        $promotion->promotion_id = $request->promotion_id;
        $promotion->name = $request->name;
        $promotion->discount_type = $request->discount_type;
        $promotion->discount_value = $request->discount_value;
        $promotion->start_date = $request->start_date;
        $promotion->end_date = $request->end_date;
        $promotion->is_active = $request->is_active;

        $promotion->save();

        Session::flash('alert-success', 'Thêm quảng cáo thành công');
        return redirect()->route('admin.promotion.index');
    }

    // sửa
    public function edit(Request $request)
    {
        $promotion = Promotions::find($request->promotion_id);
        return view('admin.promotion.edit')
            ->with('promotion', $promotion);
    }

    // Cập nhật
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required|numeric',
            'min_order_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ],
        [
            'name.required' => 'Vui lòng nhập tên quảng cáo',
            
            'discount_type.required' => 'Vui lòng chọn loại giảm giá',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm giá',
            'discount_value.numeric' => 'Giá trị giảm giá phải là số',
            'min_order_value.requuired' => 'Vui lòng nhập giá trị tối thiểu áp dụng khuyến mãi',
            'min_order_value.numeric' => 'Giá trị tối thiểu phải là số',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc',
            ]
    );

        $promotion = Promotions::find($request->promotion_id);
        $promotion->name = $request->name;
        $promotion->discount_type = $request->discount_type;
        $promotion->discount_value = $request->discount_value;
        $promotion->start_date = $request->start_date;
        $promotion->end_date = $request->end_date;
        $promotion->is_active = $request->is_active;

        $promotion->save();

        Session::flash('alert-success', 'Cập nhật quảng cáo thành công');
        return redirect()->route('admin.promotion.index');
    }

    
}