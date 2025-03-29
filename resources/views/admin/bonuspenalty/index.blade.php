@extends('admin.layouts.master')

@section('title', 'Quản lý thưởng phạt')

@section('feature-title', 'Quản lý thưởng phạt')

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }}">
        {{ Session::get('alert-' . $msg) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </p>
    @endif
    @endforeach
</div>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div class="d-flex gap-2">
        <a href="{{ route('admin.bonuspenalty.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
        <a href="{{ route('admin.bonuspenalty.exportExcel') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
        <a href="{{ route('admin.bonuspenalty.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-sync-alt"></i> Làm mới
        </a>
    </div>
    <form method="GET" action="{{ route('admin.bonuspenalty.index') }}" class="d-flex" style="max-width: 50%;">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm ca làm việc..."
                value="{{ request('search') }}">
            <button class="btn btn-bg" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th class="text-center">
                    <a
                        href="{{ route('admin.bonuspenalty.index', ['sort_field' => 'bonus_penalty_id', 'sort_direction' => $sortField == 'bonus_penalty_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã
                        @if($sortField == 'bonus_penalty_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.bonuspenalty.index', ['sort_field' => 'employee_name', 'sort_direction' => $sortField == 'employee_name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Nhân viên
                        @if($sortField == 'employee_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>


                <th class="text-center">
                    <a
                        href="{{ route('admin.bonuspenalty.index', ['sort_field' => 'amount', 'sort_direction' => $sortField == 'amount' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Số tiền
                        @if($sortField == 'amount')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    Lý do
                </th>

                <th class="text-center">
                    <a
                        href="{{ route('admin.bonuspenalty.index', ['sort_field' => 'date', 'sort_direction' => $sortField == 'date' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Ngày
                        @if($sortField == 'date')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @foreach ($bonusesPenalties as $bonuspenalty)
            <tr>
                <td class="text-center">{{ $bonuspenalty->bonus_penalty_id }}</td>
                <td class="text-center">{{ $bonuspenalty->employee->name }}</td>
                <td class="text-center">{{ number_format($bonuspenalty->amount, 0, ',', '.') }} VNĐ</td>
                <td class="text-center">{{ $bonuspenalty->reason }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($bonuspenalty->date)->format('d/m/Y') }}</td>
                <td class="text-center">
                    <a href="{{ route('admin.bonuspenalty.edit', ['bonus_penalty_id' => $bonuspenalty->bonus_penalty_id]) }}"
                        class="text-warning mx-2">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form class="d-inline delete-form" method="post" action="{{ route('admin.bonuspenalty.delete') }}">
                        @csrf
                        <input type="hidden" name="bonuspenalty_id" value="{{ $bonuspenalty->bonus_penalty_id }}">
                        <button type="submit" class="btn btn-link text-danger p-0 border-0 delete-bonuspenalty-btn">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
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
            <div class="modal-body">
            </div>
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

    $('.delete-bonuspenalty-btn').on('click', function(e) {
        e.preventDefault();
        formToSubmit = $(this).closest('form');
        const employeeName = $(this).closest('tr').find('td').eq(1).text();
        const amount = $(this).closest('tr').find('td').eq(2).text();

        $('.modal-body').html(
            `Bạn có chắc chắn muốn xóa khoản "${amount}" của nhân viên "${employeeName}" không?`
        );

        $('#delete-confirm').modal('show');
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });

    // Tự động đóng thông báo sau 5 giây
    setTimeout(function() {
        $('.flash-message .alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection