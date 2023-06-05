@section('page-title', 'Employee')
<div>
    <label for="" class="fw-bolder text-primary h4">EMPLOYEE DETAILS</label>
    <div class="row">
        <div class="col-lg-4">
            <form action="" method="get" class="form-group mt-2">
                <small class="fw-bolder text-primary">SEARCH EMPLOYEE</small>
                <input type="text" class="form-control" wire:model="searchInput" wire:keydown="searchEmployee">
                <div class=" d-flex justify-content-between mt-2">
                    <h6 class=" fw-bolder text-muted">
                        @if (count($employeeList) > 0)
                            <small class="text-muted">SEARCH RESULT: </small>
                            <span class="text-info fw-bolder">{{ $searchInput }}</span>
                        @else
                        @endif
                    </h6>
                    <span class="text-primary h6">
                        No. Result: <b>{{ count($employeeList) }}</b>
                    </span>

                </div>
            </form>
            <div class="employee-list">
                @if (count($employeeList) > 0)
                    @foreach ($employeeList as $item)
                        <div class="card mb-2">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img class="img-fluid avatar avatar-100 avatar-rounded me-2"
                                        src="{{ asset($item->profile_picture()) }}" alt="User Avatar">
                                </div>
                                <div class="col-md p-1">
                                    <div class="card-body p-2">
                                        <small
                                            class="text-primary fw-bolder">{{ strtoupper($item->last_name . ', ' . $item->first_name) }}</small>
                                        <br>
                                        <span class="badge bg-secondary">{{ $item->department }}</span>
                                        <a class="badge bg-primary"
                                            wire:click="setEmployee({{ $item->id }})">VIEW</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card  mb-2">
                        <div class="row no-gutters">
                            <div class="col-md">
                                <div class="card-body">
                                    <span class="fw-bolder">NOT FOUND</span>

                                </div>
                            </div>
                        </div>
                    </div>

                @endif
            </div>

        </div>
        <div class="col-lg">
            <div class="card mb-2">
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
                                <small class="badge bg-primary">
                                    {{ $employee ? $employee->user->email : 'EMAIL' }}
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @if ($employee)
                <nav class="nav nav-underline bg-soft-primary pb-0 text-center" aria-label="Secondary navigation">
                    <div class="d-flex" id="head-check">
                        <a class="nav-link {{ request()->input('view') == 'profile' || !request()->input('view') ? 'active' : 'text-muted' }}"
                            href="{{ route('registrar.student-profile') }}?student={{ base64_encode($employee->id) }}&view=profile">PROFILE</a>
                        <a class="nav-link  {{ request()->input('view') == 'enrollment' ? 'active' : 'text-muted' }}"
                            href="{{ route('registrar.student-profile') }}?student={{ base64_encode($employee->id) }}&view=enrollment">ROLE</a>
                        <a class="nav-link   {{ request()->input('view') == 'account' ? 'active' : 'text-muted' }}"
                            href="{{ route('registrar.student-profile') }}?student={{ base64_encode($employee->id) }}&view=account">ATTENDANCE</a>
                        <a class="nav-link   {{ request()->input('view') == 'grades' ? 'active' : 'text-muted' }}"
                            href="{{ route('registrar.student-profile') }}?student={{ base64_encode($employee->id) }}&view=grades"></a>
                    </div>
                </nav>
            @endif

        </div>
    </div>
</div>
