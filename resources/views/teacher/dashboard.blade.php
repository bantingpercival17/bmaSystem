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

                                <span class="h4 text-info">
                                    <b>
                                        {{ $subject->curriculum_subject->subject->subject_code }}
                                    </b>
                                </span>
                                <br>
                                <small class="h5 text-muted"><b> {{ $subject->section->section_name }}</b></small>
                                <br>
                                <small class="text-muted">
                                    <b>
                                        {{ $subject->curriculum_subject->subject->subject_name }}
                                    </b>
                                </small>

                            </div>
                            <div class="card-footer">
                                {{-- {{ $_subject->submitted_grade('ad1', 'midterm') }} --}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <span class="text-info"><small><b>MIDTERM GRADE SUBMISSION</b></small></span>
                                        <br>
                                        @if ($_subject_data = $subject->submitted_grade('ad1', 'midterm'))

                                            @if ($_subject_data->is_approved === 1)
                                                <div class="row">
                                                    <div class="col-md">
                                                        <small class="text-muted">GRADING STATUS:</small>
                                                        <br>
                                                        <span class="text-success"> <b>APPRROVED</b> </span>
                                                        <br>
                                                        <small class="text-muted">DATE SUBMISSION :</small>
                                                        <br>
                                                        <span class="text-muted">
                                                            <b>{{ $_subject_data->created_at->format('M-d-y') }}</b>
                                                        </span>

                                                    </div>
                                                    <div class="col-md">
                                                        <small class="text-muted">VERIFIED BY:</small>
                                                        <br>
                                                        <span
                                                            class="text-success"><b>{{ strtoupper($_subject_data->approved_by) }}</b>
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">VERIFIED DATE :</small>
                                                        <br>
                                                        <span
                                                            class="text-muted"><b>{{ $_subject_data->updated_at->format('M-d-y') }}</b></span>
                                                    </div>
                                                </div>
                                            @elseif($_subject_data->is_approved === 0)
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <form action="/teacher/subjects/grade-submission" method="post">
                                                            <label for="" class="text-muted">GRADE SUBMISSION</label>
                                                            @csrf
                                                            <input type="hidden" name="_subject"
                                                                value="{{ Crypt::encrypt($_subject->id) }}">
                                                            <input type="hidden" name="_period"
                                                                value="{{ $_subject_data->period }}">
                                                            <input type="hidden" name="_form"
                                                                value="{{ $_subject_data->form }}">
                                                            <div class="form-group row">
                                                                <div class="col-md">
                                                                    <label for="" class="form-control">FORM
                                                                        {{ strtoupper($_subject_data->form) }}</label>
                                                                </div>
                                                                <div class="col-md">
                                                                    <button type="submit"
                                                                        class="btn btn-info btn-block">RE-SUBMIT </button>
                                                                </div>

                                                            </div>

                                                        </form>
                                                    </div>
                                                    <div class="col-md">
                                                        <div class="row">
                                                            <div class="col-md">
                                                                <p>
                                                                    <small>GRADE SUBMISSION STATUS:</small>
                                                                    <span class="text-danger"> <b>DISAPPRROVED</b>
                                                                    </span>
                                                                    <br>
                                                                    <small>DATE SUBMISSION :</small> <span
                                                                        class="text-muted"><b>{{ $_subject_data->created_at->format('M-d-y') }}</b></span>
                                                                </p>
                                                            </div>
                                                            <div class="col-md">
                                                                <small>VERIFIED BY:</small>
                                                                <span
                                                                    class="text-success"><b>{{ strtoupper($_subject_data->approved_by) }}</b>
                                                                </span>
                                                                <br>
                                                                <small>VERIFIED DATE :</small> <span
                                                                    class="text-muted"><b>{{ $_subject_data->updated_at->format('M-d-y') }}</b></span>
                                                                <br>

                                                            </div>
                                                        </div>
                                                        <p>
                                                            <small>COMMENT:</small>
                                                            <span class="text-warning"> {{ $_subject_data->comments }}
                                                            </span>
                                                        </p>
                                                    </div>

                                                </div>

                                            @else

                                                <small class="text-muted">GRADING STATUS:</small>
                                                <span class="text-muted">
                                                    <b>FOR CHECKING</b>
                                                </span>
                                                <br>
                                                <small class="text-muted">DATE SUBMISSION :</small>
                                                <span class="text-muted">
                                                    <b>{{ $_subject_data->created_at->format('M-d-y') }}</b>
                                                </span>

                                            @endif
                                        @else
                                            <small class="text-muted">GRADING STATUS:</small>
                                            <span class="text-muted">
                                                <b>EDITTING</b>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-12">

                                        @if ($subject->submitted_grade('ad1', 'midterm'))
                                            <span class="text-info"><small><b>FINAL GRADE SUBMISSION</b></small></span>
                                            <br>
                                            @if ($_subject_data = $subject->submitted_grade('ad1', 'final'))
                                                @if ($_subject_data->is_approved === 1)
                                                    <div class="row">
                                                        <div class="col-md">
                                                            <small class="text-muted">GRADING STATUS:</small>
                                                            <br>
                                                            <span class="text-success"> <b>APPRROVED</b> </span>
                                                            <br>
                                                            <small class="text-muted">DATE SUBMISSION :</small>
                                                            <br>
                                                            <span class="text-muted">
                                                                <b>{{ $_subject_data->created_at->format('M-d-y') }}</b>
                                                            </span>

                                                        </div>
                                                        <div class="col-md">
                                                            <small class="text-muted">VERIFIED BY:</small>
                                                            <br>
                                                            <span
                                                                class="text-success"><b>{{ strtoupper($_subject_data->approved_by) }}</b>
                                                            </span>
                                                            <br>
                                                            <small class="text-muted">VERIFIED DATE :</small>
                                                            <br>
                                                            <span
                                                                class="text-muted"><b>{{ $_subject_data->updated_at->format('M-d-y') }}</b></span>
                                                        </div>
                                                    </div>
                                                @elseif($_subject_data->is_approved === 0)
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <form action="/teacher/subjects/grade-submission" method="post">
                                                                <label for="" class="text-muted">GRADE
                                                                    SUBMISSION</label>
                                                                @csrf
                                                                <input type="hidden" name="_subject"
                                                                    value="{{ Crypt::encrypt($_subject->id) }}">
                                                                <input type="hidden" name="_period"
                                                                    value="{{ $_subject_data->period }}">
                                                                <input type="hidden" name="_form"
                                                                    value="{{ $_subject_data->form }}">
                                                                <div class="form-group row">
                                                                    <div class="col-md">
                                                                        <label for="" class="form-control">FORM
                                                                            {{ strtoupper($_subject_data->form) }}</label>
                                                                    </div>
                                                                    <div class="col-md">
                                                                        <button type="submit"
                                                                            class="btn btn-info btn-block">RE-SUBMIT
                                                                        </button>
                                                                    </div>

                                                                </div>

                                                            </form>
                                                        </div>
                                                        <div class="col-md">
                                                            <div class="row">
                                                                <div class="col-md">
                                                                    <p>
                                                                        <small>GRADE SUBMISSION STATUS:</small>
                                                                        <span class="text-danger"> <b>DISAPPRROVED</b>
                                                                        </span>
                                                                        <br>
                                                                        <small>DATE SUBMISSION :</small> <span
                                                                            class="text-muted"><b>{{ $_subject_data->created_at->format('M-d-y') }}</b></span>
                                                                    </p>
                                                                </div>
                                                                <div class="col-md">
                                                                    <small>VERIFIED BY:</small>
                                                                    <span
                                                                        class="text-success"><b>{{ strtoupper($_subject_data->approved_by) }}</b>
                                                                    </span>
                                                                    <br>
                                                                    <small>VERIFIED DATE :</small> <span
                                                                        class="text-muted"><b>{{ $_subject_data->updated_at->format('M-d-y') }}</b></span>
                                                                    <br>

                                                                </div>
                                                            </div>
                                                            <p>
                                                                <small>COMMENT:</small>
                                                                <span class="text-warning">
                                                                    {{ $_subject_data->comments }}
                                                                </span>
                                                            </p>
                                                        </div>

                                                    </div>

                                                @else

                                                    <small class="text-muted">GRADING STATUS:</small>
                                                    <span class="text-muted">
                                                        <b>FOR CHECKING</b>
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">DATE SUBMISSION :</small>
                                                    <span class="text-muted">
                                                        <b>{{ $_subject_data->created_at->format('M-d-y') }}</b>
                                                    </span>

                                                @endif
                                            @else
                                                <small class="text-muted">GRADING STATUS:</small>
                                                <span class="text-muted">
                                                    <b>EDITTING</b>
                                                </span>
                                            @endif
                                        @else

                                        @endif

                                    </div>
                                </div>


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
