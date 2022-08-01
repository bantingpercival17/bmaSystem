@section('part-two')
    <div class="course-topic mt-5">
        <label for="" class="text-muted fw-bolder">COURSE TOPIC</label>
        <a href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part2' }}"
            class="float-end"><small class="badge bg-info">ADD COURSE TOPIC</small></a>
        @if ($_course_syllabus->learning_outcomes)
            <div class="table-responsive">
                <table class="table table-strip">
                    <thead class="text-center">
                        <tr>
                            <th rowspan="2" width="10%">TERM</th>
                            <th rowspan="2" width="10%">WEEK</th>
                            <th rowspan="2">TOPIC</th>
                            <th colspan="2" width="20%">Time allotment (in hours)</th>

                        </tr>
                        <tr>
                            <th>Theoretical</th>
                            <th>Demonstration /Practical Work</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($_course_syllabus->learning_outcomes) > 0)
                            @php
                                $_theoretical = 0;
                                $_demonstration = 0;
                            @endphp
                            @foreach ($_course_syllabus->learning_outcomes as $learning_outcome)
                                @php
                                    $_theoretical += $learning_outcome->theoretical;
                                    $_demonstration += $learning_outcome->demonstration;
                                @endphp
                                <tr>
                                    <td>{{ strtoupper($learning_outcome->term) }}</td>
                                    <td>{{ $learning_outcome->weeks }}</td>
                                    <td>{{ $learning_outcome->learning_outcomes }}</td>
                                    <td>{{ $learning_outcome->theoretical }}</td>
                                    <td>{{ $learning_outcome->demonstration }}</td>
                                </tr>
                            @endforeach


                            <tr>
                                <th colspan="3">SUB-TOTAL (Contact Hours):</th>
                                <th>{{ $_theoretical }}</th>
                                <th>{{ $_demonstration }}</th>
                            </tr>
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
