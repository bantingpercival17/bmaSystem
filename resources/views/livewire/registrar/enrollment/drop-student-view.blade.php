@php
    $pageTitle = 'List of Withdrawn & Drop';
@endphp
@section('page-title', $pageTitle)

<div class="page-content">
    <p class="display-6 fw-bolder text-primary">{{ $pageTitle }}</p>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title fw-bolder text-primary">SUMMARY OVERVIEW</h4>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive mt-4">
                <table id="basic-table" class="table table-striped mb-0" role="grid">
                    <thead>
                        <tr class="text-center">
                            <th>COURSE</th>
                            <th>WITHDRAWN</th>
                            <th>DROPPED</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courses as $course)
                            <tr>
                                <td>{{ $course->course_name }}</td>
                                <td>{{ count($course->enrollment_cancellation('withdrawn')->get()) }}</td>
                                <td>{{ count($course->enrollment_cancellation('dropped')->get()) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
                {{ $dataLists }}
                @if (count($dataLists) > 0)
                    @foreach ($dataLists as $data)
                        <div class="card mb-2">
                            <div class="row no-gutters">
                                <div class="col-md-3">
                                    <img src="{{ $data ? $data->profile_pic($data->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                                        class="card-img" alt="#">
                                </div>
                                <div class="col-md">
                                    <div class="card-body p-3 me-2">
                                        <div class="">
                                            <div class="float-end">
                                                <a href="{{ route('registrar.student-information-report') }}?_assessment={{ base64_encode($data->enrollment_assessment->id) }}"
                                                    class="badge bg-info text-white">
                                                    PRINT
                                                </a>
                                            </div>
                                        </div>
                                        <label for=""
                                            class="fw-bolder text-primary h4">{{ $data ? strtoupper($data->last_name . ', ' . $data->first_name) : 'MIDSHIPMAN NAME' }}</label>
                                        <p class="mb-0">
                                            <small class="fw-bolder badge bg-secondary">
                                                {{ $data ? ($data->account ? $data->account->student_number : 'STUDENT NO.') : 'NEW STUDENT' }}
                                            </small> |
                                            <small class="fw-bolder badge bg-secondary">
                                                {{ $data ? ($data->enrollment_status ? strtoupper(Auth::user()->staff->convert_year_level($data->enrollment_status->year_level)) : 'YEAR LEVEL') : 'YEAR LEVEL' }}
                                            </small> |
                                            <small
                                                class="fw-bolder badge {{ $data->enrollment_assessment ? $data->enrollment_assessment->color_course() : 'bg-secondary' }}">
                                                {{ $data ? ($data->enrollment_status ? $data->enrollment_status->course->course_name : 'COURSE') : 'COURSE' }}
                                            </small>
                                        </p>
                                        <div class="row mt-0">

                                            <div class="col-md">
                                                <small class="fw-bolder text-muted">CURRICULUM:</small> <br>
                                                <small class="badge bg-primary">
                                                    {{ $data ? ($data->enrollment_status ? strtoupper($data->enrollment_status->curriculum->curriculum_name) : 'CURRICULUM') : 'CURRICULUM' }}
                                                </small>
                                            </div>
                                            <div class="col-md">
                                                <small class="fw-bolder text-muted">SECTION:</small> <br>
                                                <small class="badge bg-primary">
                                                    {{ $data ? ($data->enrollment_status ? strtoupper($data->enrollment_status->academic->semester . ' | ' . $data->enrollment_status->academic->school_year) : 'SECTION') : 'SECTION' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="form-group col-md">
                                                <small class="fw-bolder text-secondary">TYPE OF
                                                    CANCELLATION</small>
                                                <br>
                                                <label for=""
                                                    class="fw-bolder text-danger">{{ strtoupper($data->enrollment_status->enrollment_cancellation->type_of_cancellations) }}</label>
                                            </div>
                                            <div class="col-md form-group">
                                                <small class="fw-bolder text-secondary">DATE
                                                    CANCELLATION</small>
                                                <br>
                                                <label for=""
                                                    class="fw-bolder text-danger">{{ strtoupper($data->enrollment_status->enrollment_cancellation->date_of_cancellation) }}</label>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card mb-2">
                        <div class="row no-gutters">
                            <div class="col-md-3">
                                <img src="{{ 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                                    class="card-img" alt="#">
                            </div>
                            <div class="col-md">
                                <div class="card-body p-3 me-2">
                                    <label for="" class="fw-bolder text-primary h4">NO DATA</label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-content mb-2">
                <a href="{{ route('enrollment.view-v2') }}{{ request()->input('_academic') ? '?_academic=' . request()->input('_academic') : '' }}"
                    class="badge bg-primary w-100">{{ strtoupper('enrollment assessment') }}</a>
                <a href="{{ route('enrollment.enrolled-student-list') }}{{ request()->input('_academic') ? '?_academic=' . request()->input('_academic') : '' }}"
                    class="badge bg-primary w-100">{{ strtoupper('List of Enrolled Students') }}</a>
            </div>
            <div class="row">
                <div class="col-12">
                    <small class="text-primary"><b>SEARCH STUDENT NAME</b></small>
                    <div class="form-group search-input">
                        <input type="search" class="form-control" placeholder="Search Pattern: Lastname, Firstname"
                            wire:model="searchInput">
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
            <div class="">
                <small class="fw-bolder text-muted">GENERATE OFFICALLY WITHDRAWN & DROP</small>
                <div class="d-flex justify-content-between">
                    {{-- <a href="{{ route('enrollment.enrolled-list-report') }}?_report=excel-report{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}"
                        class="badge bg-primary w-100">Excel</a>
                     --}}<a
                        href="{{ route('enrollment.withdrawn-and-drop-report') }}?{{ request()->input('_academic') ? '_academic=' . request()->input('_academic') : '' }}"
                        class="badge bg-danger w-100">PDF</a>
                </div>
                {{--   <a href="{{ route('enrollment.semestarl-enrollment-list') }}?_report=excel-report{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}"
                    class="badge bg-primary w-100">CHED FORM IN EXCEL</a> --}}
            </div>
        </div>
    </div>
</div>
