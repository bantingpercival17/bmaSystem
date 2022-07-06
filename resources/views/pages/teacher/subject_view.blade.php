@extends('app')
@section('page-title', 'Previous Subjects')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item active">Previous Subjects</li>
    </ol>
@endsection
@section('page-content')
    @foreach ($_academics as $academic)
        @if ($academic->is_active != 1)
            <div class="container">
                <label for="" class="text-muted h4">|
                    {{ $academic->school_year . ' - ' . strtoupper($academic->semester) }}</label>
                <div class="row">
                    @if ($academic->teacher_subjects->count() > 0)
                        @foreach ($academic->teacher_subjects as $_subjects)

                            <div class="col-12 col-sm-4 col-md-4">
                                <a
                                    href="/teacher/subjects/grading-sheet?_s={{ Crypt::encrypt($_subjects->id) }}&_period=midterm">
                                    <div class="card card-primary ">
                                        <div class="card-body box-profile">
                                            <div>
                                                <h4 class="text-info">
                                                    {{ $_subjects->curriculum_subject->subject->subject_code }}
                                                    <small>{{ $_subjects->section->section_name }}</small>
                                                </h4>
                                            </div>
                                            <p class="text-muted"><b>
                                                    {{ $_subjects->curriculum_subject->subject->subject_name }}</b></p>

                                        </div>
                                    </div>
                                </a>

                            </div>
                        @endforeach
                    @else
                        <div class="col-12 col-sm-4 col-md-4">
                            <div class="card card-primary ">
                                <div class="card-body box-profile">
                                    <div>
                                        <p class="text-muted text-center h5">No Subjects Assigned </p>
                                    </div>
                                    <p class="text-muted ">
                                    </p>

                                </div>
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        @endif
    @endforeach
@endsection
