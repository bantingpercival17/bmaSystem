@section('part-one')

    <div class="stcw-reference mt-5">
        <label for="" class="text-muted fw-bolder">STCW REFERENCE</label>
        <a href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part1&section=stcw-reference' }}"
            class="float-end"><small class="badge bg-info">ADD STCW REFERENCE</small></a>
        @if ($_course_syllabus->stcw_reference)
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
                        @if ($_course_syllabus->stcw_reference)
                            @foreach ($_course_syllabus->stcw_reference as $reference)
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
        @else
            <label for="" class="fw-bolder">NO CONTENT</label>
        @endif
    </div>
    <div class="course-outcome mt-5">
        <label for="" class="text-muted fw-bolder">COURSE OUTCOME</label>
        <a href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part1&section=course-outcome' }}"
            class="float-end"><small class="badge bg-info">ADD COURSE OUTCOME</small></a>
        @if ($_course_syllabus->course_outcome)
            <div class="table-responsive">
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
                                <td colspan="2">NO DATA</td>

                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @else
            <label for="" class="fw-bolder">NO CONTENT</label>
        @endif
    </div>
    <div class="course-details mt-5">
        <label for="" class="text-muted fw-bolder">COURSE DETAILS</label>
        <a href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part1&section=course-details' }}"
            class="float-end"><small class="badge bg-info">ADD DETAILS</small></a>
        @if ($_course_syllabus->details)
            <div class="table-responsive">
                <table class="table table-strip">
                    <tbody>
                        <tr>
                            <td>COURSE INTAKE LIMITATIONS</td>
                            <td>
                                @if ($_course_syllabus->details)
                                    @php
                                        echo $_course_syllabus->details->course_intake_limitations;
                                    @endphp
                                @else
                                    NO CONTENT
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>FACULTY REQUIREMENTS</td>
                            <td>
                                @if ($_course_syllabus->details)
                                    @php
                                        echo $_course_syllabus->details->faculty_requirements;
                                    @endphp
                                @else
                                    NO CONTENT
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>TEACHING FACILITIES & EQUIPMENT</td>
                            <td>
                                @if ($_course_syllabus->details)
                                    @php
                                        echo $_course_syllabus->details->teaching_facilities_and_equipment;
                                    @endphp
                                @else
                                    NO CONTENT
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>TEACHING AIDS</td>
                            <td>
                                @if ($_course_syllabus->details)
                                    @if ($_course_syllabus->details->teaching_aids && $_course_syllabus->details->teaching_aids != 'N/A')
                                        @foreach (json_decode($_course_syllabus->details->teaching_aids) as $item)
                                            <p>
                                                {{ trim($item) }}
                                            </p>
                                        @endforeach
                                    @else
                                        {{ $_course_syllabus->details->teaching_aids }}
                                    @endif
                                @else
                                    NO CONTENT
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>REFERENCE/S</td>
                            <td>
                                @if ($_course_syllabus->details)
                                    @if ($_course_syllabus->details->references && $_course_syllabus->details->references != 'N/A')
                                        @foreach (json_decode($_course_syllabus->details->references) as $item)
                                            <p>
                                                {{ trim($item) }}
                                            </p>
                                        @endforeach
                                    @else
                                        {{ $_course_syllabus->details->references }}
                                    @endif
                                @else
                                    NO CONTENT
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <label for="" class="fw-bolder">NO CONTENT</label>
        @endif
    </div>
@endsection


@if (request()->input('section') && request()->input('section') == 'stcw-reference')
    <label for="" class="fw-bolder text-primary h6">ADD STCW REFERENCE</label>

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
                                        @php
                                            echo $function->function_content;
                                        @endphp
                                    </label>
                                    <div class="mt-3 row">
                                        <div class="col-md">
                                            <label class="badge bg-primary btn-add" data-bs-toggle="modal"
                                                data-bs-target=".model-add-reference" data-title="ADD COMPETENCE"
                                                data-id="{{ base64_encode($function->id) }}" data-stcw="competence"
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
                                                    @php
                                                        echo $competence->competence_content;
                                                    @endphp

                                                </label>
                                                <div class="mt-3 row">
                                                    <div class="col-md">
                                                        <label class="badge bg-primary btn-add" data-bs-toggle="modal"
                                                            data-bs-target=".model-add-reference" data-title="ADD KUP"
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
                                                                @php
                                                                    echo $kup->kup_content;
                                                                @endphp
                                                            </label>
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
@endif

@if (request()->input('section') && request()->input('section') == 'course-outcome')
    <label for="" class="fw-bolder text-primary h6">ADD COURSE OUTCOME</label>

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
    <form action="{{ route('teacher.course-outcome-store') }}" id="course-outcome-form" method="post" clas>
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
            <button class="btn btn-primary btn-sm mt-3 float-end add-stcw" data-form="course-outcome-form">ADD COURSE
                OUTCOME</button>
        </div>
    </form>
@endif
