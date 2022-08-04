@section('part-two')
    <div class="course-topic mt-5">
        <label for="" class="text-muted fw-bolder">COURSE TOPIC</label>
        <a href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part2&section=course-topic' }}"
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


@if (request()->input('section') && request()->input('section') == 'course-topic')
    <label for="" class="fw-bolder text-primary h6">COURSE TOPIC</label>
    <label class="badge bg-primary btn-add float-end" data-bs-toggle="modal" data-bs-target=".model-add-topic"
        data-title="CREATE COURSE TOPIC">
        ADD COURSE TOPIC
    </label>
    <div class="learning-outcome">
        {{-- <form action="{{ route('teacher.syllabus-learning-outcome') }}" method="post" id="form-learning-outcome">
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
                <div class="col-md-3 form-group">
                    <small class="fw-bolder">TERM</small>
                    <select name="_term" id="" class="form-select">
                        <option value="midterm">MIDTERM</option>
                        <option value="finals">FINALS</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md form-group">
                    <small class="fw-bolder">COURSE TOPIC</small>
                    <input type="text" class="form-control" name="_learning_outcomes">
                </div>

            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <small class="fw-bolder">WEEK/S</small>
                    <select name="weeks[]" id="" class="form-select form-multiple-select"
                        placeholder="Select Teaching Aids" multiple="multiple">
                        @for ($i = 1; $i <= 18; $i++)
                            <option value="week-{{ $i }}">Week {{ $i }}</option>
                        @endfor
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
                    <small class="fw-bolder">REFERENCE/ BIBLIOGRAHIES</small>
                    <select name="references[]" id="" class="form-select form-multiple-select"
                        placeholder="Select Teaching Aids" multiple="multiple">
                        @if ($_course_syllabus->details)

                            @foreach (json_decode($_course_syllabus->details->references) as $key => $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>NO CONTENT</option>
                        @endif
                    </select>
                </div>
                <div class="col-md form-group">
                    <small class="fw-bolder">TEACHING AIDS</small>
                    <select name="teaching_aids[]" id="" class="form-select form-multiple-select"
                        placeholder="Select Teaching Aids" multiple="multiple">
                        @if ($_course_syllabus->details)

                            @foreach (json_decode($_course_syllabus->details->teaching_aids) as $key => $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>NO CONTENT</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <button class="btn btn-primary add-stcw" data-form="form-learning-outcome">Add Learning
                        Outcome</button>
                </div>
            </div>
        </form> --}}
        @if (count($_course_syllabus->learning_outcomes) > 0)
            <label for="" class="fw-bolder text-primary h6">CREATE COURSE TOPIC</label>

            <div class="learning-outline-content mt-5">
                @foreach ($_course_syllabus->learning_outcomes as $key => $learning_outcome)
                    <div class="lo-{{ $learning_outcome->id }} mt-4">
                        {{-- <a href="" class="badge bg-primary fw-bolder"><small>PREVIEW</small></a> --}}
                        <div class="row">
                            <div class="col-md">
                                <small for="" class="text-danger btn-remove fw-bolder float-end"
                                    data-url="{{ route('teacher.syllabus-learning-outcome-remove') . '?learning_outcome=' . base64_encode($learning_outcome->id) }}">REMOVE</small>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="fw-bolder">COURSE TOPIC {{ $key + 1 }}</small><br>
                                <label for=""
                                    class="text-primary fw-bolder h5">{{ strtoupper($learning_outcome->learning_outcomes) }}</label>
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
                                    @if ($learning_outcome->reference && $learning_outcome->reference != 'null')
                                        @foreach (json_decode($learning_outcome->reference) as $item)
                                            {{ substr($item, 0, 2) }}
                                        @endforeach
                                    @endif

                                </label>
                            </div>
                            <div class="col-md">
                                <small class="fw-bolder">TEACHING AIDS</small><br>
                                <label for="" class="text-primary h5">

                                    @if ($learning_outcome->teaching_aids && $learning_outcome->teaching_aids != 'null')
                                        @foreach (json_decode($learning_outcome->teaching_aids) as $item)
                                            {{ substr($item, 0, 2) }},
                                        @endforeach
                                    @endif

                                </label>
                            </div>
                        </div>


                        {{-- <div class="topic-materials mt-3">
                            <label for="" class="fw-bolder text-muted">TEACHING MATERIAL</label>
                            @if ($learning_outcome->materials)
                                <div class="row">
                                    <div class="col-md">
                                        <small class="fw-bolder">PRESENTATION</small> <br>

                                        <a href="{{ $learning_outcome->materials->presentation_link }}"
                                            target="_blank">POWERPOINT LINK</a>

                                    </div>
                                    <div class="col-md">
                                        <small class="fw-bolder">YOUTUBE LINK</small>
                                        <p>
                                            {{ $learning_outcome->materials->youtube_link }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('teacher.topic-materials') }}" class="form-group" method="post"
                                    id="form-topic-{{ base64_encode($learning_outcome->id) }}">
                                    @csrf
                                    <input type="hidden" name="learning_topic"
                                        value="{{ base64_encode($learning_outcome->id) }}">
                                    <div class="row">
                                        <div class="col-md">
                                            <small class="fw-bolder">PRESENTATION</small>
                                            <input type="text" class="form-control" name="presentation_link">
                                            <small class="text-warning">Note: You can Paste the link of
                                                powerpoint</small>
                                        </div>
                                        <div class="col-md">
                                            <small class="fw-bolder">YOUTUBE LINK</small>
                                            <input type="text" class="form-control" name="youtube_link">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-form-add"
                                            data-form="form-topic-{{ base64_encode($learning_outcome->id) }}">SUBMIT</button>
                                    </div>

                                </form>
                            @endif


                        </div> --}}

                    </div>

                    <div class="learning-outcome-topics mt-3">
                        <label for="" class="fw-bolder text-muted">SUB TOPIC WITH LEARNING
                            OUTCOMES</label>
                        <form action="" class="form-group" method="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="fw-bolder">SUB TOPIC</small>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <small class="fw-bolder">LEARNING OUTCOMES</small>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr>
                @endforeach
            </div>
        @else
            <p>ADD LEARNING OUTCOME</p>
        @endif
    </div>
    {{-- Add Modal & Update --}}
    <div class="modal fade model-add-topic" tabindex="-1" role="dialog" aria-labelledby="model-add-topicTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title modal-title" id="model-add-topicTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
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
                            <div class="col-md-3 form-group">
                                <small class="fw-bolder">TERM</small>
                                <select name="_term" id="" class="form-select">
                                    <option value="midterm">MIDTERM</option>
                                    <option value="finals">FINALS</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md form-group">
                                <small class="fw-bolder">COURSE TOPIC</small>
                                <input type="text" class="form-control" name="_learning_outcomes">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <small class="fw-bolder">WEEK/S</small>
                                <select name="weeks[]" class="form-select form-multiple-select" style="width: 100%"
                                    multiple="multiple">
                                    @for ($i = 1; $i <= 18; $i++)
                                        <option value="week-{{ $i }}">Week {{ $i }}</option>
                                    @endfor
                                </select>

                            </div>
                            <div class="col-md form-group">
                                <small class="fw-bolder">THEORETICAL</small>
                                <input type="number" class="form-control" name="_theoretical">
                            </div>
                            <div class="col-md-5 form-group">
                                <small class="fw-bolder">DEMONSTRATION / PRACTICAL WORK</small>
                                <input type="number" class="form-control" name="_demonstration">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md form-group">
                                <small class="fw-bolder">REFERENCE/ BIBLIOGRAHIES</small>
                                <select name="references[]" id="" class="form-select form-multiple-select"
                                    style="width: 100%" multiple="multiple">
                                    @if ($_course_syllabus->details)

                                        @foreach (json_decode($_course_syllabus->details->references) as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>NO CONTENT</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md form-group">
                                <small class="fw-bolder">TEACHING AIDS</small>
                                <select name="teaching_aids[]" id=""
                                    class="form-select form-multiple-select" style="width: 100%" multiple="multiple">
                                    @if ($_course_syllabus->details)

                                        @foreach (json_decode($_course_syllabus->details->teaching_aids) as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>NO CONTENT</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md">
                                <button class="btn btn-primary add-stcw" data-form="form-learning-outcome">Add
                                    Learning
                                    Outcome</button>
                            </div>
                        </div> --}}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm btn-modal-form"
                        data-form="form-learning-outcome">SAVE
                        CONTENT</button>
                </div>
            </div>
        </div>
    </div>
@endif
