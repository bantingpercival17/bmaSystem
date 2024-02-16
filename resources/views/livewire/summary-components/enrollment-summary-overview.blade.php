<div>
    <p class="display-6 fw-bolder text-primary">Enrollment Overview</p>
    <div class="row">
        @foreach ($courses as $course)
        <div class="col-md">
            <a href="{{route('enrollment.enrolled-student-list') .'?course='.base64_encode($course->id)}}">
                <div class="card">
                    <div class="card-body">
                        @php
                        $_level = [4, 3, 2, 1];
                        $_level = $course->id == 3 ? [11, 12] : $_level;
                        $_course_color = $course->id == 1 ? 'text-primary' : '';
                        $_course_color = $course->id == 2 ? 'text-info' : $_course_color;
                        $_course_color = $course->id == 3 ? 'text-warning' : $_course_color;
                        @endphp
                        <div class="d-flex justify-content-between">
                            <div>
                                <div>
                                    <h2 class="counter fw-bolder text-muted" style="visibility: visible;">
                                        {{ count($course->enrollment_list ) }}
                                    </h2>
                                </div>
                            </div>
                            <div>
                                <span><b class="badge bg-primary">{{ $course->course_code }}</b></span>
                            </div>
                        </div>
                        @foreach ($_level as $item)
                        <div class="d-flex justify-content-between mt-2">
                            <div>
                                <span>
                                    {{ Auth::user()->staff->convert_year_level($item) }}</span>
                            </div>
                            <div>
                                <span class="counter text-muted fw-bolder" style="visibility: visible;">
                                    {{ count($course->enrollment_list_by_year_level($item)->get()) }}

                                </span>
                            </div>
                        </div>
                        @if ($course->id != 3)
                        @foreach (Auth::user()->staff->curriculum_list() as $curriculum)
                        @if ($curriculum->id === 1 && $item === 2)
                        <div class="d-flex justify-content-between mt-2">
                            <div>
                                <span>
                                    {{ Auth::user()->staff->convert_year_level($item) }} SBT
                                    2-1-1</span>
                            </div>
                            <div>
                                <span class="counter text-muted fw-bolder" style="visibility: visible;">
                                    {{ count($course->enrollment_list_by_year_level_with_curriculum([$item, $curriculum->id])->get()) }}</span>
                            </div>
                        </div>
                        @endif

                        @if ($curriculum->id === 7 && $item === 1)
                        <div class="d-flex justify-content-between mt-2">
                            <div>
                                <span>
                                    {{ Auth::user()->staff->convert_year_level($item) }} SBT
                                    3-1</span>
                            </div>
                            <div>
                                <span class="counter text-muted fw-bolder" style="visibility: visible;">
                                    {{ count($course->enrollment_list_by_year_level_with_curriculum([$item, $curriculum->id])->get()) }}</span>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @endif
                        @endforeach
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

</div>