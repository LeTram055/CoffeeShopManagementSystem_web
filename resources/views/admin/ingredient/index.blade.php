@extends('admin/layouts/master')

@section('title')
Quản lý nguyên liệu
@endsection

@section('feature-title')
Quản lý nguyên liệu
@endsection

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="btn-close"
            data-bs-dismiss="alert" aria-label="Close"></p>
    @endif
    @endforeach
</div>

<form method="GET" action="{{ route('admin.ingredient.index') }}" class="row mb-3 justify-content-center">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm nguyên liệu..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded ms-2" type="submit">Tìm kiếm</button>
        </div>
    </div>
</form>

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.ingredient.create') }}" class="btn btn-primary">Thêm mới</a>
    <a href="{{ route('admin.ingredient.exportExcel') }}" class="btn btn-success">Xuất Excel</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredient.index', ['sort_field' => 'ingredient_id', 'sort_direction' => $sortField == 'ingredient_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã nguyên liệu
                        @if($sortField == 'ingredient_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredient.index', ['sort_field' => 'name', 'sort_direction' => $sortField == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tên nguyên liệu
                        @if($sortField == 'name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredient.index', ['sort_field' => 'quantity', 'sort_direction' => $sortField == 'quantity' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Số lượng
                        @if($sortField == 'quantity')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredient.index', ['sort_field' => 'unit', 'sort_direction' => $sortField == 'unit' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Đơn vị
                        @if($sortField == 'unit')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredient.index', ['sort_field' => 'min_quantity', 'sort_direction' => $sortField == 'min_quantity' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Số lượng tối thiểu
                        @if($sortField == 'min_quantity')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.ingredient.index', ['sort_field' => 'last_updated', 'sort_direction' => $sortField == 'last_updated' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Cập nhật lần cuối
                        @if($sortField == 'last_updated')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($ingredients as $ingredient)
            <tr>
                <td class="text-center">{{ $ingredient->ingredient_id }}</td>
                <td>{{ $ingredient->name }}</td>
                <td class="text-center">{{ $ingredient->quantity }}</td>
                <td class="text-center">{{ $ingredient->unit }}</td>
                <td class="text-center">{{ $ingredient->min_quantity }}</td>
                <td class="text-center">
                    {{ $ingredient->last_updated ? $ingredient->last_updated->format('H:i:s d/m/Y') : 'N/A' }}</td>

                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.ingredient.edit', ['ingredient_id' => $ingredient->ingredient_id]) }}"
                            class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form class="mx-1" name=frmDelete method="post" action="{{ route('admin.ingredient.delete') }}">
                            @csrf
                            <input type="hidden" name="ingredient_id" value="{{ $ingredient->ingredient_id }}">
                            <button type="submit" class="btn btn-danger btn-sm delete-ingredient-btn"><i
                                    class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="delete-confirm" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Xóa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
$(document).ready(function() {
    let formToSubmit;

    $('.delete-ingredient-btn').on('click', function(e) {
        e.preventDefault();
        formToSubmit = $(this).closest('form');
        const ingredientName = $(this).closest('tr').find('td').eq(1).text();

        if (ingredientName.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa nguyên liệu "${ingredientName}" không?`);
        }

        $('#delete-confirm').modal('show');
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });
});
</script>
@endsection