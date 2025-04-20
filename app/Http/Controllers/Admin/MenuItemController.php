<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

use App\Models\MenuItems;
use App\Exports\MenuItemsExport;
use App\Models\Categories;
use App\Models\Ingredients;
use App\Models\MenuIngredients;

class MenuItemController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Categories::with(['items' => function($q) use ($request) {
        // Nếu có từ khóa tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $q->where('name', 'like', "%$search%");
        }
        
        if ($request->filled('available')) {
            $q->where('is_available', $request->available);
        }

    }]);


        $categories = $query->get();
        return view('admin.menuitem.index')
        ->with('categories',$categories);
    }


    public function show($id)
    {
        $menuItem = MenuItems::with('ingredients.ingredient')->findOrFail($id);
       
        return response()->json([
            'name' => $menuItem->name,
            'price' => $menuItem->price,
            'description' => $menuItem->description,
            'reason' => $menuItem->reason,
            'image_url' => asset('storage/uploads/' . $menuItem->image_url),
            'ingredients' => $menuItem->ingredients,
            'is_available' => $menuItem->is_available
        ]);
    }

    public function exportExcel() {
        return Excel::download(new MenuItemsExport, 'menuitems.xlsx');
    }

    // Xóa sản phẩm
    public function destroy(Request $request)
    {
        $menuItem = MenuItems::find($request->item_id);
        if ($menuItem->orderItems()->count() > 0) {
            Session::flash('alert-danger', 'Không thể xóa sản phẩm này vì đã có đơn hàng liên quan');
            return redirect()->route('admin.menuitem.index');
        }
        MenuIngredients::where('item_id', $request->item_id)->delete();
        if ($menuItem->image_url) {
            Storage::disk('public')->delete('uploads/' . $menuItem->image_url);
        }
        $menuItem->delete();
        Session::flash('alert-success', 'Xóa sản phẩm thành công');
        return redirect()->route('admin.menuitem.index');
    }

    //Thêm sản phẩm
    public function create()
    {
        $categories = Categories::all();
        $ingredients = Ingredients::all();
        return view('admin.menuitem.create')
        ->with('categories',$categories)
        ->with('ingredients',$ingredients);
    }

    // Lưu sản phẩm mới
    public function save(Request $request)
    {   
        $request->validate([
            'name' => 'required|string|max:255|unique:menu_items,name',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'nullable|array', // Cho phép không thêm nguyên liệu
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm',
            'name.unique' => 'Tên sản phẩm đã tồn tại',
            'price.required' => 'Vui lòng nhập giá sản phẩm',
            'price.numeric' => 'Giá sản phẩm phải là số',
            'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 0',
            'image_url.required' => 'Vui lòng chọn ảnh sản phẩm',
            'image_url.image' => 'Ảnh sản phẩm phải là ảnh',
            'image_url.mimes' => 'Ảnh sản phẩm phải có định dạng jpeg, png, jpg, gif',
            'image_url.max' => 'Dung lượng ảnh sản phẩm tối đa 2MB',
        ]);

        $ingredients = $request->ingredients ? array_filter($request->ingredients, function ($ingredient) {
            return is_array($ingredient) && isset($ingredient['ingredient_id']) && isset($ingredient['quantity_per_unit']);
        }) : [];

        // Cập nhật lại request để validation hoạt động chính xác
        $request->merge(['ingredients' => $ingredients]);
        
        // Xử lý upload ảnh
        $image = $request->file('image_url');
        $originName = $image->getClientOriginalName();
        $image->storeAs('uploads', $originName, 'public');
    

        // Lưu sản phẩm vào bảng menu_items
        $menuItem = new MenuItems();
        $menuItem->name = $request->name;
        $menuItem->price = $request->price;
        $menuItem->description = $request->description;
        $menuItem->image_url = $originName;
        $menuItem->category_id = $request->category_id;
        $menuItem->save();

        // Lưu nguyên liệu vào bảng trung gian
        if (!empty($ingredients)) {
            foreach ($ingredients as $ingredient) {
                MenuIngredients::create([
                    'item_id' => $menuItem->item_id,
                    'ingredient_id' => $ingredient['ingredient_id'],
                    'quantity_per_unit' => $ingredient['quantity_per_unit']
                ]);
            }
        }

        
        Session::flash('alert-info', 'Thêm mới thành công');
        return redirect()->route('admin.menuitem.index');
    }

    public function edit(Request $request)
    {
        // Lấy sản phẩm theo ID, kèm theo thông tin các nguyên liệu liên quan
        $menuItem = MenuItems::with('ingredients.ingredient')->findOrFail($request->item_id);
        $categories = Categories::all();
        $ingredients = Ingredients::all();

        // Lấy danh sách ID của nguyên liệu đã chọn
        $selectedIngredientIds = $menuItem->ingredients->pluck('ingredient_id')->toArray();
        // Lấy dữ liệu pivot (bao gồm quantity_per_unit) để hiển thị ở form
        $menuItemIngredients = $menuItem->ingredients;

        return view('admin.menuitem.edit')
        ->with('menuItem',$menuItem)
        ->with('categories',$categories)
        ->with('ingredients',$ingredients)
        ->with('selectedIngredientIds',$selectedIngredientIds)
        ->with('menuItemIngredients',$menuItemIngredients);

    }

    public function update(Request $request)
{
    $request->validate([
        'item_id' => 'required|exists:menu_items,item_id',
        'name' => 'required|string|max:255|unique:menu_items,name,' . $request->item_id . ',item_id',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,category_id',
        // Ảnh có thể không thay đổi nên đặt nullable
        'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'ingredients' => 'nullable|array',
        'reason' => 'required_if:is_available,0|string',
    ], [
        'item_id.required' => 'Thiếu ID sản phẩm',
        'name.required' => 'Vui lòng nhập tên sản phẩm',
        'name.unique' => 'Tên sản phẩm đã tồn tại',
        'price.required' => 'Vui lòng nhập giá sản phẩm',
        'price.numeric' => 'Giá sản phẩm phải là số',
        'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 0',
        'category_id.required' => 'Vui lòng chọn danh mục',
        'image_url.image' => 'Ảnh sản phẩm phải là ảnh',
        'image_url.mimes' => 'Ảnh sản phẩm phải có định dạng jpeg, png, jpg, gif',
        'image_url.max' => 'Dung lượng ảnh sản phẩm tối đa 2MB',
        
        'reason.required_if' => 'Vui lòng nhập lý do sản phẩm không có sẵn.',
        
        
    ]);
    
    $ingredients = $request->ingredients ? array_filter($request->ingredients, function ($ingredient) {
        return is_array($ingredient) && isset($ingredient['ingredient_id']) && isset($ingredient['quantity_per_unit']);
    }) : [];

    // Cập nhật lại request để validation hoạt động chính xác
    $request->merge(['ingredients' => $ingredients]);

    $menuItem = MenuItems::findOrFail($request->item_id);

    // Nếu có ảnh mới được upload thì xử lý upload
    if ($request->hasFile('image_url')) {
        $oldImage = $menuItem->image_url;
        if ($oldImage) {
            // Xóa ảnh cũ
            Storage::disk('public')->delete('uploads/' . $oldImage);
        }
        $image = $request->file('image_url');
        $originName = $image->getClientOriginalName();
        $image->storeAs('uploads', $originName, 'public');
        $menuItem->image_url = $originName;
    }

    $menuItem->name = $request->name;
    $menuItem->price = $request->price;
    $menuItem->description = $request->description;
    $menuItem->category_id = $request->category_id;
    $menuItem->is_available = $request->is_available;
    $menuItem->reason = $request->is_available == 0 ? $request->reason : null;
    $menuItem->save();

    // Xóa các bản ghi nguyên liệu cũ
    MenuIngredients::where('item_id', $menuItem->item_id)->delete();

    // Lưu nguyên liệu mới từ form
    if (!empty($ingredients)) {
        foreach ($request->ingredients as $ingredient) {
            if (isset($ingredient['ingredient_id']) && isset($ingredient['quantity_per_unit'])) {
                MenuIngredients::create([
                    'item_id' => $menuItem->item_id,
                    'ingredient_id' => $ingredient['ingredient_id'],
                    'quantity_per_unit' => $ingredient['quantity_per_unit']
                ]);
            }
        }
    }

    Session::flash('alert-info', 'Cập nhật sản phẩm thành công');
    return redirect()->route('admin.menuitem.index');
}


}