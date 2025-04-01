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
        <a href="{{ route('admin.workschedule.scheduleView') }}" class="btn btn-outline-secondary">
            <i class="fas fa-sync-alt"></i> Làm mới
        </a>
        <a href="{{ route('admin.workschedule.index') }}" class="btn btn-outline-warning">
            <i class="fas fa-list"></i> Danh sách lịch
        </a>
    </div>

    <form method="GET" action="{{ route('admin.workschedule.scheduleView') }}" class="d-flex" style="max-width: 50%;">
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

    </form>

</div>
<div class="table-responsive">
    <table class="table table-bordered text-center align-middle">
        <thead>
            <tr class="table-primary">
                <th class="align-middle">Ca/Ngày</th>
                @php $currentDate = $startDate->copy(); @endphp
                @while ($currentDate <= $endDate) <th class="align-middle">{{ $currentDate->format('d/m/Y') }}</th>
                    @php $currentDate->addDay(); @endphp
                    @endwhile
            </tr>
        </thead>
        <tbody>
            @foreach ($shifts as $shift)
            <tr>
                <td class="table-primary align-middle">
                    <strong>{{ $shift->name }}</strong><br>
                    <small>{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</small>
                </td>
                @php $currentDate = $startDate->copy(); @endphp
                @while ($currentDate <= $endDate) <td class="align-middle">
                    @php
                    $employees = $schedulesByDateAndShift[$currentDate->format('Y-m-d')][$shift->shift_id] ?? [];
                    @endphp
                    @if (count($employees) > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach ($employees as $employee)
                        <li>
                            {{ $employee['name'] }} <br>
                            <small class="text-muted">{{ $employee['role'] }}</small>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                    </td>
                    @php $currentDate->addDay(); @endphp
                    @endwhile
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection

@section('custom-scripts')

@endsection