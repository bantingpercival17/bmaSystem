@extends('app')
@section('page-title', 'Student')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active">Student</li>
    </ol>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-4">
            <form action="" method="get" class="card">
                <div class="card-body">
                    <label for="" class="text-muted h5">| SEARCH STUDENT</label>
                    <div class="form-group">
                        <label for="" class="text-success">COURSE</label>
                        <select name="_course" id="" class="form-control">
                            @foreach ($_course as $course)
                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="text-success">ACADEMIC</label>
                        <select name="_academic" id="" class="form-control">
                            @foreach ($_academics as $data)
                                <option value="{{ $data->id }}">{{ $data->school_year . ' | ' . $data->semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="text-success">STUDENT NAME</label>
                        <input type="text" class="form-control" name="_student">


                    </div>
                    <p class="text-muted h6"> Format to search: Last name then use a coma to separate the
                        First Name </p>
                </div>
            </form>
            <form action="/administrator/students/imports" method="post" class="card"
                enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <label for="" class="text-muted h5">| EXPORT STUDENT DETAILS</label>
                    {{-- <div class="form-group">
                        <label for="" class="text-success">ATTACH FILE</label>
                        <input type="file" class="form-control" name="_file">
                    </div>
                    <button type="submit" class="btn btn-success">IMPORT</button> --}}
                </div>
            </form>
        </div>
        <div class="col-md-8">
            @if ($_students->count() > 0)
                @foreach ($_students as $_student)

                    <div class="card card-primary">
                        <div class="card-body box-profile">
                            <span class="text-success"><b>{{ $_student->account->student_number }}</b></span>
                            <a href="/registrar/students/view?_s={{ base64_encode($_student->id) }}">
                                <h4 class="text-info">
                                    <b> | {{ strtoupper($_student->last_name . ', ' . $_student->first_name) }} </b>
                                </h4>
                            </a>
                            <span class="text-success"><b>{{ $_student->account->campus_email }}</b></span><br>
                            <label
                                class="text-muted">{{ $_student->enrollment_assessment ? $_student->enrollment_assessment->course->course_name : '-' }}</label>
                        </div>
                    </div>


                @endforeach

            @else
                <div>
                    <div class="card card-primary">
                        <div class="card-body box-profile">
                            <div class="___class_+?24___">
                                <h4 class="text-muted">| No Such Data</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
