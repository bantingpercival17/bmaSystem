@extends('layouts.app-main')
@php
$_title = 'Grade Submission';
@endphp
@section('page-title', $_title)
@section('page-mode', 'dark-mode')
@section('beardcrumb-content')
    @if (request()->input('_academic'))
        <li class="breadcrumb-item">
            <a href="{{ route('onboard.dashboard') }}">
                <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>{{ $_title }}</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ $_current_academic->semester . ' | ' . $_current_academic->school_year }}</li>
    @else
        <li class="breadcrumb-item active" aria-current="page">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </li>
    @endif

@endsection
@section('page-content')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Instruction List</h4>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive mt-4">
                        <table id="basic-table" class="table table-striped mb-0" role="grid">
                            <thead>
                                <tr>
                                    <th>Instruction Name</th>
                                    <th>Subjects</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if (count($_staffs) > 0)
                                    @foreach ($_staffs as $_data)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img class=" avatar-rounded img-fluid avatar-45 me-3 bg-soft-primary"
                                                        src="{{ asset($_data->profile_pic($_data)) }}" alt="profile">
                                                    <h6> {{ strtoupper($_data->first_name . ' ' . $_data->last_name) }}
                                                    </h6>
                                                </div>
                                            </td>
                                            <td>
                                                @if (count($_data->subject_handles) > 0)

                                                    @foreach ($_data->subject_handles as $_subjects)
                                                        <a href="" class="btn btn-outline-primary mt-2">
                                                            {{ $_subjects->curriculum_subject->subject->subject_code }}
                                                        </a>
                                                    @endforeach

                                                    {{-- <div class="iq-media-group iq-media-group-1">
                                                        @foreach ($_data->subject_handles as $_subjects)
                                                            <a href="#" class="iq-media-1">
                                                                <div class="icon iq-icon-box-3 rounded-pill">
                                                                    {{ $_subjects->curriculum_subject->subject->subject_code }}
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                        <a href="#" class="iq-media-1">
                                                            <div class="icon iq-icon-box-3 rounded-pill">PP</div>
                                                        </a>
                                                        <a href="#" class="iq-media-1">
                                                            <div class="icon iq-icon-box-3 rounded-pill">MM</div>
                                                        </a>
                                                    </div> --}}


                                                @else

                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-info">Pending</div>
                                            </td>

                                        </tr>
                                    @endforeach
                                @else

                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">Academic Year</h4>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-inline m-0 p-0">
                        @if ($_academics)
                            @foreach ($_academics as $_academic)
                                <li class="d-flex align-items-center">
                                    <div class="img-fluid bg-soft-info rounded-pill"><img
                                            src="../../assets/images/icons/08.png" alt="story-img"
                                            class="rounded-pill avatar-40"></div>
                                    <div class="ms-3 flex-grow-1">
                                        <h6>{{ $_academic->semester }}</h6>
                                        <p class="mb-0">{{ $_academic->school_year }}</p>
                                    </div>
                                    <a href="{{ route('department-head.grade-submission') }}?_academic={{ base64_encode($_academic->id) }}"
                                        class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                        <span class="btn-inner">
                                            <svg width="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </a>
                                </li>
                                <a
                                    href="{{ route('department-head.grade-submission') }}?_academic={{ base64_encode($_academic->id) }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class=" bg-soft-primary rounded p-3">
                                                    <svg width="32" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M16.4184 6.47H16.6232C19.3152 6.47 21.5 8.72 21.5 11.48V17C21.5 19.76 19.3152 22 16.6232 22H7.3768C4.6848 22 2.5 19.76 2.5 17V11.48C2.5 8.72 4.6848 6.47 7.3768 6.47H7.58162C7.60113 5.27 8.05955 4.15 8.8886 3.31C9.72741 2.46 10.8003 2.03 12.0098 2C14.4286 2 16.3891 4 16.4184 6.47ZM9.91273 4.38C9.36653 4.94 9.06417 5.68 9.04466 6.47H14.9553C14.9261 4.83 13.6191 3.5 12.0098 3.5C11.2587 3.5 10.4784 3.81 9.91273 4.38ZM15.7064 10.32C16.116 10.32 16.4379 9.98 16.4379 9.57V8.41C16.4379 8 16.116 7.66 15.7064 7.66C15.3065 7.66 14.9748 8 14.9748 8.41V9.57C14.9748 9.98 15.3065 10.32 15.7064 10.32ZM8.93737 9.57C8.93737 9.98 8.6155 10.32 8.20585 10.32C7.80595 10.32 7.47433 9.98 7.47433 9.57V8.41C7.47433 8 7.80595 7.66 8.20585 7.66C8.6155 7.66 8.93737 8 8.93737 8.41V9.57Z"
                                                            fill="#2ab462"></path>
                                                    </svg>
                                                </div>
                                                <div class="ms-5">
                                                    <h5 class="mb-1">{{ $_academic->semester }}</h5>
                                                    <h6 class="">{{ $_academic->school_year }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                            @endforeach

                        @endif

                    </ul>
                </div>
            </div>
            <p class="h4 text-primary">
                <b>Academic Year</b>
            </p>
            @if ($_academics)
                @foreach ($_academics as $_academic)
                    <a
                        href="{{ route('department-head.grade-submission') }}?_academic={{ base64_encode($_academic->id) }}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class=" bg-soft-primary rounded p-3">
                                        <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M16.4184 6.47H16.6232C19.3152 6.47 21.5 8.72 21.5 11.48V17C21.5 19.76 19.3152 22 16.6232 22H7.3768C4.6848 22 2.5 19.76 2.5 17V11.48C2.5 8.72 4.6848 6.47 7.3768 6.47H7.58162C7.60113 5.27 8.05955 4.15 8.8886 3.31C9.72741 2.46 10.8003 2.03 12.0098 2C14.4286 2 16.3891 4 16.4184 6.47ZM9.91273 4.38C9.36653 4.94 9.06417 5.68 9.04466 6.47H14.9553C14.9261 4.83 13.6191 3.5 12.0098 3.5C11.2587 3.5 10.4784 3.81 9.91273 4.38ZM15.7064 10.32C16.116 10.32 16.4379 9.98 16.4379 9.57V8.41C16.4379 8 16.116 7.66 15.7064 7.66C15.3065 7.66 14.9748 8 14.9748 8.41V9.57C14.9748 9.98 15.3065 10.32 15.7064 10.32ZM8.93737 9.57C8.93737 9.98 8.6155 10.32 8.20585 10.32C7.80595 10.32 7.47433 9.98 7.47433 9.57V8.41C7.47433 8 7.80595 7.66 8.20585 7.66C8.6155 7.66 8.93737 8 8.93737 8.41V9.57Z"
                                                fill="#2ab462"></path>
                                        </svg>
                                    </div>
                                    <div class="ms-5">
                                        <h5 class="mb-1">{{ $_academic->semester }}</h5>
                                        <h6 class="">{{ $_academic->school_year }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                @endforeach

            @endif
        </div>
    </div>
@endsection
