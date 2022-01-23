@extends('layouts.app-main')
@php
$_title = 'Semestral Clearance';
@endphp
@section('page-title', $_title)
@section('page-mode', 'dark-mode')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('department.e-clearance') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('department.e-clearance') }}?_academic={{ base64_encode($_section->academic->id) }}">
            {{ $_section->academic->semester . ' | ' . $_section->academic->school_year }}
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_section->section_name }}
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">{{ $_section->section_name }}</h4>
                    <p class="mt-3">E-CLEARANCE</p>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <form action="{{ route('department-head.store-e-clearance') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md">
                                <div class="form-group">
                                    <label for="" class="form-label">Laboratory</label>
                                    <div class="form-check d-block">
                                        <input class="form-check-input input-select" data-check="laboratory" type="checkbox"
                                            id="flexCheckChecked-3">
                                        <label class="form-check-label" for="flexCheckChecked-3">
                                            Select All
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <label for="" class="form-label">Department Head</label>
                                    <div class="form-check d-block">
                                        <input class="form-check-input input-select" data-check="department-head"
                                            type="checkbox" id="flexCheckChecked-4">
                                        <label class="form-check-label" for="flexCheckChecked-4">
                                            Select All
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <button class="btn btn-primary" type="submit">
                                    Submit
                                </button>
                            </div>
                        </div>
                        <table id="datatable" class="table table-striped" data-toggle="data-table">
                            <thead>
                                <tr>
                                    <th>Midshipman Name</th>
                                    @foreach ($_section->subject_class as $_class)
                                        <th>{{ $_class->curriculum_subject->subject->subject_code }}</th>
                                    @endforeach
                                    <th class="text-center">Laboratory
                                    </th>
                                    <th class="text-center">Department Head

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($_students))
                                    @foreach ($_students as $_data)
                                        <tr>
                                            <td>
                                                <a
                                                    href="{{-- {{ route('teacher.student-clearance') }} --}}?_midshipman={{ base64_encode($_data->student_id) }}">
                                                    {{ strtoupper($_data->last_name . ', ' . $_data->first_name) }}
                                                </a>
                                            </td>
                                            @foreach ($_section->subject_class as $_class)
                                                <td>
                                                    <div class="form-check d-block ">
                                                        @if ($_data->clearance($_class->id))
                                                            @if ($_data->clearance($_class->id)->is_approved == 1)
                                                                <input class="form-check-input" type="checkbox" checked
                                                                    disabled>
                                                            @else
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="flexCheckChecked-3-" data-category="academic"
                                                                    data-content="{{ $_class->id }}"
                                                                    value="{{ $_data->id }}">
                                                            @endif
                                                        @else
                                                            <span class="text-danger"><b>-</b></span>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endforeach
                                            <td>
                                                <input class="form-check-input input-select-laboratory" type="checkbox"
                                                    name="laboratory[]" value="{{ $_data->id }}"
                                                    data-category="non-academic" data-content="laboratory"
                                                    {{ $_data->non_academic_clearance('laboratory') ? ($_data->non_academic_clearance('laboratory')->is_approved == 1 ? 'checked' : '') : '' }}>
                                            </td>
                                            <td>
                                                <input class="form-check-input input-select-department-head" type="checkbox"
                                                    name="dept_head[]" value="{{ $_data->id }}"
                                                    {{ $_data->non_academic_clearance('department-head') ? ($_data->non_academic_clearance('department-head')->is_approved == 1 ? 'checked' : '') : '' }}>
                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="{{ count($_section->subject_class) + 3 }}">No Data</th>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Midshipman Name</th>
                                    @foreach ($_section->subject_class as $_class)
                                        <th>{{ $_class->curriculum_subject->subject->subject_code }}</th>
                                    @endforeach
                                    <th>Laboratory</th>
                                    <th>Department Head</th>
                                </tr>
                            </tfoot>
                        </table>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
