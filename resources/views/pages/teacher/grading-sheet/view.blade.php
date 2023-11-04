@extends('layouts.grading-layout-v2')
@section('page-title', 'Grading Sheet')
@section('page-content')
    @php
        $_subject_data = $_subject->submitted_grade('ad1', request()->input('_period'));
        $_grade_status = $_subject_data ? ($_subject_data->is_approved === 1 ? 'disabled' : '') : '';
    @endphp
    <div class="card p-0">
        <div class="card-body">
            <label for="" class="fw-bolder text-primary h4">SUBJECT CLASS DETAILS</label>
            <div class="row">
                <div class="col-md-6">
                    <small class="fw-bolder">SUBJECT NAME:</small> <br>
                    <label for="" class="fw-bolder h6">
                        {{ $_subject->curriculum_subject->subject->subject_name }} -
                        {{ $_subject->curriculum_subject->subject->subject_code }}
                    </label>
                </div>
                <div class="col-md">
                    <small class="fw-bolder">UNITS:</small> <br>
                    <label for="" class="fw-bolder h6">
                        {{ $_subject->curriculum_subject->subject->units }}
                        UNIT/S
                    </label>
                </div>
                <div class="col-md">
                    <small class="fw-bolder">SECTION:</small> <br>
                    <label for="" class="fw-bolder h6">
                        {{ $_subject->section->section_name }}
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="" class="fw-bolder text-primary">BULK UPLOADING</label>
                    <div class="row">
                        <div class="col-md">

                            <div class="form-group m-0 p-0">
                                <small class="fw-bolder text-muted">
                                    UPLOAD GRADE FILES
                                </small> <br>
                                <form action="{{ route('teacher.bulk-upload-grades') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="row ">
                                        <div class="col-md-8">
                                            <div class="form-group ">
                                                <input type="hidden" name="_section"
                                                    value="{{ Crypt::encrypt($_subject->id) }}">
                                                <input class="form-control form-control-sm border border-primary"
                                                    type="file" id="customFile" name="_file_grade" required
                                                    {{ $_grade_status }}>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary btn-sm"
                                                {{ $_grade_status }}>UPLOAD</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group p-0 m-0">
                                <small class="fw-bolder text-muted">
                                    DOWNLOAD TEMPLATE
                                </small> <br>
                                <label for="" class=""><a class="badge bg-primary"
                                        href="{{ route('teacher.export-grade') . '?_subject=' . request()->input('_subject') . '&_period=' . request()->input('_period') }}">Grading
                                        Sheet Template.xlsx</a></label>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="" class="fw-bolder text-primary">GRADING SHEET SETTING</label>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="fw-bolder text-muted">
                                PERIOD:
                            </small>
                            <a href="{{ route('teacher.grading-sheet') }}?_subject={{ base64_encode($_subject->id) }}&_period=midterm"
                                class="badge {{ request()->input('_period') == 'midterm' ? 'bg-info text-white' : 'bg-secondary' }} me-2 mt-2">MIDTERM</a>
                            <a href="{{ route('teacher.grading-sheet') }}?_subject={{ base64_encode($_subject->id) }}&_period=finals"
                                class="badge {{ request()->input('_period') == 'finals' ? 'bg-info text-white' : 'bg-secondary' }} me-2 mt-2">FINALS</a>
                        </div>
                        <div class="col-md-6">
                            <small for="" class="fw-bolder text-muted">
                                PRE-VIEW:
                            </small>
                            <span type="button" class="badge bg-info btn-form-grade" data-bs-toggle="modal"
                                data-bs-target=".grade-view-modal"
                                data-grade-url="/teacher/subjects/grading-sheet?_subject={{ base64_encode($_subject->id) }}&_period={{ request()->input('_period') }}&_preview=pdf&_form=ad1">
                                FORM AD-01
                            </span>
                            <span type="button" class="badge bg-primary btn-form-grade mt-2" data-bs-toggle="modal"
                                data-bs-target=".grade-view-modal"
                                data-grade-url="/teacher/subjects/grading-sheet?_subject={{ base64_encode($_subject->id) }}&_period=finals&_preview=pdf&_form=ad2">
                                FORM AD-02
                            </span>
                            {{-- <span type="button" class="badge bg-info btn-form-grade" data-bs-toggle="modal" data-bs-target=".grade-view-modal" data-grade-url="{{ route('teacher.grading-sheet-report') }}?class={{ base64_encode($_subject->id) }}&period={{ request()->input('_period') }}&form=ad1">
                            FORM AD-01
                        </span>
                        <span type="button" class="badge bg-primary btn-form-grade mt-2" data-bs-toggle="modal" data-bs-target=".grade-view-modal" data-grade-url="{{ route('teacher.grading-sheet-report') }}?class={{ base64_encode($_subject->id) }}&period=finals&form=ad2">
                            FORM AD-02
                        </span> --}}
                        </div>
                    </div>
                    <div class="form-group m-0 p-0">
                        <label class="fw-bolder text-primary ">
                            GRADE SUBMISSION
                        </label> <br>

                        @if ($_subject_data = $_subject->submitted_grade('ad1', request()->input('_period')))
                            @if ($_subject_data->is_approved === 1)
                                @php
                                    $_grade_status = 'disabled';
                                @endphp
                                <div class="row">
                                    <div class="col-md">
                                        <p>
                                            <small>GRADE SUBMISSION STATUS:</small>
                                            <span class="text-primary"> <b>APPROVED</b> </span>
                                            <br>
                                            <small>DATE SUBMISSION :</small> <span
                                                class="text-muted"><b>{{ $_subject_data->created_at->format('M d, Y') }}</b></span>
                                        </p>
                                    </div>
                                    <div class="col-md">
                                        <small>VERIFIED BY:</small>
                                        <span class="text-primary"><b>{{ strtoupper($_subject_data->approved_by) }}</b>
                                        </span>
                                        <br>
                                        <small>VERIFIED DATE :</small> <span
                                            class="text-muted"><b>{{ $_subject_data->updated_at->format('M d, Y') }}</b></span>
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
                                            <input type="hidden" name="_subject"
                                                value="{{ Crypt::encrypt($_subject->id) }}">
                                            <input type="hidden" name="_period" value="{{ $_subject_data->period }}">
                                            <input type="hidden" name="_form" value="{{ $_subject_data->form }}">
                                            <div class="form-group row">
                                                <div class="col-md">
                                                    <label for="" class="form-control border border-primary">FORM
                                                        {{ strtoupper($_subject_data->form) }}</label>
                                                </div>
                                                <div class="col-md">
                                                    <button type="submit" class="btn btn-info btn-block">RE-SUBMIT
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
                                                    <span class="text-danger"> <b>DISAPPRROVED</b> </span>
                                                    <br>
                                                    <small>DATE SUBMISSION :</small> <span
                                                        class="text-muted"><b>{{ $_subject_data->created_at->format('M d, Y') }}</b></span>
                                                </p>
                                            </div>
                                            <div class="col-md">
                                                <small>VERIFIED BY:</small>
                                                <span
                                                    class="text-success"><b>{{ strtoupper($_subject_data->approved_by) }}</b>
                                                </span>
                                                <br>
                                                <small>VERIFIED DATE :</small> <span
                                                    class="text-muted"><b>{{ $_subject_data->updated_at->format('M d, Y') }}</b></span>
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
                                        class="text-muted"><b>{{ $_subject_data->created_at->format('M d, Y') }}</b></span>
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
                                        <label for="" class="form-control border border-primary">
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
                    </div>

                </div>
            </div>
        </div>
    </div>

    @php
        $cNum = 1;
    @endphp
    <div class="card p-0">
        <div class="card-body table-responsive p-0">
            <main class="main-table">
                <div role="region" aria-label="data table" tabindex="0" class="primary">
                    <table style="height: 700px;">
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
                                        @php $cNum +=1; @endphp <th class=" text-center table-bordered text-primary fw-bolder">
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
                                        <th
                                            class={{ $_student->student->enrollment_assessment_paid->enrollment_cancellation ? 'text-danger fw-bolder' : 'text-primary fw-bolder' }}>
                                            {{ $_student->student->account ? $_student->student->account->student_number : '-' }}
                                            -
                                            {{ strtoupper($_student->last_name . ', ' . $_student->first_name) }}
                                        </th>
                                        @if ($_student->student->enrollment_academic_year($subject->academic->id)->enrollment_cancellation)
                                            <td colspan="{{ $cNum }}" class="text-danger fw-bolder">STUDENT
                                                DROPPED</td>
                                        @else
                                            @foreach ($_columns as $col)
                                                @for ($i = 1; $i <= $col[2]; $i++)
                                                    <td class="text-center table-bordered">
                                                        @php
                                                            $_score = $_student->student->subject_score([$_subject->id, request()->input('_period'), $col[1] . $i]);
                                                        @endphp
                                                        <input type="text" class="score-cell"
                                                            style="width: 38px; font-size:12px"
                                                            value="{{ $_score }}"
                                                            data-student="{{ $_student->student->id }}"
                                                            data-category="{{ $col[1] . $i }}"
                                                            data-section="{{ $_subject->id }}" {{ $_grade_status }}>
                                                    </td>
                                                @endfor
                                            @endforeach
                                        @endif


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
                <div class="modal-header">
                    <label for="" class="h6 fw-bolder text-primary">GRADE PRE-VIEW</label>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body p-0">
                    <iframe class="form-view iframe-placeholder" src="" width="100%" height="600px">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>
        let previewLink = null
        $(document).on('click', '.btn-form-grade', function(evt) {
            if (previewLink != null) {
                previewLink = null
                $('.form-view').attr('src', previewLink)
            }
            previewLink = $(this).data('grade-url')
            $('.form-view').attr('src', previewLink)


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
                    toastr.success(respond._respond.success)
                } else {
                    toastr.error("Error")
                }
                //console.log(respond)
            });
        }
    </script>
@endsection
