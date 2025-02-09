@extends('admin/layouts/master')

@section('title')
Quản lý danh mục
@endsection

@section('feature-title')
Quản lý danh mục
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Thêm mới danh mục</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.category.save') }}">
            @csrf

            <div class="form-group">
                <label for="name">Tên danh mục:</label>
                <input type="text" class="form-control" id="name" name="name" value="" placeholder="Nhập tên danh mục">
                @error('name')
                <small id="name" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection