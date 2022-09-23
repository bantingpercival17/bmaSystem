@extends('layouts.app-main')
@php
$_title = 'Midshipman Onboarding Monitoring';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item active">
        <a
            href="{{ route('exo.semestral-clearance') }}{{ request()->input('_academic') ? '?_academic=' . request()->input('_academic') : '' }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </a>

    </li>
@endsection
@section('page-content')
    <section>
        <p class="display-6 fw-bolder text-primary">Onboard Midshipman's</p>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive mt-4">
                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                        <thead>
                            @php
                                $_level = [4, 3, 2, 1];
                            @endphp
                            <tr class="text-center">
                                <th>COURSE</th>
                                @foreach ($_level as $level)
                                    <td>{{ strtoupper(Auth::user()->staff->convert_year_level($level)) }}</td>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($_courses as $_course)
                                @if ($_course->id != 3)
                                    <tr>
                                        <th>{{ $_course->course_name }}</th>
                                        @foreach ($_level as $level)
                                            <td>
                                                <span class="text-primary fw-bolder">
                                                    {{ count($_course->student_onboarding($level)->get()) }}
                                                </span>
                                                <span class="text-muted fw-bolder">
                                                    /
                                                    {{ count($_course->enrollment_list_by_year_level($level)->get()) }}
                                                </span>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">STUDENT LIST</h4>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive mt-4">
                <table id="datatable" class="table table-striped" data-toggle="data-table">
                    <thead>
                        <tr>
                            <th>STUDENT NUMBER</th>
                            <th>FULL NAME</th>
                            <th>COURSE</th>
                            <th>EMBARKETION STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($_students) > 0)
                            @foreach ($_students as $student)
                                <tr>
                                    <td>
                                        {{ $student->account ? $student->account->student_number : '-' }}
                                    </td>
                                    <th> {{ $student ? strtoupper($student->last_name . ', ' . $student->first_name . ' ' . $student->middle_name) : '-' }}
                                    </th>
                                    <th>

                                        {{ $student->enrollment_assessment ? $student->enrollment_assessment->course->course_code : '' }}
                                    </th>
                                    <th>
                                        @php
                                            $_enrollment_status = $student->enrollment_application_status(Auth::user()->staff->current_academic())->first();
                                        @endphp
                                        @if ($_enrollment_status)
                                            @if ($_enrollment_status->payment_assessments)
                                                @if ($_enrollment_status->payment_assessments->payment_assessment_paid)
                                                    <span class="badge bg-info">ELIGIBLE TO EMBARK</span>
                                                @else
                                                    <span class="badge bg-info">ENROLLMENT PROCESS IS ONGOING</span>
                                                    <br>
                                                    <small for="" class="fw-bolder text-warning">Please Coordinate
                                                        to
                                                        ACCOUNTING's Office</small>
                                                @endif
                                            @else
                                                <span class="badge bg-info">ENROLLMENT PROCESS IS ONGOING</span>
                                                <br>
                                                <small for="" class="fw-bolder text-warning">Please Coordinate to
                                                    ACCOUNTING's Office</small>
                                            @endif
                                        @else
                                            <span class="badge bg-danger">NOT ENROLLED</span>
                                            <br>
                                            <small for="" class="fw-bolder text-warning">Please Coordinate to
                                                Registrar's Office</small>
                                        @endif
                                    </th>
                                    <th>
                                        <a href="" class="btn btn-info btn-primary btn-sm text-white">ACCEPT</a>
                                        <a href="" class="btn btn-info btn-danger btn-sm">REJECT</a>
                                        <a href="" class="btn btn-info btn-warning btn-sm">MEDICAL</a>
                                    </th>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


{{-- @section('js')
    <script>
        @if (Auth::user()->staff)
            @if (count(Auth::user()->staff->message_ticket_concern()) > 0)
                Toastify({
                    text: "You have  {{ count(Auth::user()->staff->message_ticket_concern()) }} unread concern, <a href='{{ route('ticket.view') }}' class='text-warning'> see here </a> ",
                    //duration: 3000,
                    //close: true,
                    //gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
            @endif
        @endif
    </script>
@endsection --}}
