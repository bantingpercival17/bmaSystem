@php
    $pageTitle = 'Grade Verification';
@endphp
@section('page-title', $pageTitle)

@if ($pageView)
    <div class="row">
        <div class="col-lg-8">
            <p class="display-6 fw-bolder text-primary">
                {{ $sectionDetails ? strtoupper($sectionDetails->section_name) : 'none' }}</p>

            <div class="form-search">
                <label class="fw-bolder text-primary">SUBJECT LIST</label>
                <div class="float-end">
                    <a href="{{ route('dean.export-form-ad01') . '/' . base64_encode($sectionDetails->id) }}"
                        class="btn btn-outline-info fw-bolder btn-sm">EXPORT AD-01</a>
                </div>
            </div>
            <div class="data-content mt-4">
                @forelse ($sectionDetails->subject_class as $subjectClass)
                    <div class="card mb-2 shadow mb-3">
                        <div class="card-body p-3 me-2">
                            <label for=""
                                class="fw-bolder text-primary h4">{{ $subjectClass->curriculum_subject->subject->subject_code }}</label>
                            <small class="text-muted"> -
                                {{ strtoupper($subjectClass->staff->first_name . ' ' . $subjectClass->staff->last_name) }}</small>
                            <p class="text-muted fw-bolder mb-0">
                                <small>{{ $subjectClass->curriculum_subject->subject->subject_name }}</small>
                            </p>
                            <div class="d-flex justify-content-between">
                                <div>
                                    @if ($subjectClass->midterm_grade_submission)
                                        @if ($subjectClass->midterm_grade_submission->is_approved === 1)
                                            {{-- <a class="badge badge-outline bg-primary"
                                                wire:click="showDocuments('{{ $subjectClass->id }}','midterm','ad1')">MIDTERM
                                                GRADE</a> --}}
                                            <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-2"
                                                wire:click="showDocuments('{{ $subjectClass->id }}','midterm','ad1')">MIDTERM
                                                GRADE</button>
                                        @else
                                            <span class="badge bg-secondary fw-bolder">
                                                MIDTERM GRADE <br> ONGOING CHECKING
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary fw-bolder">
                                            MIDTERM GRADE <br> NOT YET SUBMMITED
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
        <div class="col-lg-4">
            <p class="h4 text-info fw-bolder">FILTER SELECTION</p>
            <div class="col-12">
                <small class="text-primary"><b>COURSE</b></small>
                <div class="form-group search-input">
                    <select wire:model="selectCourse" class="form-select form-select-sm border border-primary">
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
                    <select wire:model="selectLevel" class="form-select form-select-sm border border-primary">
                        <option value="ALL LEVELS">{{ ucwords('all levels') }}</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level }}">
                                {{ ucwords(Auth::user()->staff->convert_year_level($level)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12">
                <small class="text-primary"><b>RECENT SECTION</b></small>
                <div class="form-group search-input">

                </div>
            </div>
        </div>
    </div>
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="modal fade show"
                style="display: block;
    background-color: rgb(0 0 0 / 77%);
    width: 100%;
    height: 100%;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header p-2">
                            <h5 class="text-primary fw-bolder">GRADE PREVIEW</h5>
                            <button type="button" class="btn btn-close" wire:click="hideDocuments">
                            </button>
                        </div>
                        <div class="modal-body p-0">
                            <iframe class="form-view iframe-placeholder" src="{{ $documentLink }}" width="100%"
                                height="600px">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="row">
        <div class="col-lg-8">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>

            <div class="form-search">
                <small class="fw-bolder text-primary">SECTION LIST</small>
            </div>
            <div class="data-content mt-4">
                @forelse ($sectionList as $section)
                    <a
                        href="{{ route('dean.grade-submission-v2') . '?section=' . base64_encode($section->id) }}{{ $academic ? '&_academic=' . $academic : '' }}">
                        <div class="card mb-2 shadow mb-3">
                            <div class="card-body p-3 me-2">
                                <label for=""
                                    class="fw-bolder text-primary h4">{{ $section->section_name }}</label>
                                <div class="row">
                                    <div class="col-md">
                                        <small for="" class="fw-bolder text-muted">NUMBER OF STUDENT</small>
                                        <br>
                                        <label for=""
                                            class="text-primary fw-bolder h4">{{ count($section->student_sections) }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                @endforelse
            </div>
        </div>
        <div class="col-lg-4">
            <p class="h4 text-info fw-bolder">FILTER SELECTION</p>
            <div class="col-12">
                <small class="text-primary"><b>COURSE</b></small>
                <div class="form-group search-input">
                    <select wire:model="selectCourse" class="form-select form-select-sm border border-primary">
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
                    <select wire:model="selectLevel" class="form-select form-select-sm border border-primary">
                        <option value="ALL LEVELS">{{ ucwords('all levels') }}</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level }}">
                                {{ ucwords(Auth::user()->staff->convert_year_level($level)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <small class="text-primary"><b>GENERATE FORMS</b></small>
                <div class="form-group search-input">
                    <button class="btn btn-primary btn-sm w-100" wire:click="generateReport">EXPORT AD 01</button>
                </div>
                <div class="form-group search-input">
                    <button class="btn btn-primary btn-sm w-100" wire:click="generateReport">EXPORT AD 02</button>
                </div>
            </div>
        </div>
    </div>
@endif
