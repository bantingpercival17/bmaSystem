@php
    $pageTitle = 'Employee Attendance Monitoring';
@endphp
@section('page-title', $pageTitle)
<div>
    <div class="row">
        <div class="col-lg-8">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
            <div class="filter-section m-0 p-0">
                <small class="fw-bolder text-info">TODAY'S ATTENDANCE:</small>
                <div class="row">
                    <div class="col-md-6">
                        <small class="fw-bolder text-muted">DATE </small> <br>
                        <label for="" class="fw-bolder text-primary">{{ now()->format('F d,Y') }}</label>
                    </div>
                    <div class="col-md-6">
                        <small class="fw-bolder text-muted">TOTAL PRESENT : </small> <br>
                        <label for="" class="fw-bolder text-primary"></label>
                    </div>
                </div>
            </div>
            @forelse ($employeeList as $employee)
                <a href="">
                    <div class="card mb-2 shadow mb-3">
                        <div class="row no-gutters">
                            <div class="col-md-3">
                                <img src="{{ $employee ? asset($employee->profile_picture()) : asset('/assets/img/staff/avatar.png') }}"
                                    class="card-img" alt="#">
                            </div>
                            <div class="col-md ps-0">
                                <div class="card-body p-3 me-2">
                                    <label for=""
                                        class="fw-bolder text-primary h4">{{ $employee ? strtoupper($employee->last_name . ', ' . $employee->first_name) : 'EMPLOYEE NAME' }}</label>
                                    <p class="mb-0">
                                        <small class="fw-bolder badge bg-secondary">
                                            {{ $employee ? $employee->department . ' DEPARTMENT' : 'DEPARTMENT' }}
                                        </small> -
                                        <small class="badge bg-secondary">
                                            {{ $employee ? $employee->user->email : 'EMAIL' }}
                                        </small>
                                    </p>
                                    <p class="mb-0">
                                        <small class="fw-bolder badge bg-info">
                                            TIME IN:
                                            <b>
                                                {{ $employee->date_attendance(now()->format('Y-m-d')) }}
                                                {{-- @if ($employee->daily_attendance)
                                                    {{ date_format(date_create($employee->daily_attendance->time_in), 'h:i:s a') }}
                                                @else
                                                    -
                                                @endif --}}
                                            </b>
                                        </small> -
                                        <small class="badge bg-info">
                                            TIME OUT:
                                            <b>
                                                @if ($employee->daily_attendance)
                                                    @if ($employee->daily_attendance->time_out)
                                                        {{ date_format(date_create($employee->daily_attendance->time_out), 'h:i:s a') }}
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </b>
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

            @empty
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="mt-2">
                                    <h2 class="counter" style="visibility: visible;">
                                        NO DATA
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse

        </div>
        <div class="col-lg-4">
            <form action="" method="get" class="form-group mt-3">
                <label for="" class="text-primary fw-bolder">SEARCH EMPLOYEE</label>
                <input type="text" class="form-control border border-primary form-control-sm"
                    wire:model="searchInput">
                <div class=" d-flex justify-content-between mt-2">
                    <h6 class=" fw-bolder text-muted">
                        @if ($searchInput != '')
                            Search Result: <span class="text-primary">{{ $searchInput }}</span>
                        @else
                            {{ strtoupper('Recent Search') }}
                        @endif
                    </h6>
                    <span class="text-muted h6">
                        No. Result: <b>{{ count($employeeList) }}</b>
                    </span>

                </div>
                <div class="form-group">
                    <small class="text-muted">ATTENDANCE SORT</small>
                    <select name="" id="" wire:model="searchSelect"
                        class="form-select form-select-sm border border-primary">
                        <option value="1">All Employees</option>
                        <option value="2">Present Employees</option>
                        <option value="3">Absent Employees</option>
                    </select>
                </div>
            </form>
            <div class="form mb-5">
                <span class="text-primary fw-bolder">GENERATE REPORT</span>
                <div class="">
                    <form action="{{ route('accounting.employee-attendance-v2') }}" target="_blank" method="get">
                        <div class="form-group">
                            <small class="text-muted">START DATE</small>
                            <input type="date" class="form-control form-control-sm border border-primary"
                                name="start_date" required="">
                        </div>
                        <div class="form-group">
                            <small class="text-muted">END DATE</small>
                            <input type="date" class="form-control form-control-sm border border-primary"
                                name="end_date" required="">
                        </div>
                        <div class="form-group">
                            <small class="text-muted">DEPARTMENT</small>
                            <select name="department" id=""
                                class="form-select form-select-sm border border-primary">
                                <option value="null">All Department</option>
                                @foreach ($departmentList as $item)
                                    <option value="{{ $item->id }}">{{ strtoupper($item->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm btn-block float-end">Generate</button>
                        </div>
                    </form>
                </div>
            </div>



        </div>

    </div>
</div>
