@section('part-one')
   
    <div class="stcw-reference mt-5">
        <label for="" class="text-muted fw-bolder">STCW REFERENCE</label>
        <a href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part1' }}"
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
        <a href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part1' }}"
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
        <a href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part1' }}"
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
