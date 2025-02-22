@extends('admin/layouts/master')

@section('title')
Quản lý khách hàng
@endsection

@section('feature-title')
Quản lý khách hàng
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

<form method="GET" action="{{ route('admin.customer.index') }}" class="row mb-3 justify-content-center">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm khách hàng..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded ms-2" type="submit">Tìm kiếm</button>
        </div>
    </div>
</form>

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.customer.create') }}" class="btn btn-primary">Thêm mới</a>
    <a href="{{ route('admin.customer.exportExcel') }}" class="btn btn-success">Xuất Excel</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">
                    <a
                        href="{{ route('admin.customer.index', ['sort_field' => 'customer_id', 'sort_direction' => $sortField == 'customer_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã khách hàng
                        @if($sortField == 'customer_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.customer.index', ['sort_field' => 'name', 'sort_direction' => $sortField == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tên khách hàng
                        @if($sortField == 'name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.customer.index', ['sort_field' => 'phone_number', 'sort_direction' => $sortField == 'phone_number' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Số điện thoại
                        @if($sortField == 'phone_number')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">Ghi chú</th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
            <tr>
                <td class="text-center">{{ $customer->customer_id }}</td>
                <td>{{ $customer->name }}</td>
                <td class="text-center">{{ $customer->phone_number }}</td>
                <td>{{ $customer->notes }}</td>
                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.customer.edit', ['customer_id' => $customer->customer_id]) }}"
                            class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>

                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection