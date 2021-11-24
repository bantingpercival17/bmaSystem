@extends('app')
@section('page-title', 'Previous Subjects')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/teacher/prevoius-subjects">Previous Subjects</a></li>
        <li class="breadcrumb-item active">
            {{ $_subject->section->section_name . ' | ' . $_subject->curriculum_subject->subject->subject_code }}</li>

    </ol>
@endsection
@section('css')
    {{-- <style>
        thead {
            display: table;
            width: calc(100% - 17px);
        }

        tbody {
            display: block;
            max-height: 300px;
            overflow-y: scroll;
           
        }

    </style> --}}
@endsection
@section('page-content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><b>GRADING SHEET</b></h3>
            <br>
            <div class="row">
                <p for="" class="text-muted col-md-8"><span>SUBJECT:</span>
                    <span class="h6"><b>{{ $_subject->curriculum_subject->subject->subject_name }}</b></span>
                </p>
                <p for="" class="text-muted col-md-4"><span>UNITS:</span>
                    <span class="h6"><b>{{ $_subject->curriculum_subject->subject->units }}</b></span>
                </p>
                <p for="" class="text-muted col-md-4"><span>SECTION:</span>
                    <span class="h6"><b>{{ $_subject->section->section_name }}</b></span>
                </p>
                <p for="" class="text-muted col-md-4"><span>PERIOD:</span>
                    @if (request()->input('_period') == 'midterm')
                        <span class="btn btn-info btn-xs" aria-disabled="true"><b>MIDTERM</b></span>
                        <a href="/teacher/subjects/grading-sheet?_s={{ Crypt::encrypt($_subject->id) }}&_period=finals"
                            class="btn btn-secondary btn-xs btn-rounded">FINALS</a>
                    @else

                        <a href="/teacher/subjects/grading-sheet?_s={{ Crypt::encrypt($_subject->id) }}&_period=midterm"
                            class="btn btn-secondary btn-xs">MIDTERM</a>
                        <span class="btn btn-info btn-xs"><b>FINALS</b></span>
                    @endif
                </p>
                <p for="" class="text-muted col-md-4"><span>PRE-VIEW:</span>
                    <a href="/teacher/subjects/grading-sheet?_s={{ Crypt::encrypt($_subject->id) }}&_period={{ request()->input('_period') }}&_preview=pdf&_form=ad1"
                        class="btn btn-warning btn-xs">FORM AD-01</a>
                    <a href="/teacher/subjects/grading-sheet?_s={{ Crypt::encrypt($_subject->id) }}&_period={{ request()->input('_period') }}&_preview=pdf&_form=ad2"
                        class="btn btn-primary btn-xs">FORM AD-02</a>
                </p>
                <div class="col-md-6">
                    @if ($_subject_data = $_subject->submitted_grade('ad1', request()->input('_period')))

                        @if ($_subject_data->is_approved === 1)
                            @php
                                $_grade_status = 'disabled';
                            @endphp
                            <div class="row">
                                <div class="col-md">
                                    <p>
                                        <small>GRADE SUBMISSION STATUS:</small>
                                        <span class="text-success"> <b>APPRROVED</b> </span>
                                        <br>
                                        <small>DATE SUBMISSION :</small> <span
                                            class="text-muted"><b>{{ $_subject_data->created_at->format('M-d-y') }}</b></span>
                                    </p>
                                </div>
                                <div class="col-md">
                                    <small>VERIFIED BY:</small>
                                    <span class="text-success"><b>{{ strtoupper($_subject_data->approved_by) }}</b>
                                    </span>
                                    <br>
                                    <small>VERIFIED DATE :</small> <span
                                        class="text-muted"><b>{{ $_subject_data->updated_at->format('M-d-y') }}</b></span>
                                </div>
                            </div>
                        @elseif($_subject_data->is_approved === 0)
                            @php
                                $_grade_status = '';
                            @endphp
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="/teacher/subjects/grade-submission" method="post">
                                        <label for="" class="text-muted">GRADE SUBMISSION</label>
                                        @csrf
                                        <input type="hidden" name="_subject" value="{{ Crypt::encrypt($_subject->id) }}">
                                        <input type="hidden" name="_period" value="{{ $_subject_data->period }}">
                                        <input type="hidden" name="_form" value="{{ $_subject_data->form }}">
                                        <div class="form-group row">
                                            <div class="col-md">
                                                <label for="" class="form-control">FORM
                                                    {{ strtoupper($_subject_data->form) }}</label>
                                            </div>
                                            <div class="col-md">
                                                <button type="submit" class="btn btn-info btn-block">RE-SUBMIT </button>
                                            </div>

                                        </div>

                                    </form>
                                </div>
                                <div class="col-md">
                                    <div class="row">
                                        <div class="col-md">
                                            <p>
                                                <small>GRADE SUBMISSION STATUS:</small>
                                                <span class="text-danger"> <b>DISAPPRROVED</b> </span>
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
                            @php
                                $_grade_status = 'disabled';
                            @endphp
                            <p>
                                <small>GRADE SUBMISSION STATUS:</small> <span class="text-muted"><b>FOR
                                        CHECKING</b></span>
                                <br>
                                <small>DATE SUBMISSION :</small> <span
                                    class="text-muted"><b>{{ $_subject_data->created_at->format('M-d-y') }}</b></span>
                            </p>
                        @endif
                    @else
                        @php
                            $_grade_status = '';
                        @endphp
                        <form action="/teacher/subjects/grade-submission" method="post">
                            <input type="hidden" name="_subject" value="{{ Crypt::encrypt($_subject->id) }}">
                            <input type="hidden" name="_period" value="{{ request()->input('_period') }}">
                            <label for="" class="text-muted">GRADE SUBMISSION</label>
                            @csrf
                            <div class="row">
                                <div class="form-group col-md">
                                    <label for="" class="form-control">
                                        {{ request()->input('_period') == 'midterm' ? 'Form AD-01 Midterm' : 'Form AD-02 Finals' }}
                                    </label>
                                    <input type="hidden" name="_form"
                                        value="{{ request()->input('_period') == 'midterm' ? 'ad1' : 'ad2' }}">

                                </div>
                                <div class="form-group col-md-4">
                                    <button type="submit" class="btn btn-info">SUBMIT </button>
                                </div>

                            </div>
                        </form>
                    @endif
                </div>
                <div class="col-md-6">
                    <form action="/teacher/subject-grade/bulk-upload" method="post" enctype="multipart/form-data">
                        @csrf
                        <label for="" class="text-success"><b>| BULK UPLOAD GRADES</b></label>
                        <div class="row">
                            <div class="form-group col-md">

                                <div class="custom-file">
                                    <input type="hidden" name="_section" value="{{ Crypt::encrypt($_subject->id) }}">
                                    <input type="file" class="custom-file-input" id="customFile" name="_file_grade" required
                                        {{ $_grade_status }}>
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <button type="submit" class="btn btn-success" {{ $_grade_status }}>UPLOAD</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0" style="height: 450px;">
            <table class="table table-fixed text-nowrap">
                <thead>
                    <tr class="text-center">
                        <th colspan="3"> {{ strtoupper(request()->input('_period')) }} - MIDSHIPMAN INFORMATION</th>
                        @foreach ($_columns as $col)
                            <th colspan="{{ $col[2] }}" class="bg-success  table-bordered">
                                {{ strtoupper($col[0]) }}
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        <th>NO</th>
                        <th>STUDENT NUMBER</th>
                        <th>COMPLETE NAME</th>
                        @foreach ($_columns as $col)
                            @for ($i = 1; $i <= $col[2]; $i++)
                                <th class="bg-info text-center table-bordered">
                                    {{ strtoupper($col[1]) . $i }}
                                </th>
                            @endfor

                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @if ($_students->count() > 0)
                        @foreach ($_students as $_key => $_student)
                            <tr>
                                <td>{{ $_key + 1 }}</td>
                                <td>{{ $_student->account->student_number }}</td>
                                <td>{{ strtoupper($_student->last_name . ', ' . $_student->first_name) }}
                                </td>
                                @foreach ($_columns as $col)
                                    @for ($i = 1; $i <= $col[2]; $i++)
                                        <th class="text-center table-bordered">
                                            @php
                                                $_score = $_student->subject_score([$_subject->id, request()->input('_period'), $col[1] . $i]);
                                            @endphp
                                            <input type="text" class="score-cell" style="width: 38px; font-size:12px"
                                                value="{{ $_score }}" data-student="{{ $_student->id }}"
                                                data-category="{{ $col[1] . $i }}" data-section="{{ $_subject->id }}"
                                                {{ $_grade_status }}>
                                        </th>
                                    @endfor

                                @endforeach

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="30">No Students</td>
                        </tr>
                    @endif
                </tbody>

            </table>
        </div>
    </div>

@section('js')
    <script>
        $(document).on('keyup', '.score-cell', function() {
            // Allow the numberica number only the inputs
            var _data = {
                '_student': $(this).data('student'),
                '_class': $(this).data('section'),
                '_type': $(this).data('category'),
                '_period': "{{ request()->input('_period') }}",
                '_score': $(this).val(),
            };
            if (_data['_score'] > 100) {
                toastr.error("Invalid Score input")
                $(this).val('');
            } else {
                if (event.keyCode == 13) {
                    _grade_save(_data)
                }
            }
        })

        function _grade_save(_data) {
            $.get('/teacher/grading-sheet/store', _data, function(respond) {
                if (respond._respond.status == 'success') {
                    toastr.success(respond._respond.message)
                } else {
                    toastr.error("Error")
                }
                console.log(respond)
            });
        }
    </script>
@endsection
@endsection
