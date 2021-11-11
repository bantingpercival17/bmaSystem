@extends('app')
@section('page-title', 'SUBJECTS LIST')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item active">SUBJECTS</li>
    </ol>
@endsection
@section('page-content')

    <div class="row">
        @if ($_subject->count() > 0)
            @foreach ($_subject as $subject)
                <div class="col-12 col-sm-4 col-md-4">
                    <a href="/teacher/subjects/grading-sheet?_s={{ Crypt::encrypt($subject->id) }}&_period=midterm">
                        <div class="card card-primary ">
                            <div class="card-body box-profile">
                                <div>
                                    <h4 class="text-info">{{ $subject->curriculum_subject->subject->subject_name }}
                                    </h4>
                                </div>
                                <p class="text-muted ">
                                    {{ $subject->section->section_name }}</p>

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
                            <h4 class="text-info">No Assigned Subjects</h4>
                        </div>
                        <p class="text-muted ">
                        </p>

                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
