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
            {{ Auth::user()->staff->current_academic()->semester . ' | ' . Auth::user()->staff->current_academic()->school_year }}
        </li>
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
        <div class="col-md-12">
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

    </div>
@endsection
