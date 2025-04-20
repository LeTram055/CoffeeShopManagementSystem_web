@extends('admin/layouts/master')

@section('custom-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}
</style>
@endsection

@section('title')
Chỉnh sửa sản phẩm
@endsection

@section('feature-title')
Chỉnh sửa sản phẩm
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-8 border rounded-3 p-5 custom-shadow mb-3">
        <h3 class="text-center title2 mb-4">Cập nhật sản phẩm</h3>
        <form name="frmEdit" id="frmEdit" method="post" action="{{ route('admin.menuitem.update') }}"
            enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="item_id" value="{{ $menuItem->item_id }}">

            <!-- Tên sản phẩm -->
            <div class="form-group mb-3">
                <label for="name" class="form-label fw-semibold">Tên sản phẩm:</label>
                <input type="text" class="form-control rounded-2" id="name" name="name"
                    value="{{ old('name', $menuItem->name) }}" placeholder="Nhập tên sản phẩm">
                @error('name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Giá sản phẩm -->
            <div class="form-group mb-3">
                <label for="price" class="form-label fw-semibold">Giá sản phẩm:</label>
                <input type="number" class="form-control rounded-2" id="price" name="price"
                    value="{{ old('price', $menuItem->price) }}" placeholder="Nhập giá">
                @error('price')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Mô tả -->
            <div class="form-group mb-3">
                <label for="description" class="form-label fw-semibold">Mô tả:</label>
                <textarea class="form-control rounded-2" id="description" name="description" rows="3"
                    placeholder="Nhập mô tả sản phẩm">{{ old('description', $menuItem->description) }}</textarea>
                @error('description')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Ảnh sản phẩm -->
            <div class="form-group mb-3">
                <label for="image_url" class="form-label fw-semibold">Hình ảnh:</label>
                <input type="file" class="form-control" id="image_url" name="image_url">
                <div>
                    <img id="image-preview" src="{{ asset('storage/uploads/' . $menuItem->image_url) }}"
                        class="mt-2 img-fluid rounded" style="max-width: 200px;">
                </div>
                @error('image_url')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Chọn nguyên liệu -->
            <div class="form-group mb-3">
                <label class="form-label fw-semibold">Nguyên liệu:</label>
                <select class="form-select rounded-2" id="ingredients-select" name="ingredients[]" multiple>
                    @foreach ($ingredients as $ingredient)
                    <option value="{{ $ingredient->ingredient_id }}" data-unit="{{ $ingredient->unit }}"
                        @if(in_array($ingredient->ingredient_id, $selectedIngredientIds)) selected @endif>
                        {{ $ingredient->name }}
                    </option>
                    @endforeach
                </select>
                @error('ingredients')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div id="selected-ingredients">
                @if(isset($menuItemIngredients))
                @foreach($menuItemIngredients as $mi)
                <div class="d-flex align-items-center mb-2">
                    <input type="hidden" name="ingredients[{{ $mi->ingredient_id }}][ingredient_id]"
                        value="{{ $mi->ingredient_id }}">
                    <label class="me-3">{{ $mi->ingredient->name }}</label>
                    <input type="number" step="0.01" required
                        name="ingredients[{{ $mi->ingredient_id }}][quantity_per_unit]" class="form-control w-25"
                        value="{{ old('ingredients.' . $mi->ingredient_id . '.quantity_per_unit', $mi->quantity_per_unit) }}"
                        placeholder="Số lượng">
                    <label class="ms-2">{{ $mi->ingredient->unit }}</label>
                </div>
                @endforeach
                @endif
            </div>

            <!-- Chọn danh mục -->
            <div class="form-group mb-3">
                <label class="form-label fw-semibold">Danh mục:</label>
                <select class="form-select rounded-2" name="category_id">
                    @foreach ($categories as $category)
                    <option value="{{ $category->category_id }}" @if(old('category_id', $menuItem->category_id) ==
                        $category->category_id) selected @endif>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Trạng thái -->
            <div class="form-group mb-3">
                <label class="form-label fw-semibold">Trạng thái:</label>
                <select class="form-select rounded-2" name="is_available" id="is_available">
                    <option value="1" @if(old('is_available', $menuItem->is_available) == 1) selected @endif>Còn
                        hàng</option>
                    <option value="0" @if(old('is_available', $menuItem->is_available) == 0) selected @endif>Hết
                        hàng</option>
                </select>
            </div>

            <!-- Lý do hết hàng -->
            <div class="form-group mb-3" id="reason-container" style="display: none;">
                <label for="reason" class="form-label fw-semibold">Lý do hết hàng:</label>
                <textarea class="form-control rounded-2" id="reason" name="reason" rows="3"
                    placeholder="Nhập lý do">{{ old('reason', $menuItem->reason) }}</textarea>
                @error('reason')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" name="submit" class="btn btn-primary fw-semibold">Lưu</button>
            <a href="{{ route('admin.menuitem.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#image_url').on('change', function(event) {
        let input = event.target;
        let file = input.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });
});

$(document).ready(function() {
    let ingredientData = {};

    $('#ingredients-select').select2({
        placeholder: "Chọn nguyên liệu...",
        allowClear: true,
        width: "100%"
    });

    $('#ingredients-select').on('change', function() {
        let selectedIngredients = $(this).val() || [];

        // Lưu lại số lượng đã nhập trước đó
        $('#selected-ingredients input[name^="ingredients"]').each(function() {
            let id = $(this).attr("name").match(/\d+/)[0];
            ingredientData[id] = $(this).val();
        });

        $('#selected-ingredients').empty();
        $(this).find(':selected').each(function() {
            let ingredientId = $(this).val();
            let ingredientName = $(this).text();
            let unit = $(this).data('unit');
            let quantity = ingredientData[ingredientId] || '';
            $('#selected-ingredients').append(`
        <div class="d-flex align-items-center mb-2">
          <input type="hidden" name="ingredients[${ingredientId}][ingredient_id]" value="${ingredientId}">
          <label class="me-3">${ingredientName}</label>
          <input type="number" step="0.01" required name="ingredients[${ingredientId}][quantity_per_unit]" class="form-control w-25" value="${quantity}" placeholder="Số lượng">
          <label class="ms-2">${unit}</label>
        </div>
      `);
        });
    });
    // Kích hoạt sự kiện change để hiển thị lại danh sách đã chọn khi tải trang
    $('#ingredients-select').trigger('change');
});

$(document).ready(function() {
    const $isAvailableSelect = $('#is_available');
    const $reasonContainer = $('#reason-container');
    const $reasonInput = $('#reason');

    // Hiển thị hoặc ẩn trường lý do dựa trên trạng thái
    function toggleReasonField() {
        if ($isAvailableSelect.val() == "0") { // Hết hàng
            $reasonContainer.show();
            //$reasonInput.attr('required', 'required'); // Bắt buộc nhập lý do
        } else { // Còn hàng
            $reasonContainer.hide();
            //$reasonInput.removeAttr('required'); // Không bắt buộc nhập lý do
            $reasonInput.val(''); // Xóa nội dung lý do nếu có
        }
    }

    // Gọi hàm khi tải trang
    toggleReasonField();

    // Gọi hàm khi thay đổi trạng thái
    $isAvailableSelect.on('change', toggleReasonField);
});
</script>
@endsection