@php
    $pageTitle = 'Teacher Subject Handles';
@endphp
@section('page-title', $pageTitle)

<div class="page-content">
    <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
    <div class="row">
        <div class="col-lg-8 col-md-8">
            <div class="form-search">
                <small class="fw-bolder text-primary">SEARCH TEACHER / INSTRUCTION NAME</small>
                <input type="text" wire:model='teacherListSearch'
                    class="form-control form-control-sm border border-primary"
                    placeholder="Search Pattern: Last Name, First Name">
            </div>
            <div class="content-page mt-5">
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
                                            @foreach ($employee->subject_handles_v2($academic) as $item)
                                                <small class="mt-2 btn-form-grade badge bg-primary col-md-3 m-2">
                                                    {{ $item->curriculum_subjects->subject->subject_code }}

                                                    <br>[ {{ $item->section->section_name }} ]
                                                </small>
                                            @endforeach
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
        <div class="col-lg-4 col-md-4">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('registrar.course-subject-view-v2') }}"
                        class="btn btn-primary btn-sm w-100">SUBJECTS</a>
                </div>
                <div class="col-md-12">
                    <small class="text-primary"><b>DEPARTMENT</b></small>
                    <div class="form-group">
                        <select wire:model="selectedDepartment" class="form-select form-select-sm border border-primary"
                            wire:change="">
                            @foreach ($departmentList as $data)
                                <option value="{{ $data }}">
                                    {{ ucwords($data) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
