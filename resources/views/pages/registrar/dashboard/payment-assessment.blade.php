@extends('layouts.app-main')
@section('page-title', 'Dashboard')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('registrar.dashboard') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Dashboard
        </a>

    </li>
    <li class="breadcrumb-item active" aria-current="page">
        Payment Assessment
    </li>
@endsection
@section('page-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Payment Assessment Overview</h4>
                <small class="text-muted mt-2 fw-bolder">{{ $_course->course_name }}</small>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive mt-4">
                <table id="basic-table" class="table table-striped mb-0" role="grid">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Year Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($_course->payment_assessment))
                            @foreach ($_course->payment_assessment as $_payment)
                                <tr>
                                    <td>{{ $_payment->student->first_name . ' ' . $_payment->student->last_name }}</td>
                                    <td>
                                        @if ($_payment->course_id == 3)
                                            Grade {{ $_payment->year_level }}
                                        @else
                                            {{ $_payment->year_level }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2">No Data</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
