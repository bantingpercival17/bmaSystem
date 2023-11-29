@php
    $pageTitle = 'Grade Submission';
@endphp
@section('page-title', $pageTitle)

@if (!$viewPage)
    <div class="row">
        <div class="col-lg-8">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
            {{-- {{ $academic }} --}}
            <div class="form-search">
                <small class="fw-bolder text-primary">SEARCH TEACHER / INSTRUCTION NAME</small>
                <input type="text" wire:model='teacherListSearch'
                    class="form-control form-control-sm border border-primary"
                    placeholder="Search Pattern: Last Name, First Name">
            </div>
            <div class="data-content mt-4">
                @forelse ($teacherLists as $employee)
                    <a
                        href="{{ route('department-head.grade-submission-v2') . '?staff=' . base64_encode($employee->id) . (request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '') }}">
                        <div class="card mb-2 shadow mb-3">
                            <div class="row no-gutters">
                                <div class="col-md-3">
                                    <img src="{{ $employee ? asset($employee->profile_picture()) : asset('/assets/img/staff/avatar.png') }}"
                                        class="card-img" alt="#">
                                </div>
                                <div class="col-md ps-0">
                                    <div class="card-body p-3 me-2">
                                        <label for=""
                                            class="fw-bolder text-primary h4">{{ $employee ? strtoupper($employee->last_name . ', ' . $employee->first_name) : 'EMPLOYEE NAME' }}</label>
                                        <div class="row">
                                            <div class="col-md">
                                                <small class="fw-bolder text-info">MIDTERM GRADE SUBMISSION: </small>
                                                <br>
                                                <span class="fw-bolder">
                                                    @php
                                                        $_counts = 0;
                                                        foreach ($employee->grade_submission_v2($academic, 'midterm') as $_count) {
                                                            if (!empty($_count->midterm_grade_submission)) {
                                                                $_counts += 1;
                                                            }
                                                        }
                                                    @endphp
                                                    <span class="text-info fw-bolder">{{ $_counts }}</span><small>
                                                        out of</small>
                                                    <span class="text-primary fw-bolder">
                                                        {{ count($employee->subject_handles_v2($academic)) }}
                                                    </span>
                                                    <small>Submitted Grade</small>
                                                </span>
                                            </div>
                                            <div class="col-md">
                                                <small class="fw-bolder text-primary">FINAL GRADE SUBMISSION: </small>
                                                <br>
                                                <span class="fw-bolder">
                                                    @php
                                                        $_finals_counts = 0;
                                                        foreach ($employee->grade_submission_v2($academic, 'midterm') as $_finals) {
                                                            if (!empty($_finals->finals_grade_submission)) {
                                                                $_finals_counts += 1;
                                                            }
                                                        }
                                                    @endphp
                                                    <span class="text-info fw-bolder">{{ $_finals_counts }}</span>
                                                    <small> out of</small>
                                                    <span class="text-primary fw-bolder">
                                                        {{ count($employee->subject_handles_v2($academic)) }}
                                                    </span>
                                                    <small>Submitted Grade</small>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                @empty
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="mt-2">
                                        <h2 class="counter" style="visibility: visible;">
                                            NO DATA
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="col-lg-4">
            <p class="h4 text-info fw-bolder">FILTER SELECTION</p>

            <div class="col-12">
                <small class="text-primary"><b>STATUS</b></small>
                <div class="form-group search-input">
                    <select class="form-select form-select-sm border border-primary" wire:model="selectCategories">
                        @foreach ($filterContent as $item)
                            <option value="{{ $item }}">{{ ucwords(str_replace('_', ' ', $item)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12">
                <small class="text-primary"><b>TERM</b></small>
                <div class="form-group search-input">
                    <select class="form-select form-select-sm border border-primary" wire:model="selectPeriod">
                        <option value="midterm">MIDTERM</option>
                        <option value="finals">FINALS</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-lg-8">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <img src="{{ $staffView ? asset($staffView->profile_picture()) : asset('/assets/img/staff/avatar.png') }}"
                            class="card-img" alt="#">
                    </div>
                    <div class="col-md ps-0">
                        <div class="card-body p-3 me-2">
                            <label for=""
                                class="fw-bolder text-primary h4">{{ $staffView ? strtoupper($staffView->last_name . ', ' . $staffView->first_name) : 'EMPLOYEE NAME' }}</label>
                            <p class="mb-0">
                                <small class="fw-bolder badge bg-secondary">
                                    {{ $staffView ? $staffView->department . ' DEPARTMENT' : 'DEPARTMENT' }}
                                </small> -
                                <small class="badge bg-secondary">
                                    {{ $staffView ? $staffView->user->email : 'EMAIL' }}
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @if ($staffView)
                <nav class="nav nav-underline bg-soft-primary pb-0 text-center" aria-label="Secondary navigation">
                    <div class="d-flex" id="head-check">
                        <a class="nav-link {{ $activeCard == 'overview' || !$activeCard ? 'active' : 'text-muted' }}"
                            wire:click="switchCard('overview')">CLASS HANDLED</a>
                        <a class="nav-link  {{ $activeCard == 'subject' ? 'active' : 'text-muted' }}"
                            wire:click="switchCard('subject')">SUBJECT VIEW</a>
                    </div>
                </nav>

                <div class="mt-4">
                    @if ($activeCard == 'overview')
                        @include('livewire.department-head.grade-submission.component.teacher-class-handled')
                    @elseif ($activeCard == 'subject')
                        @include('livewire.department-head.grade-submission.component.subject-class')
                    @endif
                </div>
            @endif
        </div>
        <div class="col-lg-4">
            <div class="form-search">
                <small class="fw-bolder text-primary">SEARCH TEACHER / INSTRUCTION NAME</small>
                <input type="text" wire:model='teacherListSearch'
                    class="form-control form-control-sm border border-primary"
                    placeholder="Search Pattern: Last Name, First Name">

                <a class="badge bg-primary float-end mt-3 mb-3" wire:click="filterDialog">{{ $filterButton }}</a>
            </div>
            @if ($filterBox)
                <div class="filter-section mt-2">

                    <p class="h6 text-info fw-bolder">FILTER SELECTION</p>
                    <div>
                        <small class="text-primary"><b>STATUS</b></small>
                        <div class="form-group search-input">
                            <select class="form-select form-select-sm border border-primary"
                                wire:model="selectCategories">
                                @foreach ($filterContent as $item)
                                    <option value="{{ $item }}">{{ ucwords(str_replace('_', ' ', $item)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <small class="text-primary"><b>TERM</b></small>
                        <div class="form-group search-input">
                            <select class="form-select form-select-sm border border-primary" wire:model="selectPeriod">
                                <option value="midterm">MIDTERM</option>
                                <option value="finals">FINALS</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endif
            <br>
            <div class="data-content mt-5">
                @forelse ($teacherLists as $item)
                    <a
                        href="{{ route('department-head.grade-submission-v2') . '?staff=' . base64_encode($item->id) . ($academic ? '&_academic=' . $academic : '') }}">
                        <div class="card mb-2 shadow mb-3">
                            <div class="row no-gutters">
                                <div class="col-md-4 text-center">
                                    <img src="{{ $item ? $item->profile_picture() : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                                        class="avatar-100 rounded card-img" alt="student-image">
                                </div>
                                <div class="col-md-8">
                                    <div class="">
                                        <div class="card-body">
                                            <small
                                                class="text-primary fw-bolder">{{ strtoupper($item->last_name . ', ' . $item->first_name) }}</small>
                                            <br>
                                            <span class="badge bg-secondary">{{ $item->department }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                @empty
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="mt-2">
                                        <h2 class="counter" style="visibility: visible;">
                                            NO DATA
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endif
