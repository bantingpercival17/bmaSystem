@extends('layouts.app-main')
@php
$_title = 'Semestral Grades';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a
            href="{{ route('registrar.semestral-clearance') }}{{ request()->input('_academic') ? '?_academic=' . request()->input('_academic') : '' }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </a>

    </li>
    <li class="breadcrumb-item ">
        <a href="">
            {{ $_section->course->course_name }}
        </a>

    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_section->section_name }}
    </li>

@endsection
@section('page-content')
    <div class=" mt-6 py-0">
        <form action="{{ route('registrar.semestral-clearance-store') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">{{ $_section->section_name }}</h4>
                        <h6 class="card-title">Semestral Grade</h6>
                    </div>
                    <div class="card-tool">
                        <a href="{{ route('registrar.semestral-grade-publish-all') }}?section={{ request()->input('_section') }}&academic={{ request()->input('_academic') }}"
                            class="btn btn-primary btn-sm">PUBLISH ALL GRADES</a>
                        {{-- <div class="form-check d-block">
                            <input class="form-check-input input-select" data-check="subject-clearance" type="checkbox"
                                id="flexCheckChecked-4">
                            <label class="form-check-label" for="flexCheckChecked-4">
                                Select All
                            </label>
                        </div>
                        <input type="hidden" name="_academic" value="{{ Auth::user()->staff->current_academic()->id }}">
                        <input type="hidden" name="_clearance_data" value="registrar">
                        <button type="submit" class="btn btn-primary">SUBMIT</button> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="datatable" data-toggle="data-table">
                            <thead>
                                <tr>
                                    <th>Student Number</th>
                                    <th>Midshipman Name</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($_section->student_section) > 0)
                                    @foreach ($_section->student_section as $_key => $_data)
                                        <tr>
                                            <td>{{ $_data->student->account ? $_data->student->account->student_number : '-' }}
                                            </td>
                                            <td>{{ strtoupper($_data->student->last_name . ', ' . $_data->student->first_name) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('registrar.semestral-grade-form-ad2') }}?_student={{ base64_encode($_data->id) }}&_section={{ request()->input('_section') }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}"
                                                    class="btn btn-sm btn-primary" target="_blank">FORM AD-02-A</a>
                                                @if ($_data->student->grade_publish)
                                                    <span class="badge bg-secondary">GRADE PUBLISHED <br>
                                                        {{ $_data->student->grade_publish->staff->user->name . ' - ' . $_data->student->grade_publish->created_at->format('F d, Y') }}</span>
                                                @else
                                                    <a href="{{ route('registrar.semestral-grade-publish') }}?_student={{ base64_encode($_data->id) }}&_academic={{ request()->input('_academic') }}"
                                                        class="btn btn-sm btn-info text-white">PUBLISH GRADE</a>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="3">No Data</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>

    </div>

@endsection
