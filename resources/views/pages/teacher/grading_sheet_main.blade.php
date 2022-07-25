@extends('layouts.grading-layout')
@section('page-title', 'Grading Sheet')
@section('page-content')

    <div class="card">
        <div class="card-header">
            <a href="{{ route('teacher.subject-view') . '?_subject=' . base64_encode($_subject->id) }}"
                class="btn btn-primary btn-sm rounded-pill mt-2">
                <i class="icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </i>
            </a>
            <h3 class="card-title"><b>GRADING SHEET</b></h3>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <span><small><b>SUBJECT:</b></small></span>
                            <br>
                            <span class="h6"><b>{{ $_subject->curriculum_subject->subject->subject_name }} -
                                    {{ $_subject->curriculum_subject->subject->subject_code }}</b></span>
                        </div>
                        <div class="col-md-6">
                            <span><small><b>UNITS:</b></small></span> <br>
                            <span class="h6"><b>{{ $_subject->curriculum_subject->subject->units }}
                                    UNIT/S</b></span>
                        </div>
                        <div class="col-md-6">
                            <small><b>SECTION:</b></small> <br>
                            <span class="h6"><b>{{ $_subject->section->section_name }}</b></span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <small><b>PERIOD:</b></small> <br>
                            <a href="{{ route('teacher.grading-sheet') }}?_subject={{ base64_encode($_subject->id) }}&_period=midterm"
                                class="btn {{ request()->input('_period') == 'midterm' ? 'btn-info text-white' : 'btn-secondary' }} btn-sm me-2 mt-2">MIDTERM</a>
                            <a href="{{ route('teacher.grading-sheet') }}?_subject={{ base64_encode($_subject->id) }}&_period=finals"
                                class="btn {{ request()->input('_period') == 'finals' ? 'btn-info text-white' : 'btn-secondary' }} btn-sm me-2 mt-2">FINALS</a>
                        </div>
                        <div class="col-md-6">
                            <small><b>PRE-VIEW:</b></small> <br>
                            <button type="button" class="btn btn-warning btn-xs btn-form-grade mt-2" data-bs-toggle="modal"
                                data-bs-target=".grade-view-modal"
                                data-grade-url="/teacher/subjects/grading-sheet?_subject={{ base64_encode($_subject->id) }}&_period={{ request()->input('_period') }}&_preview=pdf&_form=ad1">
                                FORM AD-01</button>
                            <button type="button" class="btn btn-primary btn-xs btn-form-grade mt-2" data-bs-toggle="modal"
                                data-bs-target=".grade-view-modal"
                                data-grade-url="/teacher/subjects/grading-sheet?_subject={{ base64_encode($_subject->id) }}&_period={{ request()->input('_period') }}&_preview=pdf&_form=ad2">
                                FORM AD-02</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="" class="text-primary"><b>| GRADE SUBMISSION</b></label>
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
                            @csrf
                            <div class="row">
                                <div class="form-group col-md">
                                    <label for="" class="form-control">
                                        {{ request()->input('_period') == 'midterm' ? 'Form AD-01 Midterm' : 'Form AD-02 Finals' }}
                                    </label>
                                    <input type="hidden" name="_form" value="ad1">

                                </div>
                                <div class="form-group col-md-4">
                                    <button type="submit" class="btn btn-primary ">SUBMIT </button>
                                </div>

                            </div>
                        </form>
                    @endif
                    <form action="{{ route('teacher.bulk-upload-grades') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <label for="" class="text-primary"><b>| BULK UPLOAD GRADES</b></label>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="hidden" name="_section" value="{{ Crypt::encrypt($_subject->id) }}">
                                    <input class="form-control" type="file" id="customFile" name="_file_grade" required
                                        {{ $_grade_status }}>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary" {{ $_grade_status }}>UPLOAD</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <main>
                <div role="region" aria-label="data table" tabindex="0" class="primary">
                    <table>
                        <thead>
                            <tr>
                                <th class="pin text-primary fw-bolder"> {{ strtoupper(request()->input('_period')) }} -
                                    MIDSHIPMAN
                                    INFORMATION
                                </th>
                                @foreach ($_columns as $col)
                                    <th colspan="{{ $col[2] }}" class="text-center text-primary fw-bolder">
                                        {{ strtoupper($col[0]) }}
                                    </th>
                                @endforeach
                            </tr>
                            <tr>
                                <th class="pin text-primary fw-bolder">STUDENT NO. - COMPLETE NAME</th>
                                @foreach ($_columns as $col)
                                    @for ($i = 1; $i <= $col[2]; $i++)
                                        <th class=" text-center table-bordered text-primary fw-bolder">
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
                                        <th class="text-primary fw-bolder">
                                            {{ $_student->student->account->student_number }} -
                                            {{ strtoupper($_student->last_name . ', ' . $_student->first_name) }}
                                        </th>
                                        @foreach ($_columns as $col)
                                            @for ($i = 1; $i <= $col[2]; $i++)
                                                <td class="text-center table-bordered">
                                                    @php
                                                        $_score = $_student->student->subject_score([$_subject->id, request()->input('_period'), $col[1] . $i]);
                                                    @endphp
                                                    <input type="text" class="score-cell"
                                                        style="width: 38px; font-size:12px" value="{{ $_score }}"
                                                        data-student="{{ $_student->id }}"
                                                        data-category="{{ $col[1] . $i }}"
                                                        data-section="{{ $_subject->id }}" {{ $_grade_status }}>
                                                </td>
                                            @endfor
                                        @endforeach

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="28">No Students</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    <div class="modal fade grade-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <iframe class="form-view iframe-placeholder" src="" width="100%" height="600px">
                </iframe>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>
        $(document).on('click', '.btn-form-grade', function(evt) {
            $('.form-view').attr('src', $(this).data('grade-url'))
        });
        $(document).on('keydown', '.score-cell', function(e) {
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
                if (event.keyCode === 13) {
                    _grade_save(_data)
                }
                if (event.keyCode === 9) {
                    _grade_save(_data)
                }
            }
        })

        function _grade_save(_data) {
            $.get('/teacher/grading-sheet/store', _data, function(respond) {
                if (respond._respond.status == 'success') {
                    //Toastr.success(respond._respond.message);
                    toastr.success(respond._respond.message)
                } else {
                    toastr.error("Error")
                }
                //console.log(respond)
            });
        }
    </script>
@endsection
