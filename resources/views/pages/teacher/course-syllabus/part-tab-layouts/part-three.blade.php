@section('part-three')
    <div class="course-topic mt-5">
        <label for="" class="text-muted fw-bolder">COURSE TOPIC</label>
        <a href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part2' }}"
            class="float-end"><small class="badge bg-info">ADD COURSE TOPIC</small></a>
        @if ($_course_syllabus->learning_outcomes)
            <div class="table-responsive">
                <table class="table table-strip">
                    <thead class="text-center">
                        <tr>
                            <th>COURSE<br>OUTCOME</th>
                            <th>TOPIC <br> <small>Learning Outcomes</small></th>
                            <th>
                                REFERENCES / BIBLIOGRAPHIES
                            </th>
                            <th>TEACHING AIDS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($_course_syllabus->learning_outcomes) > 0)
                            @foreach ($_course_syllabus->learning_outcomes as $learning_outcome)
                                <tr>
                                    <td>{{ substr(strtoupper($learning_outcome->course_outcome->course_outcome), 0, 3) }}
                                    </td>
                                    <td>{{ $learning_outcome->learning_outcomes }}</td>
                                    <td>
                                        @if ($learning_outcome->reference && $learning_outcome->reference != 'null')
                                            @foreach (json_decode($learning_outcome->reference) as $item)
                                                {{ substr($item, 0, 3) }}
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if ($learning_outcome->teaching_aids && $learning_outcome->teaching_aids != 'null')
                                            @foreach (json_decode($learning_outcome->teaching_aids) as  $item)
                                                {{ substr($item, 0, 3) }},
                                            @endforeach
                                        @endif
                                    </td>
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
@endsection
