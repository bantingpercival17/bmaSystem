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
        </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="filter-section m-0 p-0">
                {{ $showData }}
                <small class="fw-bolder text-info">FILTER DETAILS: {{ $academic }}</small>
                <div class="row">
                    <div class="col-md-6">
                        <small class="fw-bolder text-muted">COURSE : </small> <br>
                        <label for="" class="fw-bolder text-primary">{{ $selectedCourse }}</label>
                    </div>
                    <div class="col-md-6">
                        <small class="fw-bolder text-muted">YEAR LEVEL : </small> <br>
                        <label for="" class="fw-bolder text-primary">{{ $selectLevel == 'ALL LEVELS' ? $selectLevel : strtoupper(Auth::user()->staff->convert_year_level($selectLevel)) }}</label>
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
                <div class="card mb-2 shadow">
                    <div class="row no-gutters">
                        <div class="col-md-3">
                            <img src="{{ $data ? $data->profile_pic($data->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}" class="card-img" alt="#">
                        </div>
                        <div class="col-md">
                            <div class="card-body p-3 me-2">
                                <div class="">
                                    <div class="float-end">
                                        <a href="{{ route('registrar.student-information-report') }}?_assessment={{ base64_encode($data->enrollment_assessment->id) }}" class="badge bg-info text-white">
                                            PRINT
                                        </a>
                                    </div>
                                </div>
                                <label for="" class="fw-bolder text-primary h4">{{ $data ? strtoupper($data->last_name . ', ' . $data->first_name) : 'MIDSHIPMAN NAME' }}</label>
                                <p class="mb-0">
                                    <small class="fw-bolder badge bg-secondary">
                                        {{ $data ? ($data->account ? $data->account->student_number : 'STUDENT NO.') : 'NEW STUDENT' }}
                                    </small> |
                                    <small class="fw-bolder badge bg-secondary">
                                        {{ $data ? ($data->enrollment_status ? strtoupper(Auth::user()->staff->convert_year_level($data->enrollment_status->year_level)) : 'YEAR LEVEL') : 'YEAR LEVEL' }}
                                    </small> |
                                    <small class="fw-bolder badge {{ $data->enrollment_assessment ? $data->enrollment_assessment->color_course() : 'bg-secondary' }}">
                                        {{ $data ? ($data->enrollment_status ? $data->enrollment_status->course->course_name : 'COURSE') : 'COURSE' }}
                                    </small>
                                </p>
                                <div class="row mt-0">

                                    <div class="col-md">
                                        <small class="badge bg-primary">
                                            {{ $data ? ($data->enrollment_status ? strtoupper($data->enrollment_status->curriculum->curriculum_name) : 'CURRICULUM') : 'CURRICULUM' }}
                                        </small>
                                    </div>
                                    <div class="col-md">
                                        <small class="badge bg-primary">
                                            {{ $data ? ($data->enrollment_status ? strtoupper($data->enrollment_status->academic->semester . ' | ' . $data->enrollment_status->academic->school_year) : 'SECTION') : 'SECTION' }}
                                        </small>
                                    </div>
                                </div>
                                {{-- @if ($isOpen === $data->enrollment_status->id)
                                            @if ($isLoading)
                                                <span class="fw-bolder bg-secondary badge">LOADING....</span>
                                            @else
                                                <button class="btn btn-danger btn-sm  float-end mt-2">
                                                  hide
                                                </button>
                                            @endif
                                        @else
                                            <small class="btn btn-outline-danger btn-sm  float-end mt-2"
                                                wire:click="enrollment_cancellation('{{ $data->enrollment_status->id }}')">
                                ENROLLMENT CANCELLATION
                                </small>
                                @endif --}}

                                @if ($isOpen == $data->enrollment_status->id)
                                <div class="form-enrollment-cancellation mt-3">
                                    {{-- <form> --}}
                                    <input type="hidden" name="enrollmentID" value="{{ $enrollmentData ? $enrollmentData->id : '' }}">
                                    <div class="form-group">
                                        <small class="fw-bolder text-primary">TYPE OF
                                            CANCELLATION</small>
                                        <select wire:model="enrollmentType" class="form-select form-select-sm border border-primary">
                                            <option value="dropped">Dropping Form</option>
                                            <option value="withdrawn">Withdrawal Form</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <small class="fw-bolder text-primary">DATE OF
                                            CANCELLATION</small>
                                        <input type="date" wire:model="enrollmentDate" class="form-control form-control-sm border border-primary">
                                    </div>
                                    <button wire:click="enrollmentCancellationStore" class="btn btn-primary btn-sm float-end">SUBMIT</button>
                                    {{-- </form> --}}
                                </div>
                                @else
                                @if ($data->enrollment_status->enrollment_cancellation)
                                <div class="row">

                                    <div class="form-group col-md">
                                        <small class="fw-bolder text-secondary">TYPE OF
                                            CANCELLATION</small>
                                        <br>
                                        <label for="" class="fw-bolder text-danger">{{ strtoupper($data->enrollment_status->enrollment_cancellation->type_of_cancellations) }}</label>
                                    </div>
                                    <div class="col-md form-group">
                                        <small class="fw-bolder text-secondary">DATE
                                            CANCELLATION</small>
                                        <br>
                                        <label for="" class="fw-bolder text-danger">{{ strtoupper($data->enrollment_status->enrollment_cancellation->date_of_cancellation) }}</label>

                                    </div>
                                </div>
                                @else
                                <small class="btn btn-outline-danger btn-sm  float-end mt-2" wire:click="enrollment_cancellation('{{ $data->enrollment_status->id }}')">
                                    ENROLLMENT CANCELLATION
                                </small>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-content mb-2">
                <a href="{{ route('enrollment.view-v2') }}{{ request()->input('_academic') ? '?_academic=' . request()->input('_academic') : '' }}" class="badge bg-primary w-100">{{ strtoupper('enrollment assessment') }}</a>
                <a href="{{ route('enrollment.withdrawn-list') }}{{ request()->input('_academic') ? '?_academic=' . request()->input('_academic') : '' }}" class="badge bg-primary w-100">{{ strtoupper('List of Withdrawn & Dropped') }}</a>
            </div>
            <div class="row">
                <div class="col-12">
                    <small class="text-primary"><b>SEARCH STUDENT NAME</b></small>
                    <div class="form-group search-input">
                        <input type="search" class="form-control" placeholder="Search Pattern: Lastname, Firstname" wire:model="searchInput">
                    </div>
                </div>
                <div class="col-12">
                    <small class="text-primary"><b>COURSE</b></small>
                    <div class="form-group search-input">
                        <select wire:model="selectCourse" class="form-select form-control-sm" wire:click="categoryCourse">
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
                <small class="fw-bolder text-muted">EXPORT OFFICALLY ENROLLED</small>
                <div class="<!-- d-flex justify-content-between -->">

                </div>
                <a href="{{ route('enrollment.enrolled-list-report') }}?_report=excel-report-2&cancellation=0{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}" class="badge bg-primary w-100">TOTAL ENROLLED</a> <br>
                <a href="{{ route('enrollment.enrolled-list-report') }}?_report=excel-report-2&cancellation=1{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}" class="badge bg-primary w-100">TOTAL REMAINING ENROLLED</a> <br>
                <a href="{{ route('enrollment.enrolled-list-report') }}?_report=pdf-report{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}" class="badge bg-danger w-100">PDF</a>
                <a href="{{ route('enrollment.semestarl-enrollment-list') }}?_report=excel-report{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}" class="badge bg-primary w-100">CHED FORM IN EXCEL</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade view-modal " tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder text-primary">ENROLLMENT CANCELLATION</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div {{-- wire:submit.prevent="enrollmentCancellationStore" --}}>
                    <input type="hidden" name="enrollmentID" value="{{ $enrollmentData ? $enrollmentData->id : '' }}">
                    <div class="form-group">
                        <small class="fw-bolder text-primary">TYPE OF CANCELLATION</small>
                        <select wire:model="enrollmentType" class="form-select form-select-sm border border-primary">
                            <option value="dropped">Dropping Form</option>
                            <option value="withdrawn">Withdrawal Form</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <small class="fw-bolder text-primary">DATE OF CANCELLATION</small>
                        <input type="date" wire:model="enrollmentDate" class="form-control form-control-sm border border-primary">
                    </div>
                    <button wire:click="enrollmentCancellationStore" class="btn btn-primary btn-sm float-end">SUBMIT</button>
                </div>
            </div>
        </div>
    </div>
</div>