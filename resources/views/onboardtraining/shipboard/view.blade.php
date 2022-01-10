@extends('layouts.app-main')
@php
$_title = 'Shipboard Monitoring';
@endphp
@section('page-title', $_title)
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active"> Midshipman</li>

    </ol>
@endsection
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
            xmlns="http://www.w3.org/2000/svg">
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
                                <span>COURE: <b>
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
                                        <label class="form-label-sm"><small>NAME OF SHIPPING</small></label>
                                        <br>
                                        <label class="text-primary">
                                            <b>{{ $_midshipman->shipboard_training->vessel_name }}</b>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md">
                                        <label class="form-label-sm"><small>NAME OF SHIPPING</small></label>
                                        <br>
                                        <label class="text-primary">
                                            <b>{{ $_midshipman->shipboard_training->vessel_name }}</b>
                                        </label>
                                    </div>
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
                        <hr>
                        <div class="card-body">
                            <div class="header-title d-flex justify-content-between">
                                <span class="h5 text-primary fw-bolder">NARATIVE REPORT</span>
                            </div>
                            @include('layouts.icon-main')
                            <div class="swiper swiper-container mySwiper position-relative">
                                <div class="swiper-button-next1">
                                    @yield('icon-left')
                                </div>
                                <div class="swiper-wrapper row-cols-2 row-cols-lg-4 list-inline">
                                    @foreach ($_midshipman->narative_report as $_journal)
                                        <div class="swiper-slide">
                                            <div class="text-center">
                                                <div class="card-body ">
                                                    <a
                                                        href=" {{ route('onboard.journal') }}?_j={{ base64_encode($_journal->month) }}">
                                                        <i class="icon text-muted">
                                                            @yield('icon-document')
                                                        </i>

                                                        <h6 class="text-muted mt-3">
                                                            {{ date('F - Y', strtotime($_journal->month)) }}
                                                        </h6>
                                                    </a>


                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-button-prev1">
                                    @yield('icon-right')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
        </div>
        <div class="col-md-4">
            <div class="form-group search-input">
                <input type="search" class="form-control" placeholder="Search...">
            </div>
            @if ($_shipboard_monitoring)
                @foreach ($_shipboard_monitoring as $item)
                    <div class="card border-bottom border-4 border-0 border-primary">
                        <a href="?_midshipman={{ base64_encode($item->student_id) }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span
                                            class="text-primary"><b>{{ strtoupper($item->student->last_name . ', ' . $item->student->first_name) }}</b></span>
                                    </div>
                                    <div>
                                        <span>{{ $item->student->account->student_number }}</span>
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
