@php
    $pageTitle = 'QR-CODE SCANNER';
@endphp
@section('page-title', $pageTitle)
<div class="editors position-relative mt-5 ">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <span class="text-primary fw-bolder">Enter to Scanning QR-CODE</span>
                            <input type="password" class="form-control border border-info" id="qr-code-scanner"
                                wire:model="scanData">
                            <button class="btn btn-primary btn-sm float-end mt-2" wire:click="inputClear">CLEAR</button>
                        </div>
                        <a href="{{ route('exo.qrcode-scanner') }}" class="btn btn-info w-100 text-white mt-5">GO TO OLD VERSION</a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                {{-- CARD --}}
                <div class="card mb-2">
                    <div class="row no-gutters">
                        <div class="col-lg-3 col-md-3">
                            @if ($profile)
                                @switch($profile['type'])
                                    @case('student')
                                        <img src="{{ $profile['image'] }}" class="card-img image" alt="#">
                                    @break

                                    @case('employee')
                                        <img src="{{ asset($profile['image']) }}" class="card-img image" alt="#">
                                    @break

                                    @default
                                @endswitch
                            @else
                                <img src="http://20.0.0.120:70/assets/img/staff/avatar.png" class="card-img image"
                                    alt="#">
                            @endif

                        </div>
                        <div class="col-lg-9 col-md-9 ps-0">
                            @if ($profile)
                                @switch($profile['type'])
                                    @case('student')
                                        <div class="card-body p-3 me-2">
                                            <div class="row">
                                                <div class="col-md">
                                                    <label for="" class="fw-bolder text-primary h4 student-name">
                                                        {{ $profile['name'] }}
                                                    </label>
                                                    <p class="mb-0">
                                                        <small class="fw-bolder badge bg-secondary student-course">
                                                            {{ $profile['course'] }}
                                                        </small> -
                                                        <small class="badge bg-primary student-level">
                                                            {{ $profile['section'] }}
                                                        </small>
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="col-md">
                                                        <label for="" class="fw-bolder text-muted h5">
                                                            TIME IN
                                                        </label> <br>
                                                        <label for="" class="fw-bolder text-info h4 employee-time-in">
                                                            @if ($profile['attendance'])
                                                                {{ $profile['attendance']->time_in }}
                                                            @else
                                                                - - : - -
                                                            @endif

                                                        </label>
                                                    </div>
                                                    <div class="col-md">
                                                        <label for="" class="fw-bolder text-muted h5">
                                                            TIME OUT
                                                        </label> <br>
                                                        <label for="" class="fw-bolder text-info h4 employee-time-out">
                                                            @if ($profile['attendance'])
                                                                @if ($profile['attendance']->time_out !== null)
                                                                    {{ $profile['attendance']->time_out }}
                                                                @else
                                                                    - - : - -
                                                                @endif
                                                            @else
                                                                - - : - -
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    @break

                                    @case('employee')
                                        <div class="card-body p-3 me-2">
                                            <label for="" class="fw-bolder text-primary h4 text-name employee-name">
                                                {{ $profile['name'] }}
                                            </label>
                                            <p class="mb-0">
                                                <small class="fw-bolder badge bg-secondary text-department">
                                                    {{ $profile['department'] }}
                                                </small>
                                            </p>
                                            <div class="row">
                                                <div class="col-md">
                                                    <label for="" class="fw-bolder text-muted h5">
                                                        TIME IN
                                                    </label> <br>
                                                    <label for="" class="fw-bolder text-info h4 employee-time-in">
                                                        @if ($profile['attendance'])
                                                            {{ $profile['attendance']->time_in }}
                                                        @else
                                                            - - : - -
                                                        @endif

                                                    </label>
                                                </div>
                                                <div class="col-md">
                                                    <label for="" class="fw-bolder text-muted h5">
                                                        TIME OUT
                                                    </label> <br>
                                                    <label for="" class="fw-bolder text-info h4 employee-time-out">
                                                        @if ($profile['attendance'])
                                                            @if ($profile['attendance']->time_out !== null)
                                                                {{ $profile['attendance']->time_out }}
                                                            @else
                                                                - - : - -
                                                            @endif
                                                        @else
                                                            - - : - -
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @break

                                    @default
                                @endswitch
                            @else
                                <div class="card-body p-3 me-2">
                                    <label for="" class="fw-bolder text-primary h4 text-name employee-name">
                                        NAME
                                    </label>
                                    <div class="row">
                                        <div class="col-md">
                                            <label for="" class="fw-bolder text-muted h5">
                                                TIME IN
                                            </label> <br>
                                            <label for="" class="fw-bolder text-info h4 employee-time-in"> - - :
                                                -
                                                -
                                            </label>
                                        </div>
                                        <div class="col-md">
                                            <label for="" class="fw-bolder text-muted h5">
                                                TIME OUT
                                            </label> <br>
                                            <label for="" class="fw-bolder text-info h4 employee-time-out"> - -
                                                : -
                                                -
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
                <nav class="nav nav-underline bg-soft-primary pb-0 text-center mt-3" aria-label="Secondary navigation">
                    <div class="d-flex" id="head-check">
                        <a class="nav-link fw-bolder w-100 {{ $activeTab == 'employee' ? 'active' : 'text-muted' }}"
                            wire:click="switchTab('employee')">EMPLOYEE</a>
                        <a class="nav-link fw-bolder w-100 {{ $activeTab == 'student' ? 'active' : 'text-muted' }}"
                            wire:click="switchTab('student')">STUDENT</a>
                    </div>
                </nav>
                @switch($activeTab)
                    @case('employee')
                        <div class="card mt-3">
                            <div class="card-header">
                                <p class="h4 text-primary"><b>EMPLOYEE'S ATTENDANCE LIST</b></p>
                            </div>
                            <div class="card">
                                <table class="table table-head-fixed text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>EMPLOYEE</th>
                                            <th>TIME IN</th>
                                            <th>TIME OUT</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-body-100">
                                        @if (count($employees) > 0)
                                            @foreach ($employees as $_data)
                                                <tr>
                                                    <td>
                                                        <span class="text-muted">
                                                            {{ strtoupper($_data->staff->first_name . ' ' . $_data->staff->last_name) }}<br>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted">
                                                            {{ $_data->staff ? ($_data->time_in ? date_format(date_create($_data->time_in), 'h:i:s a') : '-') : '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted">
                                                            {{ $_data->staff ? ($_data->time_in ? ($_data->time_out != null ? date_format(date_create($_data->time_out), 'h:i:s a') : '-') : '-') : '-' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-muted"><b>NO DATA</b></td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @break

                    @case('student')
                        <div class="card mt-3">
                            <div class="card-header">
                                <p class="h4 text-primary"><b>MIDSHIPMAN'S ATTENDANCE LIST</b></p>
                            </div>
                            <div class="card">
                                <table class="table table-head-fixed text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>CADET'S NAME</th>
                                            <th>SECTION</th>
                                            <th>TIME IN</th>
                                            <th>TIME OUT</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-body-100">
                                        @if (count($students) > 0)
                                            @foreach ($students as $_data)
                                                <tr>
                                                    <td>
                                                        <span class="text-muted">
                                                            {{ strtoupper($_data->student->first_name . ' ' . $_data->student->last_name) }}<br>
                                                        </span>
                                                    </td>
                                                    <td> {{ $_data->student->current_section->section->section_name }}
                                                    </td>
                                                    <td>
                                                        <span class="text-muted">
                                                            {{ $_data->student ? ($_data->time_in ? date_format(date_create($_data->time_in), 'h:i:s a') : '-') : '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ $_data->student ? ($_data->time_in ? ($_data->time_out != null ? date_format(date_create($_data->time_out), 'h:i:s a') : '-') : '-') : '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3"><b>NO DATA</b></td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @break

                    @default
                @endswitch
            </div>
        </div>


    </div>
</div>
