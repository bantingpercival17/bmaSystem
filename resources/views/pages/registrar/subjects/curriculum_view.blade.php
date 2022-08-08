@extends('layouts.app-main')
@php
$_title = 'Subjects';
@endphp
@section('page-title', $_curriculum->curriculum_name)
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('registrar.subject-view') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_curriculum->curriculum_name }}
    </li>

@endsection
@section('page-content')
    <div class="row">
        <div class="col-12">
            <div class="row">
                @if ($_course_view->count() > 0)
                    @foreach ($_course_view as $data)
                        <div class="col-md">
                            <a
                                href="{{ url()->current() }}?view={{ request()->input('view') }}&d={{ base64_encode($data->id) }}">
                                <div class="card card-primary ">
                                    <div class="card-body box-profile">
                                        <div class="">
                                            <label class="h5 text-primary fw-bolder">
                                                {{ $data->course_name }}</label>
                                        </div>
                                        <small class="text-muted fw-bolder ">{{ $data->school_level }}</small>

                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
                @if (request()->input('d'))
                    @php
                        $_year_level = $_course->id == 3 ? [11, 12] : [4, 3, 2, 1];
                        $_semester = ['First Semester', 'Second Semester'];
                        
                    @endphp
                    @foreach ($_year_level as $_key => $item)
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-tools float-end">
                                        <button class="btn btn-primary btn-modal-subject" data-bs-toggle="modal"
                                            data-year="{{ Auth::user()->staff->convert_year_level($item) }}"
                                            data-tab="nav-link-{{ $item }}"
                                            data-bs-target=".model-add-subject">ADD</button>
                                        {{-- <button class="btn btn-info btn-sm" disabled></button> --}}
                                    </div>
                                    <small
                                        class="fw-bolder text-muted">{{ strtoupper($_course->course_name . ' - ' . $_curriculum->curriculum_name) }}</small>

                                    <h3 class="card-title text-primary">
                                        <b>{{ strtoupper(Auth::user()->staff->convert_year_level($item)) }}
                                        </b>
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-fill" id="myTab-three" role="tablist">
                                        @foreach ($_semester as $key => $tab)
                                            <li class="nav-item">
                                                <a class="nav-link nav-link-{{ $item }} {{ $key == 0 ? 'active' : '' }}"
                                                    id="{{ str_replace(' ', '-', strtolower($tab)) . '-' . $item }}"
                                                    data-bs-toggle="tab" data-semester="{{ $tab }}"
                                                    href="#{{ str_replace(' ', '-', strtolower($tab)) . '-' . $item }}-content"
                                                    role="tab" aria-controls="home"
                                                    aria-selected="{{ $key == 0 ? true : false }}">
                                                    {{ strtoupper($tab) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content" id="myTabContent-4">
                                        @foreach ($_semester as $key => $sem)
                                            <div class="tab-pane fade show {{ $key == 0 ? 'active' : '' }}"
                                                id="{{ str_replace(' ', '-', strtolower($sem)) . '-' . $item }}-content"
                                                role="tabpanel"
                                                aria-labelledby="{{ str_replace(' ', '-', strtolower($sem)) . '-' . $item }}">

                                                <div class="content">
                                                    @php
                                                        $_tUnits = 0;
                                                        $_tLechr = 0;
                                                        $_tLabhr = 0;
                                                    @endphp
                                                    <div class="table-responsive">
                                                        <table class="table table-strip">
                                                            <thead class="text-center">
                                                                <tr class="text-center">
                                                                    <th>SUBJECT CODE</th>
                                                                    <th>SUBJECT DESCRIPTION</th>
                                                                    <th style="width: 5px;">LEC. HOURS</th>
                                                                    <th style="width: 5px;">LAB. HOURS</th>
                                                                    <th style="width: 5px;">UNITS</th>
                                                                    <th>ACTION</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if ($_subject = $_curriculum->subject([$_course->id, $item, $sem])->count() > 0)
                                                                    @foreach ($_curriculum->subject([$_course->id, $item, $sem])->get() as $_subject)
                                                                        <tr>
                                                                            <td>{{ $_subject->subject->subject_code }}</td>
                                                                            <td width="30px;">
                                                                                {{ $_subject->subject->subject_name }}</td>
                                                                            <td style="width: 5px;">
                                                                                {{ $_subject->subject->lecture_hours }}
                                                                            </td>
                                                                            <td style="width: 5px;">
                                                                                {{ $_subject->subject->laboratory_hours }}
                                                                            </td>
                                                                            <td style="width: 5px;">
                                                                                {{ $_subject->subject->units }}</td>
                                                                            <td>
                                                                                <button
                                                                                    class="btn btn-success  btn-sm btn-modal-subject"
                                                                                    data-bs-toggle="modal"
                                                                                    data-year="{{ Auth::user()->staff->convert_year_level($item) }}"
                                                                                    data-tab="nav-link-{{ $item }}"
                                                                                    data-curriculum="{{ base64_encode($_subject->id) }}"
                                                                                    data-bs-target=".model-update-subject">EDIT</button>
                                                                                <button
                                                                                    class="btn btn-danger btn-sm btn-remove"
                                                                                    data-url="{{ route('registrar.remove-curriculum-subject') . '?_subject=' . base64_encode($_subject->id) }}">REMOVE</button>
                                                                            </td>
                                                                        </tr>
                                                                        @php
                                                                            $_tLechr += $_subject->subject->lecture_hours;
                                                                            $_tLabhr += $_subject->subject->laboratory_hours;
                                                                            $_tUnits += $_subject->subject->units;
                                                                        @endphp
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="6">NO SUBJECT</td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="2">TOTAL</th>
                                                                    <td>{{ $_tLechr }}</td>
                                                                    <td>{{ $_tLabhr }}</td>
                                                                    <td>{{ $_tUnits }}</td>
                                                                    <td></td>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                    {{-- <table class="table table-head-fixed text-nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="5">{{ strtoupper($sem) }}</th>
                                                            </tr>
                                                            <tr class="text-center">
                                                                <th>SUBJECT CODE</th>
                                                                <th>SUBJECT DESCRIPTION</th>
                                                                <th style="width: 5px;">LEC. HOURS</th>
                                                                <th style="width: 5px;">LAB. HOURS</th>
                                                                <th style="width: 5px;">UNITS</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if ($_subject = $_curriculum->subject([$_course->id, $item, $sem])->count() > 0)
                                                                @foreach ($_curriculum->subject([$_course->id, $item, $sem])->get() as $_subject)
                                                                    @php
                                                                        $_tLechr += $_subject->lecture_hours;
                                                                        $_tLabhr += $_subject->laboratory_hours;
                                                                        $_tUnits += $_subject->units;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $_subject->subject->subject_code }}</td>
                                                                        <td width="30px;">
                                                                            {{ $_subject->subject->subject_name }}</td>
                                                                        <td style="width: 5px;">
                                                                            {{ $_subject->subject->lecture_hours }}</td>
                                                                        <td style="width: 5px;">
                                                                            {{ $_subject->subject->laboratory_hours }}</td>
                                                                        <td style="width: 5px;">
                                                                            {{ $_subject->subject->units }}</td>
                                                                        <td>
                                                                            <button class="btn btn-danger btn-sm btn-remove"
                                                                                data-url="{{ route('registrar.remove-curriculum-subject') . '?_subject=' . base64_encode($_subject->id) }}">remove</button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td colspan="5">NO SUBJECT</td>
                                                                </tr>
                                                            @endif

                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2">TOTAL</td>
                                                                <td>{{ $_tLechr }}</td>
                                                                <td>{{ $_tLabhr }}</td>
                                                                <td>{{ $_tUnits }}</td>
                                                            </tr>
                                                        </tfoot>
                                                    </table> --}}
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @else
                @endif

            </div>
        </div>

    </div>
    <div class="modal fade model-add-subject" tabindex="-1" role="dialog" aria-labelledby="model-add-subjectTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title modal-title fw-bolder text-primary" id="model-add-subjectTitle">ADD SUBJECT
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" action="{{ route('registrar.curriculum-store') }}" method="POST"
                        id="modal-form-add">
                        @csrf
                        <small for="" class="h5 text-primary fw-bolder">SUBJECT DETAILS</small>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" name="curriculum" value="{{ request()->input('view') }}">
                                    <input type="hidden" name="department" value="{{ request()->input('d') }}">
                                    <input type="text" class="form-control course-code" placeholder="Course Code"
                                        name="course_code">
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control" placeholder="Subject Unit"
                                        name="_units">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Subject Description"
                                name="_subject_name">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Lecture Hours"
                                        name="_hours">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Laboratory Hours"
                                        name="_lab_hour">
                                </div>

                            </div>
                        </div>
                        <small for="" class="text-primary h5 fw-bolder">COURSE DETAILS</small>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <small class="fw-bolder text-muted">
                                        COURSE
                                    </small>
                                    @if (request()->input('d'))
                                        <label for="" class="form-control">{{ $_course->course_name }}</label>
                                        <input type="hidden" value="{{ $_course->id }}" name="course">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <small class="fw-bolder text-muted">
                                        YEAR LEVEL
                                    </small>
                                    <input type="text" class="form-control year-level" name="_input_7">
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <small class="fw-bolder text-muted">
                                        SEMESTER
                                    </small>
                                    <input type="text" class="form-control semester" name="_input_8">
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm btn-modal-form" data-form="modal-form-add">SAVE
                        CONTENT</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade model-update-subject" tabindex="-1" role="dialog"
        aria-labelledby="model-update-subjectTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title modal-title fw-bolder text-primary" id="model-update-subjectTitle">UPDATE
                        SUBJECT
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" action="{{ route('registrar.update-curriculum-subject') }}" method="POST"
                        id="modal-form-update">
                        @csrf
                        <input type="hidden" name="curriculum_subject" class="curriculum">
                        <small for="" class="h5 text-primary fw-bolder">SUBJECT DETAILS</small>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" name="curriculum" value="{{ request()->input('view') }}">
                                    <input type="hidden" name="department" value="{{ request()->input('d') }}">
                                    <input type="text" class="form-control course-code" placeholder="Course Code"
                                        name="course_code">
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control units" placeholder="Subject Unit"
                                        name="_units">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control subject-name" placeholder="Subject Description"
                                name="_subject_name">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <input type="text" class="form-control lec-hours" placeholder="Lecture Hours"
                                        name="_hours">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control lab-hours" placeholder="Laboratory Hours"
                                        name="_lab_hour">
                                </div>

                            </div>
                        </div>
                        <small for="" class="text-primary h5 fw-bolder">COURSE DETAILS</small>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <small class="fw-bolder text-muted">
                                        COURSE
                                    </small>
                                    @if (request()->input('d'))
                                        <label for="" class="form-control">{{ $_course->course_name }}</label>
                                        <input type="hidden" value="{{ $_course->id }}" name="course">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <small class="fw-bolder text-muted">
                                        YEAR LEVEL
                                    </small>
                                    <input type="text" class="form-control year-level" name="_input_7">
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <small class="fw-bolder text-muted">
                                        SEMESTER
                                    </small>
                                    <input type="text" class="form-control semester" name="_input_8">
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm btn-modal-form"
                        data-form="modal-form-update">UPDATE
                        CONTENT</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        // Remove
        $('.btn-remove').click(function(event) {
            Swal.fire({
                title: 'Subject Course',
                text: "Do you want to remove?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var _url = $(this).data('url');
                if (result.isConfirmed) {
                    window.location.href = _url
                }
            })
            event.preventDefault();
        })
        $('.btn-modal-subject').click(function(event) {
            var tab = $(this).data('tab')
            var year_level = $(this).data('year');
            var semester = $("." + tab + '.active').data('semester');
            if ($(this).data('curriculum')) {
                console.log($(this).data('curriculum'))
                $.get("{{ route('registrar.view-curriculum-subject') }}?curriculum=" + $(this).data('curriculum'),
                    function(respond) {
                        console.log(respond._curriculum_subject)
                        $('.course-code').val(respond._subject.subject_code)
                        $('.units').val(respond._subject.units)
                        $('.subject-name').val(respond._subject.subject_name)
                        $('.lab-hours').val(respond._subject.laboratory_hours)
                        $('.lec-hours').val(respond._subject.lecture_hours)
                        $('.curriculum').val(respond._curriculum_subject.id)
                    })
            }
            $('.semester').val(semester)
            $('.year-level').val(year_level)
            event.preventDefault();
        })
        $('.btn-modal-form').click(function(event) {
            Swal.fire({
                title: 'Course Subject',
                text: "Do you want to add?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var form = $(this).data('form');
                if (result.isConfirmed) {

                    console.log(form)
                    document.getElementById(form).submit()
                }
            })
            event.preventDefault();
        })
    </script>
@endsection
