<div class="row mt-5">

    <div class="col-lg-8">
        <label for="" class="fw-bolder text-primary h4">LIST OF TOPICS</label>
        @if (count($course_syllabus->learning_outcomes) > 0)

            <div class="learning-outline-content mt-5">
                @foreach ($course_syllabus->learning_outcomes as $key => $learning_outcome)
                    <a
                        href="{{ route('teacher.course-syllabus-topic-view-v2') . '?topic=' . base64_encode($learning_outcome->id) }}">
                        <div class="card">
                            <div class="card-body m-2 p-2">
                                <div class="row">
                                    <div class="col-md">
                                        <small class="fw-bolder">COURSE OUTLINE</small><br>
                                        <label for="" class="text-primary h5 fw-bolder">
                                            {{ $learning_outcome->course_outcome->program_outcome }}
                                        </label>
                                    </div>
                                    <div class="col-md">
                                        <small class="fw-bolder">TERM</small><br>
                                        <label for=""
                                            class="text-primary h5">{{ strtoupper($learning_outcome->term) }}</label>
                                    </div>
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
                                </div>
                                <div class="col-md-12">
                                    <small class="fw-bolder">TOPIC {{ $key + 1 }}</small><br>
                                    <label for=""
                                        class="text-primary fw-bolder h5">{{ strtoupper($learning_outcome->learning_outcomes) }}</label>
                                </div>
                                <div class="row">
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
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p>No Topics</p>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header m-2 p-2">
                <label for="" class="fw-bolder text-primary h6">CREATE COURSE TOPIC</label>
            </div>
            <div class="card-body m-2 p-2 model-add-topic">
                <form action="{{ route('teacher.syllabus-learning-outcome') }}" method="post"
                    id="form-learning-outcome">
                    @csrf
                    <input type="hidden" name="_syllabus" value="{{ base64_encode($course_syllabus->id) }}">
                    <div class="form-group">
                        <small class="fw-bolder">COURSE OUTCOME</small>
                        <select name="_course_outcome" id=""
                            class="form-select form-select-sm border border-primary">
                            @foreach ($course_syllabus->course_outcome as $co)
                                <option value="{{ $co->id }}">{{ $co->course_outcome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <small class="fw-bolder">TERM</small>
                        <select name="_term" id="" class="form-select form-select-sm border border-primary">
                            <option value="midterm">MIDTERM</option>
                            <option value="finals">FINALS</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <small class="fw-bolder">COURSE TOPIC</small>
                        <input type="text" class="form-control form-control-sm border border-primary"
                            name="_learning_outcomes">
                    </div>
                    <div class="form-group">
                        <small class="fw-bolder">WEEK/S</small>
                        <select name="weeks[]"
                            class="form-select form-select-sm border border-primary form-multiple-select"
                            style="width: 100%" multiple="multiple">
                            @for ($i = 1; $i <= 18; $i++)
                                <option value="week-{{ $i }}">Week {{ $i }}</option>
                            @endfor
                        </select>

                    </div>
                    <div class="form-group">
                        <small class="fw-bolder">THEORETICAL</small>
                        <input type="number" class="form-control form-control-sm border border-primary"
                            name="_theoretical">
                    </div>
                    <div class="form-group">
                        <small class="fw-bolder">DEMONSTRATION / PRACTICAL WORK</small>
                        <input type="number" class="form-control form-control-sm border border-primary"
                            name="_demonstration">
                    </div>
                    <div class="form-group">
                        <small class="fw-bolder">REFERENCE/ BIBLIOGRAHIES</small>
                        <select name="references[]" id=""
                            class="form-select form-select-sm border border-primary form-multiple-select"
                            style="width: 100%" multiple="multiple">
                            @if ($course_syllabus->details)

                                @foreach (json_decode($course_syllabus->details->references) as $key => $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>NO CONTENT</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <small class="fw-bolder">TEACHING AIDS</small>
                        <select name="teaching_aids[]" id=""
                            class="form-select form-select-sm border border-primary form-multiple-select"
                            style="width: 100%" multiple="multiple">
                            @if ($course_syllabus->details)

                                @foreach (json_decode($course_syllabus->details->teaching_aids) as $key => $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>NO CONTENT</option>
                            @endif
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">SAVE</button>
                </form>
            </div>
        </div>

    </div>
</div>
@section('script')
    <script>
        $(document).ready(function() {
            $('.form-multiple-select').select2({
                dropdownParent: $('.model-add-topic')
            });
            console.log('subject-topics')
        });
    </script>
@endsection
