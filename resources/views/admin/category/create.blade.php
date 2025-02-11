@extends('admin/layouts/master')

@section('title')
Quản lý danh mục
@endsection

@section('feature-title')
Quản lý danh mục
@endsection

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-6 border rounded-3 p-5 custom-shadow">
        <h3 class="text-center title2 mb-4">Thêm mới danh mục</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.category.save') }}">
            @csrf

            <div class="form-group mb-3">
                <label for="name" class="form-label fw-semibold">Tên danh mục:</label>
                <input type="text" class="form-control rounded-2" id="name" name="name" value=""
                    placeholder="Nhập tên danh mục">
                @error('name')
                <small id="name" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" name="submit" class="btn btn-primary fw-semibold">Lưu</button>
        </form>
    </div>
</div>
@endsection