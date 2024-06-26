@extends('layouts.app-main')
@php
    $_title = 'Medical Appointment Schedule';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item ">
        <a href="/">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Overview
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_title }}
    </li>
@endsection
@section('page-content')
    <section>
        <p class="display-6 fw-bolder text-primary">{{ $_title }}</p>
        <div class="card">
            <div class="card-header">
                <label for="" class="fw-bolder text-primary">CREATE SCHEDULE</label>
            </div>
            <div class="card-body">
                <form action="{{ route('medical.appoitnment-schedule-store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-md-6 form-group">
                            <small class="fw-bolder text-muted">DATE</small>
                            <input type="date" class="form-control" name="date">
                            @error('date')
                                <span class="badge bg-danger">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="col-lg-4 col-md-6 form-group">
                            <small class="fw-bolder text-muted">CAPACITY</small>
                            <input type="number" class="form-control" name="capacity">
                            @error('capacity')
                                <span class="badge bg-danger mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-6 form-group">
                            <button type="submit" class="btn btn-primary w-100 mt-5">SUBMIT</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <label for="" class="fw-bolder h4 text-primary">LIST OF SCHEDULE</label>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>APPOINTMENT DATE</th>
                            <th>APPLICANT SCHEDULE</th>
                            <th>CAPACITY</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($dates) > 0)
                            @foreach ($dates as $date)
                                <tr>
                                    <td><b>{{ $date->date }}</b></td>
                                    <td>{{ $date->number_of_applicant() }}</td>
                                    <td>{{ $date->capacity }}</td>
                                    <td>{{ $date->is_close == false ? 'OPEN' : 'CLOSE' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">NO DATA</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection
