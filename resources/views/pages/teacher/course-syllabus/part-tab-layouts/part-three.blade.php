@section('part-three')
    <div class="course-topic mt-5">
        <label for="" class="text-muted fw-bolder">SUB-TOPIC</label>
        <a href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part3&section=sub-topic' }}"
            class="float-end"><small class="badge bg-info">ADD SUB-TOPIC</small></a>
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
                                            @foreach (json_decode($learning_outcome->teaching_aids) as $item)
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


@if (request()->input('section') && request()->input('section') == 'sub-topic')
    @if ($_course_syllabus->learning_outcomes)
        <div class="topics">
            @if (count($_course_syllabus->learning_outcomes) > 0)
                @foreach ($_course_syllabus->learning_outcomes as $key => $learning_outcome)
                    <div class="topic-details">
                        <div class="row">
                            <div class="col-md-2">
                                <small>Course Outcome</small> <br>
                                <span
                                    class="text-primary h5">{{ substr(strtoupper($learning_outcome->course_outcome->course_outcome), 0, 3) }}</span>
                            </div>
                            <div class="col-md">
                                <small>Topic {{ $key + 1 }}</small> <br>
                                <span
                                    class="text-primary h5 fw-bolder">{{ strtoupper($learning_outcome->learning_outcomes) }}</span>
                            </div>
                        </div>
                        <div class="add-sub-topic">

                            <div class="sub-topic-view">
                                {{-- <label for="" class="fw-bolder h6 text-info">SUB-TOPIC VIEW</label> --}}
                                @if (count($learning_outcome->sub_topics) > 0)
                                    @php
                                        $_learning_outcome_count = 0;
                                    @endphp
                                    @foreach ($learning_outcome->sub_topics as $subTopic => $item)
                                        <div class="sub-topic-details">
                                            <div class="row">
                                                <div class="col-md">
                                                    <div class="form-group">
                                                        <small class="">SUB-TOPIC</small><br>
                                                        <label for="" class="fw-bolder">
                                                            {{ strtoupper($item->sub_topic) }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <form
                                                        action="{{ route('teacher.sub-topic-learning-outcome-store') }}"
                                                        method="post"
                                                        id="form-subTopic-learning-outcome-{{ base64_encode($item->id) }}">
                                                        @csrf
                                                        <input type="hidden" name="sub_topic"
                                                            value="{{ base64_encode($item->id) }}">
                                                        <div class="form-group">
                                                            <small for="" class="form-label">ADD
                                                                LEARNING
                                                                OUTCOMES</small>
                                                            <div class="row">
                                                                <div class="col-md">
                                                                    <input type="text"
                                                                        class="form-control form-control-sm"
                                                                        name="learning_outcome">
                                                                </div>
                                                                <div class="col-md">
                                                                    <button
                                                                        class="btn btn-primary btn-sm btn-stcw">ADD</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="learning-outcomes">
                                                @if (count($item->learning_outcome_list) > 0)
                                                    <div style=" text-indent: 50px;">
                                                        @foreach ($item->learning_outcome_list as $item2)
                                                            <p>{{ $key + 1 . '.' . ($_learning_outcome_count+=1).".".$item2->learning_outcome_content }}
                                                            </p>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="sub-topic-details">
                                        <label for="" class="fw-bolder text-muted">NO SUB-TOPIC</label>
                                    </div>
                                @endif
                            </div>
                            <hr>
                            <form action="{{ route('teacher.sub-topic-store') }}" method="post"
                                id="form-subTopic-{{ base64_encode($learning_outcome->id) }}">
                                @csrf
                                <input type="hidden" name="learning_topic"
                                    value="{{ base64_encode($learning_outcome->id) }}">
                                <div class="form-group">
                                    <small for="" class="form-label fw-bolder text-primary">ADD
                                        SUB-TOPIC</small>
                                    <div class="row">
                                        <div class="col-md">
                                            <input type="text" class="form-control" name="content">
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-primary btn-stcw">ADD</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                @endforeach
            @else
                <div class="topic-details">
                    <label for="" class="fw-bolder text-muted">NO TOPIC</label>
                </div>
                <hr>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-strip">
                <thead class="text-center">
                    <tr>
                        <th>COURSE OUTCOME</th>
                        <th>TOPIC <br> <small>Learning Outcomes</small></th>

                    </tr>
                </thead>
                <tbody>
                    @if (count($_course_syllabus->learning_outcomes) > 0)
                        @foreach ($_course_syllabus->learning_outcomes as $learning_outcome)
                            <tr>
                                <td>{{ substr(strtoupper($learning_outcome->course_outcome->course_outcome), 0, 3) }}
                                </td>
                                <td>{{ $learning_outcome->learning_outcomes }}</td>

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
@endif
