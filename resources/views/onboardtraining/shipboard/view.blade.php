@extends('layouts.app-main')
@php
$_title = 'Shipboard Monitoring';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>{{ $_title }}
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md-6 col-lg-4">

                        <img src="{{ $_midshipman ? $_midshipman->profile_pic($_midshipman->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="card-img" alt="#">
                    </div>
                    <div class="col-md-6 col-lg-8">
                        <div class="card-body">
                            <h4 class="card-title text-primary">
                                <b>{{ $_midshipman ? strtoupper($_midshipman->last_name . ', ' . $_midshipman->first_name) : 'MIDSHIPMAN NAME' }}</b>
                            </h4>
                            <p class="card-text">
                                <span>STUDENT NUMBER: <b>
                                        {{ $_midshipman ? $_midshipman->account->student_number : '-' }}</b></span>
                                <br>
                                <span>COURSE: <b>
                                        {{ $_midshipman ? $_midshipman->enrollment_assessment->course->course_name : '-' }}</b></span>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($_midshipman)
                <div class="mt-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="card-title text-primary"><b>ONBOAD TRAINING MONITORING</b></p>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="form-group col-md">
                                        <label class="form-label-sm"><small>OBT BATCH</small></label>
                                        <br>
                                        <label
                                            class=" text-primary"><b>{{ $_midshipman->shipboard_training->sbt_batch }}</b></label>
                                    </div>
                                    <div class="form-group col-md">
                                        <label class="form-label-sm"><small>SEA EXPERIENCE</small></label>
                                        <br>
                                        <label
                                            class="text-primary"><b>{{ strtoupper($_midshipman->shipboard_training->shipping_company) }}</b></label>
                                    </div>
                                    <div class="form-group col-md">
                                        <label class="form-label-sm"><small>STATUS</small></label>
                                        <br>
                                        <label class="text-primary">
                                            <b> {{ strtoupper($_midshipman->shipboard_training->shipboard_status) }} </b>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md">
                                        <label class="form-label-sm"><small>COMPANY NAME</small></label>
                                        <br>
                                        <label
                                            class="text-primary"><b>{{ $_midshipman->shipboard_training->company_name }}</b></label>
                                    </div>
                                    <div class="form-group col-md">
                                        <label class="form-label-sm"><small>NAME OF VESSEL</small></label>
                                        <br>
                                        <label class="text-primary">
                                            <b>{{ $_midshipman->shipboard_training->vessel_name }}</b>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="form-group col-md">
                                        <label class="form-label-sm"><small>VESSEL TYPE</small></label>
                                        <br>
                                        <label
                                            class="text-primary"><b>{{ $_midshipman->shipboard_training->vessel_type }}</b></label>
                                    </div>
                                    <div class="form-group col-md">
                                        <label class="form-label-sm"><small>DATE OF EMBARKED</small></label>
                                        <br>
                                        <label
                                            class="text-primary"><b>{{ $_midshipman->shipboard_training->embarked }}</b></label>
                                    </div>
                                </div>
                                <div class="row">
                                    @if ($_midshipman->shipboard_training->shipboard_status != 'on going')
                                        <div class="form-group col-md">
                                            <label class="form-label-sm"><small>DATE OF
                                                    DISEMBARKED</small></label>
                                            <br>
                                            <label
                                                class="text-primary"><b>{{ $_midshipman->shipboard_training->disembarked }}</b></label>
                                        </div>
                                    @endif

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="header-title d-flex justify-content-between">
                            <span class="h5 text-primary fw-bolder">NARATIVE REPORT</span>
                        </div>
                        <div class="table-responsive mt-4">
                            <table id="basic-table" class="table table-striped mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th>Narative Report</th>
                                        <th>Progress</th>
                                        <th>Summary Report</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($_midshipman->narative_report) > 0)
                                        @foreach ($_midshipman->narative_report as $_journal)
                                            <tr>
                                                <td>
                                                    <a
                                                        href=" {{ route('onboard.journal') }}?_j={{ base64_encode($_journal->month) }}&_midshipman={{ base64_encode($_midshipman->id) }}">
                                                        {{ date('F - Y', strtotime($_journal->month)) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h6>{{ ($_journal->is_approved / 5) * 100 }}%</h6>
                                                    </div>
                                                    <div class="progress bg-soft-info shadow-none w-100"
                                                        style="height: 6px">
                                                        <div class="progress-bar bg-info" data-toggle="progress-bar"
                                                            role="progressbar"
                                                            aria-valuenow="{{ ($_journal->is_approved / 5) * 100 }}"
                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3">No Journal</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="header-title d-flex justify-content-between">
                            <span class="h5 text-primary fw-bolder">ASSESSMENT</span>
                        </div>
                        <div class="table-responsive mt-4">
                            <table id="basic-table" class="table table-striped mb-0" role="grid">
                                <tbody>
                                    <tr>
                                        <th>ONLINE EXAMINATION</th>
                                        <th></th>

                                    </tr>
                                    <tr>
                                        <th>PARCTICAL ASSESSMENT</th>
                                        <th><input type="text" class="form-control"></th>
                                    </tr>
                                    <tr>
                                        <th>ORAL INTERVIEW</th>
                                        <th><input type="text" class="form-control"></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-4">
            <form action="?" method="get">
                <div class="form-group search-input">
                    <input type="search" class="form-control" name="_cadet" placeholder="Search...">
                </div>
            </form>
            @if ($_shipboard_monitoring)
                @foreach ($_shipboard_monitoring as $item)
                    <div class="card border-bottom border-4 border-0 border-primary">
                        <a href="?_midshipman={{ base64_encode($item->id) }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span
                                            class="text-primary"><b>{{ strtoupper($item->last_name . ', ' . $item->first_name) }}</b></span>
                                    </div>
                                    <div>
                                        <span>{{ $item->account ? $item->account->student_number : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                    </div>
                @endforeach
            @else
                <div class="card border-bottom border-4 border-0 border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span>NO DATA</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
