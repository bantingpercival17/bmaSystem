@php
    $pageTitle = 'List of Enrolled Student';
@endphp
@section('page-title', $pageTitle)

<div class="page-content">
    <p class="display-6 fw-bolder text-primary">{{ $pageTitle }}</p>
    <div class="row">
        @foreach ($courses as $course)
            <div class="col-md">
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
                                        {{ count($course->enrollment_list) }}</h2>
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
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="filter-section m-0 p-0">
                {{ $showData }}
                <small class="fw-bolder text-info">FILTER DETAILS:</small>
                <div class="row">
                    <div class="col-md-6">
                        <small class="fw-bolder text-muted">COURSE : </small> <br>
                        <label for="" class="fw-bolder text-primary">{{ $selectedCourse }}</label>
                    </div>
                    <div class="col-md-6">
                        <small class="fw-bolder text-muted">YEAR LEVEL : </small> <br>
                        <label for=""
                            class="fw-bolder text-primary">{{ $selectLevel == 'ALL LEVELS' ? $selectLevel : strtoupper(Auth::user()->staff->convert_year_level($selectLevel)) }}</label>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mb-3">
                @if ($searchInput != '')
                    <div>
                        <p for="" class="h5">
                            <small class="text-muted"> Search Result:</small>
                            <span class="fw-bolder h6 text-primary"> {{ strtoupper($searchInput) }}</span>
                        </p>
                    </div>
                    <div>
                        No. Result: <b>{{ count($dataLists) }}</b>
                    </div>
                @else
                    <div>
                        <span class="fw-bolder">
                            RECENT DATA
                        </span>
                    </div>
                    <div>
                        No. Result: <b>{{ count($dataLists) }}</b>
                    </div>
                @endif
            </div>
            <div class="content-data">
                @if (count($dataList) > 0)
                    @foreach ($dataLists as $data)
                        @php
                            if ($_data->student->enrollment_assessment) {
                                $_course_color = $_data->student->enrollment_assessment->course_id == 1 ? 'bg-primary' : '';
                                $_course_color = $_data->student->enrollment_assessment->course_id == 2 ? 'bg-info' : $_course_color;
                                $_course_color = $_data->student->enrollment_assessment->course_id == 3 ? 'bg-warning text-white' : $_course_color;
                            } else {
                                $_course_color = 'text-muted';
                            }
                        @endphp
                        <div class="card mb-2">
                            <div class="row no-gutters">
                                <div class="col-md {{-- ps-0 --}}">
                                    <div class="card-body p-3 me-2">
                                        <div class="float-end">
                                            <small
                                                class="badge bg-primary">{{ $data->student->enrollment_status->created_at->format('F d, Y') }}</small>
                                        </div>
                                        <a
                                            href="{{ route('registrar.student-profile') }}?_student={{ base64_encode($data->student->id) }}">
                                            <label for=""
                                                class="text-muted  fw-bolder h5">{{ $data ? strtoupper($data->student->last_name . ', ' . $data->student->first_name . ' ' . $data->student->middle_name . ' ' . $data->student->extection_name) : 'MIDSHIPMAN NAME' }}</label>
                                            -
                                            <small class="fw-bolder text-muted h5">
                                                {{ $data ? ($data->student->account ? $data->student->account->student_number : 'STUDENT NO.') : 'NEW STUDENT' }}
                                            </small>
                                        </a>

                                        <p class="mb-0">
                                            <small class="fw-bolder badge {{ $_course_color }}">
                                                {{ $data ? ($data->student->enrollment_status ? strtoupper($data->student->enrollment_assessment->year_and_section($data->student->enrollment_assessment)) : 'SECTION') : 'SECTION' }}
                                            </small>
                                        </p>

                                        <div class="mt-5">
                                            <div class="float-end">
                                                <a href="{{ route('registrar.student-information-report') }}?_assessment={{ base64_encode($data->student->enrollment_assessment->id) }}"
                                                    class="badge bg-info text-white">
                                                    PRINT
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-lg-4">
            <div class="row">
                <div class="col-12">
                    <small class="text-primary"><b>SEARCH STUDENT NAME</b></small>
                    <div class="form-group search-input">
                        <input type="search" class="form-control" placeholder="Search Pattern: Lastname, Firstname"
                            wire:model="searchInput" wire:keydown="searchStudents">
                    </div>
                </div>
                <div class="col-12">
                    <small class="text-primary"><b>COURSE</b></small>
                    <div class="form-group search-input">
                        <select wire:model="selectCourse" class="form-select form-control-sm"
                            wire:click="categoryCourse">
                            <option value="ALL COURSE">{{ ucwords('all courses') }}</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ ucwords($course->course_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <small class="text-primary"><b>YEAR LEVEL</b></small>
                    <div class="form-group search-input">
                        <select wire:model="selectLevel" class="form-select form-control-sm">
                            <option value="ALL LEVELS">{{ ucwords('all levels') }}</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level }}">
                                    {{ ucwords(Auth::user()->staff->convert_year_level($level)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
