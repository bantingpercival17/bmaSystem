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
        <div class="col-7">
            <div class="row">
                @if (request()->input('d'))
                    <label for="" class="h2 text-success">| {{ $_course->course_name }}</label>
                    @php
                        $_year_level = $_course->id == 3 ? [11, 12] : [4, 3, 2, 1];
                        $_semester = ['First Semester', 'Second Semester'];
                        
                    @endphp
                    @foreach ($_year_level as $_key => $item)
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <b>{{ $_course->id == 3 ? 'GRADE ' . $item : $item . ' CLASS' }} </b>
                                    </h3>

                                    <div class="card-tools">
                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-lg"
                                            disabled><i class="fa fa-print"></i> </button>
                                    </div>
                                </div>

                                <div class="card-body table-responsive p-0">
                                    @foreach ($_semester as $_sem)
                                        @php
                                            $_tUnits = 0;
                                            $_tLechr = 0;
                                            $_tLabhr = 0;
                                        @endphp
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th colspan="5">{{ strtoupper($_sem) }}</th>
                                                </tr>
                                                <tr class="text-center">
                                                    <th>SUBJECT CODE</th>
                                                    <th>SUBJECT DESCRIPTION</th>
                                                    <th>LEC. HOURS</th>
                                                    <th>LAB. HOURS</th>
                                                    <th>UNITS</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($_subject = $_curriculum->subject([$_course->id, $item, $_sem])->count() > 0)
                                                    @foreach ($_curriculum->subject([$_course->id, $item, $_sem])->get() as $_subject)
                                                        @php
                                                            $_tLechr += $_subject->lecture_hours;
                                                            $_tLabhr += $_subject->laboratory_hours;
                                                            $_tUnits += $_subject->units;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $_subject->subject->subject_code }}</td>
                                                            <td>{{ $_subject->subject->subject_name }}</td>
                                                            <td>{{ $_subject->subject->lecture_hours }}</td>
                                                            <td>{{ $_subject->subject->laboratory_hours }}</td>
                                                            <td>{{ $_subject->subject->units }}</td>
                                                            <td>
                                                                <button class="btn btn-danger btn-remove"
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
                                        </table>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    @if ($_course_view->count() > 0)
                        @foreach ($_course_view as $data)
                            <div class="col-md-12">
                                <a
                                    href="{{ url()->current() }}?view={{ request()->input('view') }}&d={{ base64_encode($data->id) }}">
                                    <div class="card card-primary ">
                                        <div class="card-body box-profile">
                                            <div class="">
                                                <h4 class="
                                                text-info">
                                                    {{ $data->course_name }}</h4>
                                            </div>
                                            <p class="text-muted ">{{ $data->school_level }}</p>

                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                @endif

            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header">
                    <label for="" class="h5 text-success">CREATE SUBJECT</label>
                </div>
                <div class="card-body">

                    <form role="form" action="{{ route('registrar.curriculum-store') }}" method="POST">
                        @csrf
                        <label for="" class="h5 text-success">SUBJECT DETAILS</label>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" name="curriculum" value="{{ request()->input('view') }}">
                                    <input type="hidden" name="department" value="{{ request()->input('d') }}">
                                    <input type="text" class="form-control course-code" placeholder="Course Code"
                                        name="course_code">
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control" placeholder="Subject Unit" name="_units">
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
                                    <input type="text" class="form-control" placeholder="Lecture Hours" name="_hours">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Laboratory Hours"
                                        name="_lab_hour">
                                </div>

                            </div>
                        </div>
                        <label for="" class="text-success h5">COURSE DETAILS</label>
                        <div class="form-group">
                            <label for="" class="text-muted">COURSE</label>
                            @if (!request()->input('d'))
                                <select name="course" id="" class="form-control">
                                    @foreach ($_course as $item)
                                        <option value="{{ $item->id }}">{{ $item->course_name }}
                                        </option>
                                    @endforeach
                                    <option value="both">Both BSMT & BSME</option>
                                </select>
                            @else
                                <label for="" class="form-control">{{ $_course->course_name }}</label>
                                <input type="hidden" value="{{ $_course->id }}" name="course">
                            @endif

                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label for="" class="text-muted">YEAR LEVEL</label>
                                    <select name="_input_7" id="" class="form-control">
                                        <option value="11">Grade 11</option>
                                        <option value="12">Grade 12</option>
                                        <option value="4"> 4th Class</option>
                                        <option value="3">3rd Class</option>
                                        <option value="2">2nd Class</option>
                                        <option value="1">1st Class</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="" class="text-muted">SEMESTER</label>
                                    <select name="_input_8" id="" class="form-control">
                                        <option value="First Semester">First Semester</option>
                                        <option value="Second Semester">Second Semester</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-info btn-block">Create</button>
                        </div>

                    </form>
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
    </script>
@endsection
