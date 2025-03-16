<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

use App\Models\Categories;
use App\Exports\CategoriesExport;


class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'category_id'); // Mặc định sắp xếp theo category_id
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định sắp xếp tăng dần

        $query = Categories::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            // Tìm kiếm theo tên
            $query->where('name', 'like', '%' . $searchTerm . '%');
                
        }

        if ($sortField == 'name') {
            $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
        }
        elseif ($sortField == 'category_id') {
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        }
        else {
            $query->orderByRaw("CONVERT(category_id USING utf8) COLLATE utf8_unicode_ci asc");
        }

        // Lấy ra danh sách các category 10 phần tử mỗi trang
        $categories = $query->get();

        return view('admin.category.index')
            ->with('categories', $categories)
            ->with('sortField', $sortField)
            ->with('sortDirection', $sortDirection);
    }

    // Xuất Excel
    public function exportExcel()
    {
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }

    // Xóa category
    public function destroy(Request $request)
    {
        $category = Categories::find($request->input('category_id'));
        if ($category->items->count() > 0) {
            Session::flash('alert-danger', 'Không thể xóa danh mục này vì nó đang có sản phẩm.');
            return redirect()->route('admin.category.index');
        }

        $category->delete();
        Session::flash('alert-success', 'Xóa danh mục thành công');
        return redirect()->route('admin.category.index');
    }

    // Thêm category
    public function create()
    {
        return view('admin.category.create');
    }

    // Lưu category
    public function save(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:categories,name']
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục',
            'name.unique' => 'Danh mục đã tồn tại'
        ]);


        $category = new Categories();
        $category->name = $request->name;
       
        $category->save();

        Session::flash('alert-success', 'Thêm danh mục thành công');
        return redirect()->route('admin.category.index');
    }

    // Sửa category
    public function edit(Request $request)
    {
        
        $category = Categories::find($request->category_id);
        return view('admin.category.edit')
            ->with('category', $category);
    }

    // Cập nhật category
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:categories,name']
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục',
            'name.unique' => 'Danh mục đã tồn tại'
        ]);


        $category = Categories::find($request->category_id);
        $category->name = $request->name;
        $category->save();

        Session::flash('alert-success', 'Cập nhật danh mục thành công');
        return redirect()->route('admin.category.index');
    }
}