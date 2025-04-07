@extends('admin/layouts/master')

@section('title')
Quản lý lịch làm việc
@endsection

@section('feature-title')
Quản lý lịch làm việc
@endsection

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }} position-relative">
        {{ Session::get('alert-' . $msg) }}
        <button type="button" class="btn-close position-absolute end-0 me-2" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </p>
    @endif
    @endforeach
</div>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div class="d-flex gap-2">
        <a href="{{ route('admin.workschedule.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
        <a href="{{ route('admin.workschedule.exportExcel') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
        <a href="{{ route('admin.workschedule.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-sync-alt"></i> Làm mới
        </a>
        <a href="{{ route('admin.workschedule.scheduleView') }}" class="btn btn-outline-warning">
            <i class="fas fa-calendar-alt"></i> Xem dạng lịch biểu
        </a>
    </div>


    <form method="GET" action="{{ route('admin.workschedule.index') }}" class="d-flex" style="max-width: 50%;">
        <div class="dropdown me-2">
            <button class="btn btn-outline-info dropdown-toggle" type="button" id="dateDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                Lọc theo ngày
            </button>
            <div class="dropdown-menu p-3" aria-labelledby="dateDropdown">
                <div class="mb-2">
                    <label for="start_date" class="form-label">Từ ngày:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="{{ request('start_date') }}">
                </div>
                <div class="mb-2">
                    <label for="end_date" class="form-label">Đến ngày:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="{{ request('end_date') }}">
                </div>
                <button type="submit" class="btn btn-primary w-100">Áp dụng</button>
            </div>
        </div>
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
                        href="{{ route('admin.workschedule.index', ['sort_field' => 'schedule_id', 'sort_direction' => $sortField == 'schedule_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã lịch làm việc
                        @if($sortField == 'schedule_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.workschedule.index', ['sort_field' => 'employee_id', 'sort_direction' => $sortField == 'employee_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Nhân viên
                        @if($sortField == 'employee_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.workschedule.index', ['sort_field' => 'shift_id', 'sort_direction' => $sortField == 'shift_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Ca làm
                        @if($sortField == 'shift_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.workschedule.index', ['sort_field' => 'work_date', 'sort_direction' => $sortField == 'work_date' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Ngày làm việc
                        @if($sortField == 'work_date')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.workschedule.index', ['sort_field' => 'status', 'sort_direction' => $sortField == 'status' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Trạng thái
                        @if($sortField == 'status')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">Số giờ làm</th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @foreach ($schedules as $schedule)
            <tr>
                <td class="text-center">{{ $schedule->schedule_id }}</td>
                <td class="text-center">{{ $schedule->employee->name ?? 'Không xác định' }}</td>
                <td class="text-center">{{ $schedule->shift->name ?? 'Không có ca' }}
                    ({{ $schedule->shift->start_time->format('H:i') }} -
                    {{ $schedule->shift->end_time->format('H:i') }})</td>
                <td class="text-center">{{ $schedule->work_date->format('d/m/Y') }}</td>
                <td class="text-center">
                    <span
                        class="badge bg-{{ $schedule->status == 'completed' ? 'success' : ($schedule->status == 'absent' ? 'danger' : 'warning') }}">
                        {{ $schedule->status == 'completed' ? 'Hoàn thành' : ($schedule->status == 'absent' ? 'Vắng mặt' : 'Đã lên lịch') }}
                    </span>
                </td>

                <td class="text-center">{{ $schedule->work_hours }} giờ</td>
                <td class="text-center">
                    <a href="{{ route('admin.workschedule.edit', ['schedule_id' => $schedule->schedule_id]) }}"
                        class="text-warning mx-2">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form class="d-inline delete-form" method="post" action="{{ route('admin.workschedule.delete') }}">
                        @csrf
                        <input type="hidden" name="schedule_id" value="{{ $schedule->schedule_id }}">
                        <button type="submit" class="btn btn-link text-danger p-0 border-0 delete-schedule-btn">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Phân trang -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <form action="{{ route('admin.workschedule.index') }}" method="GET" class="d-flex align-items-center mt-1">
            @foreach(request()->except(['per_page', 'page']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <label for="per_page" class="me-2 text-nowrap">Hiển thị:</label>
            <select name="per_page" id="per_page" class="form-select form-select-sm w-auto"
                onchange="this.form.submit()">

                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </form>

        <div>
            {{ $schedules->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
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
    $('.delete-schedule-btn').on('click', function(e) {
        e.preventDefault();
        formToSubmit = $(this).closest('form');
        const workDate = $(this).closest('tr').find('td').eq(3).text();
        const employeeName = $(this).closest('tr').find('td').eq(1).text();

        if (workDate.length > 0 && employeeName.length > 0) {
            $('.modal-body').html(
                `Bạn có muốn xóa lịch làm việc ngày "${workDate}" của nhân viên "${employeeName}" không?`
            );
        }
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