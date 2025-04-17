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
Quản lý sản phẩm
@endsection

@section('feature-title')
Quản lý sản phẩm
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-8 border rounded-3 p-5 custom-shadow mb-3">
        <h3 class="text-center title2 mb-4">Thêm sản phẩm</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.menuitem.save') }}"
            enctype="multipart/form-data">
            @csrf

            <!-- Tên sản phẩm -->
            <div class="form-group mb-3">
                <label for="name" class="form-label fw-semibold">Tên sản phẩm:</label>
                <input type="text" class="form-control rounded-2" id="name" name="name" value="{{ old('name') }}"
                    placeholder="Nhập tên sản phẩm">
                @error('name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Giá -->
            <div class="form-group mb-3">
                <label for="price" class="form-label fw-semibold">Giá sản phẩm:</label>
                <input type="number" class="form-control rounded-2" id="price" name="price" value="{{ old('price') }}"
                    placeholder="Nhập giá">
                @error('price')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Mô tả -->
            <div class="form-group mb-3">
                <label for="description" class="form-label fw-semibold">Mô tả:</label>
                <textarea class="form-control rounded-2" id="description" name="description" rows="3"
                    placeholder="Nhập mô tả sản phẩm">{{ old('description') }}</textarea>
                @error('description')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Ảnh sản phẩm -->
            <div class="form-group mb-3">
                <label for="image" class="form-label fw-semibold">Hình ảnh:</label>
                <input type="file" class="form-control" id="image_url" name="image_url" value="{{ old('image_url') }}">
                <div>
                    <img id="image-preview" class="mt-2 img-fluid rounded" style="max-width: 200px;">
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
                    <option value="{{ $ingredient->ingredient_id }}" data-unit="{{ $ingredient->unit }}">
                        {{ $ingredient->name }}
                    </option>
                    @endforeach
                </select>
                @error('ingredients')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div id="selected-ingredients"></div>

            <!-- Chọn danh mục -->
            <div class="form-group mb-3">
                <label class="form-label fw-semibold">Danh mục:</label>
                <select class="form-select rounded-2" name="category_id">
                    @foreach ($categories as $category)
                    <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
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
        let input = event.target; // Lấy phần tử input file
        let file = input.files[0]; // Lấy tệp đầu tiên

        if (file) {
            let reader = new FileReader(); // Tạo đối tượng FileReader

            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result).show(); // Cập nhật ảnh xem trước
            };

            reader.readAsDataURL(file); // Đọc tệp dưới dạng URL base64
        } else {
            $('#image-preview').hide(); // Ẩn ảnh nếu không có tệp nào được chọn
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

        $('#selected-ingredients input[name^="ingredients"]').each(function() {
            let id = $(this).attr("name").match(/\d+/)[0]; // Lấy ID nguyên liệu
            ingredientData[id] = $(this).val(); // Lưu giá trị nhập
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
                        <input type="number" step="0.01" name="ingredients[${ingredientId}][quantity_per_unit]" class="form-control w-25" value="${quantity}" placeholder="Số lượng" required>
                        <label class="ms-2">${unit}</label>
                    </div>
                `);
        });
    });
});
</script>
@endsection