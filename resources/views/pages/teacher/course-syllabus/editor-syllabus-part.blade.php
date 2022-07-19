@extends('layouts.app-main')
@php
$_title = 'Course Syllabus';
@endphp
@section('page-title', $_title)
@section('page-mode', 'dark-mode')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('teacher.course-syllabus') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Course Syllabus</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ $_title }}</li>
@endsection
@section('page-content')
    <div class="content">
        <div class="card">
            <div class="card-body">
                <label for=""
                    class="fw-bolder text-primary h4">{{ $_course_syllabus->subject->subject_code }}</label>
                <br> <small>{{ $_course_syllabus->subject->subject_name }}</small>

                <a href="{{ route('teacher.course-syllabus-report') . '?_course_syllabus=' . base64_encode($_course_syllabus->id) }}"
                    class="btn btn-primary float-end">GENERATE FORM</a>
            </div>
            <div class="card-body row">
                <div class="col-md">
                    <a
                        href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part1' }}">
                        COURSE DETAILS</a>
                </div>

                <div class="col-md">
                    <a
                        href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part2' }}">
                        LEARNING OUTCOMES & TOPIC
                    </a>
                </div>

            </div>
        </div>
        @if (request()->input('part') == 'part1')
            <div class="part-one-content">
                <div class="card">
                    <div class="card-header">
                        <label for="" class="fw-bolder text-primary h6">STCW REFERENCE</label>
                    </div>
                    <div class="card-body">
                        <div class="stcw-reference">
                            @foreach ($_course_syllabus->stcw_reference as $stcw)
                                <div class="stcw-reference">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <small>STCW TABLE</small> <br>
                                            <label for="" class="text-primary h5">{{ $stcw->stcw_table }}</label>

                                            <div class="mt-3 row">
                                                <div class="col-md">
                                                    <label class="badge bg-primary btn-add" data-bs-toggle="modal"
                                                        data-bs-target=".model-add-reference" data-title="ADD FUNCTION"
                                                        data-id="{{ base64_encode($stcw->id) }}" data-stcw="function"
                                                        data-url="{{ route('teacher.stcw-reference-add') . '?stcw_reference=stcw-table&stcw=' . base64_encode($stcw->id) }}">
                                                        ADD FUNCTION
                                                    </label>
                                                </div>
                                                <div class="col-md">
                                                    <small class="text-primary btn-remove"
                                                        data-url="{{ route('teacher.stcw-reference-remove') . '?stcw_reference=stcw-table&stcw=' . base64_encode($stcw->id) }}">REMOVE</small>

                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-md-10">
                                            @foreach ($stcw->function_content as $function)
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <small>FUNCTION</small> <br>
                                                        <label for="" class="text-primary">
                                                            {{ $function->function_content }}</label>
                                                        <div class="mt-3 row">
                                                            <div class="col-md">
                                                                <label class="badge bg-primary btn-add"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target=".model-add-reference"
                                                                    data-title="ADD COMPETENCE"
                                                                    data-id="{{ base64_encode($function->id) }}"
                                                                    data-stcw="competence"
                                                                    data-url="{{ route('teacher.stcw-reference-add') . '?stcw_reference=stcw-table&stcw=' . base64_encode($function->id) }}">
                                                                    ADD COMPETENCE
                                                                </label>
                                                            </div>
                                                            <div class="col-md">
                                                                <small class="text-primary btn-remove"
                                                                    data-url="{{ route('teacher.stcw-reference-remove') . '?stcw_reference=function&stcw=' . base64_encode($function->id) }}">REMOVE</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md">
                                                        @foreach ($function->competence_content as $competence)
                                                            <div class="row">
                                                                <div class="col-md">
                                                                    <small>COMPETENCE</small> <br>
                                                                    <label for="" class="text-primary">
                                                                        {{ $competence->competence_content }}</label>
                                                                    <div class="mt-3 row">
                                                                        <div class="col-md">
                                                                            <label class="badge bg-primary btn-add"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target=".model-add-reference"
                                                                                data-title="ADD KUP"
                                                                                data-id="{{ base64_encode($competence->id) }}"
                                                                                data-stcw="competence"
                                                                                data-url="{{ route('teacher.stcw-reference-add') . '?stcw_reference=stcw-table&stcw=' . base64_encode($competence->id) }}">
                                                                                ADD KUP
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md">
                                                                            <small class="text-primary btn-remove"
                                                                                data-url="{{ route('teacher.stcw-reference-remove') . '?stcw_reference=competence&stcw=' . base64_encode($competence->id) }}">REMOVE</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md">
                                                                    @foreach ($competence->kup_content as $kup)
                                                                        <div class="row">
                                                                            <div class="col-md">
                                                                                <small>KUP</small> <br>
                                                                                <label for="" class="text-primary">
                                                                                    {{ $kup->kup_content }}</label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>


                                                </div>
                                            @endforeach


                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach

                            {{-- If Course Syllabus have STCW Reference --}}
                        </div>
                        <form action="{{ route('teacher.store-stcw-reference') }}" id="form-stcw" method="post">
                            @csrf
                            <input type="hidden" name="syllabus" value="{{ $_course_syllabus->id }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="form-label">STCW TABLE</small>
                                    <label for="" class="form-label"></label>
                                    <input type="text" class="form-control" name="stcw_table">
                                </div>
                                <div class="col-md">
                                    <small for="" class="form-label">FUNCTION</small><br>

                                    <input type="text" class="form-control" name="function">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <small class="form-label">COMPETENCE</small>
                                    <label for="" class="form-label"></label>
                                    <input type="text" class="form-control" name="competence">
                                </div>
                                <div class="col-md-12">
                                    <small for="" class="form-label">KUP</small><br>
                                    <textarea name="kup" id="editor" cols="30" rows="5" class="form-control"></textarea>
                                </div>
                            </div>
                            <button class=" btn btn-primary float-end mt-4 add-stcw" data-form="form-stcw">ADD</button>

                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <label for="" class="fw-bolder text-primary h6">COURSE OUTCOME</label>

                    </div>
                    <div class="card-body">
                        <div class="course-outcome">
                            <table class="table table-strip">
                                <thead>
                                    <tr>
                                        <th>PROGRAM OUTCOME</th>
                                        <th>COURSE OUTCOME</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($_course_syllabus->course_outcome) > 0)
                                        @foreach ($_course_syllabus->course_outcome as $course_outcome)
                                            <tr>
                                                <td>{{ $course_outcome->program_outcome }}</td>
                                                <td>{{ $course_outcome->course_outcome }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2">NO DATE</td>

                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <form action="{{ route('teacher.course-outcome-store') }}" id="course-outcome-form"
                            method="post" clas>
                            @csrf
                            <input type="hidden" name="_syllabus" value="{{ base64_encode($_course_syllabus->id) }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <small for="" class="form-label">PROGRAM OUTCOME</small><br>

                                    <input type="text" class="form-control" name="_program_outcome">
                                </div>
                                <div class="col-md">
                                    <small class="form-label">COURSE OUTCOME</small>
                                    <label for="" class="form-label"></label>
                                    <input type="text" class="form-control" name="_course_outcome">
                                </div>

                            </div>
                            <small class="text-warning">If Program Outcome more than one use a comma to seperate</small>

                            <div class="">
                                <button class="btn btn-primary btn-sm mt-3 float-end add-stcw"
                                    data-form="course-outcome-form">ADD COURSE OUTCOME</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <label for="" class="fw-bolder text-primary h6">COURSE SYLLABUS DETAILS</label>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('teacher.course-details-store') }}" id="course-details-form"
                            method="post">
                            @csrf
                            <input type="hidden" name="_syllabus" value="{{ base64_encode($_course_syllabus->id) }}">
                            @if ($_course_syllabus->details)
                                <input type="hidden" name="_details" value="{{ $_course_syllabus->details->id }}">
                            @else
                            @endif
                            <div class="row">
                                <div class="col-md-12">
                                    <small for="" class="form-label">COURSE INTAKE LIMITATIONS</small><br>
                                    <textarea name="course_limitations" id="course_limitations" cols="30" rows="5" class="form-control">{{ $_course_syllabus->details ? $_course_syllabus->details->course_intake_limitations : '' }}</textarea>
                                </div>
                                <div class="col-md-12 mt-5">
                                    <small for="" class="form-label">FACULTY REQUIREMENTS</small><br>
                                    <textarea name="faculty_requirements" id="faculty_requirements" cols="30" rows="5" class="form-control">{{ $_course_syllabus->details ? $_course_syllabus->details->faculty_requirements : '' }}</textarea>
                                </div>
                                <div class="col-md-12 mt-5">
                                    <small for="" class="form-label">TEACHING FACILITIES & EQUIPMENT</small><br>
                                    <textarea name="teaching_facilities" id="teaching_facilities" cols="30" rows="5" class="form-control">{{ $_course_syllabus->details ? $_course_syllabus->details->teaching_facilities_and_equipment : '' }}</textarea>
                                </div>
                                <div class="col-md-12 mt-5">
                                    <small for="" class="form-label">TEACHING AIDS</small><br>
                                    <textarea name="teaching_aids" id="teaching_aids" cols="10" rows="5" class="form-control">
@if ($_course_syllabus->details)
@foreach (json_decode($_course_syllabus->details->teaching_aids) as $item)
{{ trim($item) }}
@endforeach
@endif
</textarea>
                                </div>
                                <div class="col-md-12 mt-5">
                                    <small for="" class="form-label">REFERENCE/S</small><br>
                                    <textarea name="references" id="references" cols="30" rows="5" class="form-control">
@if ($_course_syllabus->details)
@foreach (json_decode($_course_syllabus->details->references) as $item)
{{ trim($item) }}
@endforeach
@endif
</textarea>
                                </div>
                            </div>


                            <div class="">
                                <button class="btn btn-primary btn-sm mt-3 float-end add-stcw"
                                    data-form="course-details-form">ADD SYLLABUS DETAILS</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade model-add-reference" tabindex="-1" role="dialog"
                aria-labelledby="model-add-referenceTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title modal-title" id="model-add-referenceTitle"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('teacher.stcw-reference-add') }}" method="post"
                                id="modal-form-add">
                                @csrf
                                <input type="hidden" name="stcw" class="stcw" value="">
                                <input type="hidden" name="stcw_reference" class="stcw_reference" value="">
                                <div class="form-group">
                                    <small for="" class="form-label">CONTENT</small>
                                    <textarea name="content" id="modal-editor"cols="30" rows="5" class="form-control"></textarea>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary btn-sm btn-modal-form"
                                data-form="modal-form-add">SAVE
                                CONTENT</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (request()->input('part') == 'part2')
            <div class="card learning-outcome">
                <div class="card-header">
                    <label for="" class="text-primary fw-bolder">VIEW LEARNING OUTCOME</label>
                </div>
                <div class="card-body">
                    @if (count($_course_syllabus->learning_outcomes) > 0)
                        <div class="learning-outline-content">
                            @foreach ($_course_syllabus->learning_outcomes as $key => $learning_outcome)
                                <div class="lo-{{ $learning_outcome->id }}">
                                    <div class="row ">

                                        <div class="col-md-6">
                                            <small class="fw-bolder">LEARNING OUTLINE</small><br>
                                            <label for=""
                                                class="text-primary h5">{{ strtoupper($learning_outcome->learning_outcomes) }}</label>
                                        </div>
                                        <div class="col-md-2">
                                            <small class="fw-bolder">COURSE OUTLINE</small><br>
                                            <label for="" class="text-primary h5">
                                                {{ substr($learning_outcome->course_outcome->course_outcome, 0, 3) }}
                                            </label>

                                        </div>
                                        <div class="col-md">
                                            <small class="fw-bolder">TERM</small><br>
                                            <label for=""
                                                class="text-primary h5">{{ strtoupper($learning_outcome->term) }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md">
                                            <small class="fw-bolder">THEORETICAL</small><br>
                                            <label for=""
                                                class="text-primary h5">{{ strtoupper($learning_outcome->theoretical) }}</label>
                                        </div>
                                        <div class="col-md">
                                            <small class="fw-bolder">DEMONSTRATION</small><br>
                                            <label for=""
                                                class="text-primary h5">{{ strtoupper($learning_outcome->demonstration) }}</label>
                                        </div>
                                        <div class="col-md">
                                            <small class="fw-bolder">REFERENCE</small><br>
                                            <label for="" class="text-primary h5">

                                                @if ($learning_outcome->reference)
                                                    @foreach (json_decode($learning_outcome->reference) as $item)
                                                        {{ substr($item, 0, 3) }}
                                                    @endforeach
                                                @endif

                                            </label>
                                        </div>
                                        <div class="col-md">
                                            <small class="fw-bolder">TEACHING AIDS</small><br>
                                            <label for="" class="text-primary h5">

                                                @if ($learning_outcome->teaching_aids)
                                                    @foreach (json_decode($learning_outcome->teaching_aids) as $item)
                                                        {{ substr($item, 0, 3) }},
                                                    @endforeach
                                                @endif

                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    {{-- <small for="" class="btn btn-outline-primary btn-sm">EDIT</small> --}}
                                    <small for="" class="text-primary btn-remove fw-bolder"
                                        data-url="{{ route('teacher.syllabus-learning-outcome-remove') . '?learning_outcome=' . base64_encode($learning_outcome->id) }}">REMOVE</small>
                                </div>
                                <div class="learning-outcome-topics">
                                    <label for="" class="fw-bolder text-info">SUB-TOPICS</label>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    @else
                        <p>ADD LEARNING OUTCOME</p>
                    @endif
                </div>
            </div>
            <div class="card learning-outcome">
                <div class="card-header">
                    <label for="" class="text-primary fw-bolder">CREATE TOPICS</label>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.syllabus-learning-outcome') }}" method="post"
                        id="form-learning-outcome">
                        @csrf
                        <input type="hidden" name="_syllabus" value="{{ base64_encode($_course_syllabus->id) }}">
                        <div class="row">
                            <div class="col-md form-group">
                                <small class="fw-bolder">COURSE OUTCOME</small>
                                <select name="_course_outcome" id="" class="form-select">
                                    @foreach ($_course_syllabus->course_outcome as $co)
                                        <option value="{{ $co->id }}">{{ $co->course_outcome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md form-group">
                                <small class="fw-bolder">TERM</small>
                                <select name="_term" id="" class="form-select">
                                    <option value="midterm">MIDTERM</option>
                                    <option value="finals">FINALS</option>
                                </select>
                            </div>

                            <div class="col-md form-group">
                                <small class="fw-bolder">THEORETICAL</small>
                                <input type="number" class="form-control" name="_theoretical">
                            </div>
                            <div class="col-md-4 form-group">
                                <small class="fw-bolder">DEMONSTRATION / PRACTICAL WORK</small>
                                <input type="number" class="form-control" name="_demonstration">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md form-group">
                                <small class="fw-bolder">LEARNING OUTCOMES</small>
                                <input type="text" class="form-control" name="_learning_outcomes">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group">
                                <small class="fw-bolder">WEEK/S</small>
                                <div class="">
                                    @for ($i = 1; $i <= 18; $i++)
                                        <div class="form-check d-block col-md">
                                            <input class="form-check-input" type="checkbox"
                                                value="week-{{ $i }}" name="weeks[]"
                                                id="flexCheckDefault{{ $i }}">
                                            <label class="form-check-label" for="flexCheckDefault{{ $i }}">
                                                Week {{ $i }}
                                            </label>
                                        </div>
                                    @endfor
                                </div>

                            </div>
                            <div class="col-md form-group">
                                <small class="fw-bolder">REFERENCE/ BIBLIOGRAHIES</small>
                                <div class="">

                                    @if ($_course_syllabus->details)
                                        @foreach (json_decode($_course_syllabus->details->references) as $key => $item)
                                            <div class="form-check d-block col-md">
                                                <input class="form-check-input" type="checkbox"
                                                    value="{{ $item }}" name="references[]"
                                                    id="reference{{ $key }}">
                                                <label class="form-check-label" for="reference{{ $key }}">
                                                    {{-- {{ substr($item, 0, 20) }} --}}
                                                    {{ $item }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>No Course Details</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md form-group">
                                <small class="fw-bolder">TEACHING AIDS</small>
                                <div class="">
                                    @if ($_course_syllabus->details)
                                        @foreach (json_decode($_course_syllabus->details->teaching_aids) as $key => $item)
                                            <div class="form-check d-block ">
                                                <input class="form-check-input" type="checkbox"
                                                    value="{{ $item }}" name="teaching_aids[]"
                                                    id="teaching-aids-{{ $key }}">
                                                <label class="form-check-label" for="teaching-aids-{{ $key }}">
                                                    {{-- {{ substr($item, 0, 2) }} --}}
                                                    {{ $item }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>No Course Details</p>
                                    @endif

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <button class="btn btn-primary btn-add" data-form="form-learning-outcome">Add Learning
                                    Outcome</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



        @endif
    </div>

@endsection
@section('js')
    <script src="{{ asset('resources/plugin/editor/js/ckeditor.js') }}"></script>
    <script src="{{ asset('resources/plugin/editor/js/sample.js') }}"></script>
    <script>
        initSample();
        let editor = ['course_limitations', 'faculty_requirements',
            'teaching_facilities' /* , 'teaching_aids', 'references' */
        ]
        editor.forEach(element => {
            CKEDITOR.replace(element)
        });
        CKEDITOR.replace('modal-editor')
        // STCW REFERENCE
        $('.add-stcw').click(function(event) {
            Swal.fire({
                title: 'Course Syllabus',
                text: "Do you want to add?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var form = $(this).data('form');
                if (result.isConfirmed) {

                    //console.log(form)
                    document.getElementById(form).submit()
                }
            })
            event.preventDefault();
        })
        // Remove
        $('.btn-remove').click(function(event) {
            Swal.fire({
                title: 'Course Syllabus',
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
        $('.btn-add').click(function(event) {
            var model_title = $(this).data('title');
            var reference = $(this).data('stcw');
            var id = $(this).data('id');
            $('.modal-title').text(model_title)
            $('.stcw_reference').val(reference)
            $('.stcw').val(id)
            event.preventDefault();
        })
        $('.btn-modal-form').click(function(event) {
            Swal.fire({
                title: 'Course Syllabus',
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
        $('.btn-add').click(function(event) {
            Swal.fire({
                title: 'Course Syllabus',
                text: "Do you want to add?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var form = $(this).data('form');
                if (result.isConfirmed) {

                    //console.log(form)
                    document.getElementById(form).submit()
                }
            })
            event.preventDefault();
        })
    </script>
@endsection
