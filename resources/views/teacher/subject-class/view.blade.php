@extends('layouts.app-main')
@php
$_title = $_subject->section->section_name . ' | ' . $_subject->curriculum_subject->subject->subject_code;
@endphp
@section('page-title', $_title)
@section('page-mode', 'dark-mode')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('teacher.subject-list') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Subject</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ $_title }}</li>
@endsection
@section('page-content')
    <div class="nav-scroller text-center">
        <nav class="nav nav-underline bg-soft-primary  pb-0" aria-label="Secondary navigation ">
            <div class="d-flex" id="head-check">
                <a href="{{ Request::url() . '?_s=' . request()->input('_s') }}&_work_panel=student-list"
                    class="nav-link {{ request()->input('_work_panel') == 'student-list' || request()->input('_work_panel') == '' ? 'active' : '' }}">Student</a>
                <a href="{{ Request::url() . '?_s=' . request()->input('_s') }}&_work_panel=e-clearance"
                    class="nav-link {{ request()->input('_work_panel') == 'e-clearance' ? 'active' : '' }}">Semestral
                    Clearance</a>
                <a href="{{ Request::url() . '?_s=' . request()->input('_s') }}&_work_panel=record-book"
                    class="nav-link {{ request()->input('_work_panel') == 'record-book' ? 'active' : '' }}">Record Book</a>
            </div>
        </nav>
    </div>
    @if (request()->input('_work_panel') == '' || request()->input('_work_panel') == 'student-list')
        <div class="conatiner-fluid content-inner mt-6 py-0">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped" data-toggle="data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Student Number</th>
                                    <th>Midshipman Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($_students))
                                    @foreach ($_students as $_key => $_student)
                                        <tr>
                                            <td>{{ $_key + 1 }}</td>
                                            <td>{{ $_student->account->student_number }}</td>
                                            <td>{{ strtoupper($_student->last_name . ', ' . $_student->first_name) }}
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
        </div>
    @endif
    @if (request()->input('_work_panel') == 'e-clearance')
        <div class="conatiner-fluid content-inner mt-6 py-0">
            <form action="{{ route('teacher.e-clearance') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">E-Clearance</h4>
                        </div>
                        <div class="card-tool">
                            <button type="button" class="btn btn-info text-white" id="select-all">Select All</button>
                            <input type="hidden" name="_subject_class" value="{{ base64_encode($_subject->id) }}">
                            <button type="submit" class="btn btn-primary">SUBMIT</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Student Number</th>
                                        <th>Midshipman Name</th>
                                        <th>Clearance Status</th>
                                        <th>Comment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($_students))
                                        @foreach ($_students as $_key => $_student)
                                            <tr>
                                                <td>{{ $_student->account->student_number }}</td>
                                                <td>{{ strtoupper($_student->last_name . ', ' . $_student->first_name) }}
                                                </td>
                                                <td>
                                                    <div class="form-check d-block">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="flexCheckChecked-3-{{ $_key }}"
                                                            name="data[{{ $_key }}][e_clearance]"
                                                            value="{{ $_student->id }}"
                                                            {{ $_student->clearance($_subject->id) ? ($_student->clearance($_subject->id)->is_approved == 1 ? 'checked' : '') : '' }}>
                                                        <label class="form-check-label"
                                                            for="flexCheckChecked-3-{{ $_key }}">
                                                            CLEARED
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="" class="text-muted"><small><b>COMMENT</b></small>
                                                        </label>
                                                        <input type="text" class="form-control"
                                                            name="data[{{ $_key }}][comment]"
                                                            value="{{ $_student->clearance($_subject->id) ? $_student->clearance($_subject->id)->comments : '' }}">
                                                        <input type="hidden" name="data[{{ $_key }}][sId]"
                                                            value="{{ base64_encode($_student->id) }}">
                                                    </div>
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

    @endif
    @if (request()->input('_work_panel') == 'record-book')
        <div class="row">
            <div class="col-md-12">
                <iframe
                    src="{{ route('teacher.grading-sheet-main') }}?_s={{ base64_encode($_subject->id) }}&_period=midterm"
                    style="width: 100%;  height: 1000px; overflow: auto">
                </iframe>
            </div>
        </div>


    @endif
@endsection
@section('js')
    <script>
        $('#select-all').click(function(event) {
            if (this.checked) {
                console.log('checked:true')
                $(':checkbox').prop('checked',false);
            } else {
                console.log('checked:false')
                $(':checkbox').prop('checked', true);
                //$(':checkbox').prop('checked', false);
            }
        });
    </script>
@endsection
