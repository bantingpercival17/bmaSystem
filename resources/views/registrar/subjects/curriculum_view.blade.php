@extends('app')
@section('page-title', $_curriculum->curriculum_name)
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/registrar/subjects">Subjects</a></li>
        <li class="breadcrumb-item active">{{ $_curriculum->curriculum_name }}</li>

    </ol>
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
                                                            <td>{{ $_subject->subject_code }}</td>
                                                            <td>{{ $_subject->subject_name }}</td>
                                                            <td>{{ $_subject->lecture_hours }}</td>
                                                            <td>{{ $_subject->laboratory_hours }}</td>
                                                            <td>{{ $_subject->units }}</td>

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

                    <form role="form" action="/registrar/subjects/curriculum/" method="POST">
                        @csrf
                        <label for="" class="h5 text-success">SUBJECT DETAILS</label>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" name="_input_" value="{{ request()->input('view') }}">
                                    <input type="hidden" name="_input_0" value="{{ request()->input('d') }}">
                                    <input type="text" class="form-control course-code" placeholder="Course Code"
                                        name="_input_1">
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control" placeholder="Subject Unit" name="_input_5">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Subject Description" name="_input_2">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Lecture Hours" name="_input_3">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Laboratory Hours"
                                        name="_input_4">
                                </div>

                            </div>
                        </div>
                        <label for="" class="text-success h5">COURSE DETAILS</label>
                        <div class="form-group">
                            <label for="" class="text-muted">COURSE</label>
                            @if (!request()->input('d'))
                                <select name="_input_6" id="" class="form-control">
                                    @foreach ($_course as $item)
                                        <option value="{{ $item->id }}">{{ $item->course_name }}
                                        </option>
                                    @endforeach
                                    <option value="both">Both BSMT & BSME</option>
                                </select>
                            @else
                                <label for="" class="form-control">{{ $_course->course_name }}</label>
                                <input type="hidden" value="{{ $_course->id }}" name="_input_6">
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
