<div class="card mt-4">
    <div class="card-header m-2 p-2">
        <label for="" class="card-title fw-bolder text-primary">STCW REFERENCE</label>
        <div class="card-tool float-end">
            <a class="badge bg-primary" data-bs-toggle="modal" data-bs-target=".stcw-view-modal" data-bs-toggle="tooltip"
                title="" data-bs-original-title="ADD STCW REFERENCE">
                ADD STCW REFERENCE
            </a>
        </div>
    </div>
    <div class="card-body m-2 p-2">
        <div class="table-responsive">
            <table class="table table-striped dataTable">
                <thead>
                    <th>STCW Table</th>
                    <th>Function</th>
                    <th>Competence</th>
                    <th>Knowledge, Understanding and
                        Proficiency</th>
                </thead>
                <tbody>
                    @if (count($course_syllabus->stcw_reference) > 0)
                        @foreach ($course_syllabus->stcw_reference as $reference)
                            <tr>
                                <td>{{ $reference->stcw_table }}</td>
                                <td colspan="1">
                                    @if (count($reference->function_content) > 0)
                                        @foreach ($reference->function_content as $function)
                                            <p>
                                                @php
                                                    echo $function->function_content;
                                                @endphp
                                            </p>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if (count($reference->function_content) > 0)
                                        @foreach ($reference->function_content as $function)
                                            @if (count($function->competence_content) > 0)
                                                @foreach ($function->competence_content as $competence)
                                                    <p>
                                                        @php
                                                            echo $competence->competence_content;
                                                        @endphp
                                                    </p>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td colspan="3">
                                    @if (count($reference->function_content) > 0)
                                        @foreach ($reference->function_content as $function)
                                            @if (count($function->competence_content) > 0)
                                                @foreach ($function->competence_content as $competence)
                                                    @if (count($competence->kup_content) > 0)
                                                        @foreach ($competence->kup_content as $kup)
                                                            <p>
                                                                @php
                                                                    echo $kup->kup_content;
                                                                @endphp
                                                            </p>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
{{--  COURSE OUTCOME --}}
<div class="card mt-4">
    <div class="card-header m-2 p-2">
        <label for="" class="card-title fw-bolder text-primary">COURSE OUTCOCOME</label>
        <div class="card-tool float-end">
            <a class="badge bg-primary" data-bs-toggle="modal" data-bs-target=".outcome-view-modal"
                data-bs-toggle="tooltip" title="" data-bs-original-title="ADD COURSE OUTCOCOME">
                ADD COURSE OUTCOME
            </a>
        </div>
    </div>
    <div class="card-body m-2 p-2">
        <div class="table-responsive">
            <table class="table table-strip">
                <thead>
                    <tr>
                        <th>PROGRAM OUTCOME</th>
                        <th>COURSE OUTCOME</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($course_syllabus->course_outcome) > 0)
                        @foreach ($course_syllabus->course_outcome as $course_outcome)
                            <tr>
                                <td>{{ $course_outcome->program_outcome }}</td>
                                <td>{{ $course_outcome->course_outcome }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2">NO DATA</td>

                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- COURSE DETAILS --}}
<div class="card mt-4">
    <div class="card-header m-2 p-2">
        <label for="" class="card-title fw-bolder text-primary">COURSE DETAILS</label>
        <div class="card-tool float-end">
            <a class="badge bg-primary" data-bs-toggle="modal" data-bs-target=".details-view-modal"
                data-bs-toggle="tooltip" title="" data-bs-original-title="ADD COURSE OUTCOCOME">
                ADD COURSE DETAILS
            </a>
        </div>
    </div>
    <div class="card-body m-2 p-2">
        <div class="table-responsive">
            <table class="table table-strip">
                <tbody>
                    <tr>
                        <td>COURSE INTAKE LIMITATIONS</td>
                        <td>
                            @if ($course_syllabus->details)
                                @php
                                    echo $course_syllabus->details->course_intake_limitations;
                                @endphp
                            @else
                                NO CONTENT
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>FACULTY REQUIREMENTS</td>
                        <td>
                            @if ($course_syllabus->details)
                                @php
                                    echo $course_syllabus->details->faculty_requirements;
                                @endphp
                            @else
                                NO CONTENT
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>TEACHING FACILITIES & EQUIPMENT</td>
                        <td>
                            @if ($course_syllabus->details)
                                @php
                                    echo $course_syllabus->details->teaching_facilities_and_equipment;
                                @endphp
                            @else
                                NO CONTENT
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>TEACHING AIDS</td>
                        <td>
                            @if ($course_syllabus->details)
                                @if ($course_syllabus->details->teaching_aids && $course_syllabus->details->teaching_aids != 'N/A')
                                    @foreach (json_decode($course_syllabus->details->teaching_aids) as $item)
                                        <p>
                                            {{ trim($item) }}
                                        </p>
                                    @endforeach
                                @else
                                    {{ $course_syllabus->details->teaching_aids }}
                                @endif
                            @else
                                NO CONTENT
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>REFERENCE/S</td>
                        <td>
                            @if ($course_syllabus->details)
                                @if ($course_syllabus->details->references && $course_syllabus->details->references != 'N/A')
                                    @foreach (json_decode($course_syllabus->details->references) as $item)
                                        <p>
                                            {{ trim($item) }}
                                        </p>
                                    @endforeach
                                @else
                                    {{ $course_syllabus->details->references }}
                                @endif
                            @else
                                NO CONTENT
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- Modal --}}
<div class="modal fade stcw-view-modal" id="document-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary fw-bolder" id="exampleModalLabel1">ADD STCW REFERENCE </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="card-body m-2 p-2">
                <form action="{{ route('teacher.store-stcw-reference') }}" id="form-stcw" method="post">
                    @csrf
                    <input type="hidden" name="syllabus" value="{{ $course_syllabus->id }}">
                    <div class="row">
                        <div class="col-md-4">
                            <small class="form-label text-primary fw-bolder">STCW TABLE</small>
                            <label for="" class="form-label text-primary fw-bolder"></label>
                            <input type="text" class="form-control border border-primary" name="stcw_table">
                        </div>
                        <div class="col-md">
                            <small for="" class="form-label text-primary fw-bolder">FUNCTION</small><br>

                            <input type="text" class="form-control border border-primary" name="function">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <small class="form-label text-primary fw-bolder">COMPETENCE</small>
                            <label for="" class="form-label text-primary fw-bolder"></label>
                            <input type="text" class="form-control border border-primary" name="competence">
                        </div>
                        <div class="col-md-12">
                            <small for="" class="form-label text-primary fw-bolder">KUP</small><br>
                            <textarea name="kup" id="stcw-editor" cols="30" rows="5" class="form-control border border-primary"></textarea>
                        </div>
                    </div>
                    <button class=" btn btn-primary float-end mt-4 add-stcw" data-form="form-stcw">ADD</button>

                </form>
            </div>
        </div>
    </div>
</div>
{{-- Modal Course Outcome --}}
<div class="modal fade outcome-view-modal" id="document-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary fw-bolder" id="exampleModalLabel1">ADD COURSE OUTCOME</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="card-body m-2 p-2">
                <form action="{{ route('teacher.course-outcome-store') }}" id="course-outcome-form" method="post"
                    clas>
                    @csrf
                    <input type="hidden" name="_syllabus" value="{{ base64_encode($course_syllabus->id) }}">
                    <div class="row">
                        <div class="col-md-4">
                            <small for="" class="form-label text-primary fw-bolder">PROGRAM
                                OUTCOME</small><br>

                            <input type="text" class="form-control border border-primary" name="_program_outcome">
                        </div>
                        <div class="col-md">
                            <small class="form-label text-primary fw-bolder">COURSE OUTCOME</small>
                            <label for="" class="form-label text-primary fw-bolder"></label>
                            <input type="text" class="form-control border border-primary" name="_course_outcome">
                        </div>

                    </div>
                    <small class="text-warning">If Program Outcome more than one use a comma to seperate</small>

                    <div class="">
                        <button class="btn btn-primary btn-sm mt-3 float-end add-stcw"
                            data-form="course-outcome-form">ADD COURSE
                            OUTCOME</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Modal Course Outcome --}}
<div class="modal fade details-view-modal" id="document-view-modal" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary fw-bolder" id="exampleModalLabel1">ADD COURSE OUTCOME</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="card-body m-2 p-2">
                <form action="{{ route('teacher.course-details-store') }}" id="course-details-form" method="post">
                    @csrf
                    <input type="hidden" name="_syllabus" value="{{ base64_encode($course_syllabus->id) }}">
                    @if ($course_syllabus->details)
                        <input type="hidden" name="_details" value="{{ $course_syllabus->details->id }}">
                    @else
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <small for="" class="form-label text-primary fw-bolder">COURSE INTAKE
                                LIMITATIONS</small><br>
                            <textarea name="course_limitations" id="course_limitations" cols="30" rows="5"
                                class="form-control border border-primary">{{ $course_syllabus->details ? $course_syllabus->details->course_intake_limitations : '' }}</textarea>
                        </div>
                        <div class="col-md-12 mt-5">
                            <small for="" class="form-label text-primary fw-bolder">FACULTY
                                REQUIREMENTS</small><br>
                            <textarea name="faculty_requirements" id="faculty_requirements" cols="30" rows="5"
                                class="form-control border border-primary">{{ $course_syllabus->details ? $course_syllabus->details->faculty_requirements : '' }}</textarea>
                        </div>
                        <div class="col-md-12 mt-5">
                            <small for="" class="form-label text-primary fw-bolder">TEACHING FACILITIES &
                                EQUIPMENT</small><br>
                            <textarea name="teaching_facilities" id="teaching_facilities" cols="30" rows="5"
                                class="form-control border border-primary">{{ $course_syllabus->details ? $course_syllabus->details->teaching_facilities_and_equipment : '' }}</textarea>
                        </div>
                        <div class="col-md-12 mt-5">
                            <small for="" class="form-label text-primary fw-bolder">TEACHING AIDS</small><br>
                            <textarea name="teaching_aids" id="teaching_aids" cols="10" rows="5"
                                class="form-control border border-primary">
            @if ($course_syllabus->details)
            @if ($course_syllabus->details->teaching_aids && $course_syllabus->details->teaching_aids != 'N/A')
@foreach (json_decode($course_syllabus->details->teaching_aids) as $item)
<p>
            {{ trim($item) }}
            </p>
@endforeach
@else
{{ $course_syllabus->details->references }}
@endif
            @endif
            </textarea>
                        </div>
                        <div class="col-md-12 mt-5">
                            <small for="" class="form-label text-primary fw-bolder">REFERENCE/S</small><br>
                            <textarea name="references" id="references" cols="30" rows="5"
                                class="form-control border border-primary">
            @if ($course_syllabus->details)
            @if ($course_syllabus->details->references && $course_syllabus->details->references != 'N/A')
@foreach (json_decode($course_syllabus->details->references) as $item)
<p>
            {{ trim($item) }}
            </p>
@endforeach
@else
{{ $course_syllabus->details->references }}
@endif
            @endif
            </textarea>
                        </div>
                    </div>


                    <div class="">
                        <button class="btn btn-primary btn-sm mt-3 float-end add-stcw"
                            data-form="course-details-form">ADD
                            SYLLABUS DETAILS</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('script')
    <script src="{{ asset('resources/plugin/editor/js/ckeditor.js') }}"></script>
    <script>
        let editor = ['course_limitations', 'faculty_requirements',
            'teaching_facilities' /* , 'teaching_aids', 'references' */
        ]
        editor.forEach(element => {
            CKEDITOR.replace(element)
        });
        CKEDITOR.replace('stcw-editor')
    </script>
@endsection
