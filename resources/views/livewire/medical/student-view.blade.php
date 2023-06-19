@php
    $pageTitle = 'Student Medical Overview';
@endphp
@section('page-title', $pageTitle)
<div class="row">
    <div class="col-md-12">
        <p class="display-6 fw-bolder text-primary">{{ $pageTitle }}</p>
        <div class="row">
            <div class="col-lg-4">
                <p class="h4 text-info fw-bolder">FILTER SELECTION</p>
                <div class="row">
                    <div class="col-12">
                        <small class="text-primary"><b>SEARCH STUDENT NAME</b></small>
                        <div class="form-group search-input">
                            <input type="search" class="form-control" placeholder="Search Pattern: Lastname, Firstname"
                                wire:model="searchInput" wire:keydown="searchStudents">
                        </div>
                    </div>
                    <div class="col-12">
                        <small class="text-primary"><b>CATEGORY</b></small>
                        <div class="form-group search-input">
                            <select class="form-select" wire:model="selectCategories">
                                @foreach ($selectContent as $item)
                                    <option value="{{ $item[1] }}">{{ ucwords($item[0]) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <small class="text-primary"><b>COURSE</b></small>
                        <div class="form-group search-input">
                            <select wire:model="selectCourse" class="form-select" wire:click="chooseCourse">
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
                            <select wire:model="selectLevel" class="form-select">
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
                        <small class="text-primary"><b>SECTION</b></small>
                        <div class="form-group search-input">
                            <select wire:model="selectSection" class="form-select">
                                <option value="ALL SECTION">{{ ucwords('all section') }}</option>
                                @if (count($sections) > 0)
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ ucwords($section->section_name) }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="">PLEASE SELECT COURSE</option>
                                @endif

                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="filter-section m-0 p-0">
                    {{ $showData }}
                    <small class="fw-bolder text-info">FILTER DETAILS:</small>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="fw-bolder text-muted">CATEGORY : </small> <br>
                            <label for="" class="fw-bolder text-primary">{{ $selectCategories }}</label>
                        </div>
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
                <div class="data-content">
                    @if (count($dataLists) > 0)
                        {{--  @foreach ($dataLists as $item)
                            <p> {{ $item }}</p>
                        @endforeach --}}
                        @foreach ($dataLists as $data)
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <span
                                                class="badge {{ $data->enrollment_assessment->color_course() }}">{{ $data->enrollment_assessment->course->course_code }}</span>
                                            -
                                            <span><b>{{ $data ? $data->account->student_number : '-' }}</b></span>
                                            <a
                                                href="{{ route('applicant-profile') }}?_student={{ base64_encode($data->id) }}">
                                                <div class="mt-2 h4 fw-bolder text-primary">
                                                    {{ strtoupper($data->last_name . ', ' . $data->first_name) }}
                                                </div>
                                            </a>
                                            <span> <small>CONTACT NUMBER:</small>
                                                <b>{{ $data->account ? $data->account->student->contact_number : '-' }}</b>
                                            </span>
                                        </div>
                                        <div class="col-md-4">
                                            @if ($selectCategories == 'waiting_for_medical_result')
                                                <div class="medical-result">
                                                    <label for="" class="fw-bolder text-info">MEDICAL
                                                        RESULT</label>
                                                    <button class="btn btn-primary btn-sm w-100  mb-2"
                                                        wire:click="medicalResult({{ $data->id }},{{ $data->enrollment_id }},1)">FIT</button>
                                                    <a class="btn btn-danger btn-sm w-100 mb-2 btn-medical"
                                                        data-applicant="{{ base64_encode($data->id) }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target=".modal-medical-fail">FAIL</a>
                                                    <a class="btn btn-info btn-sm w-100 text-white mb-2 btn-medical"
                                                        data-applicant="{{ base64_encode($data->id) }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target=".modal-medical-pending">PENDING</a>
                                                </div>
                                            @endif
                                            @if (
                                                $selectCategories == 'medical_result_passed' ||
                                                    $selectCategories == 'medical_result_pending' ||
                                                    $selectCategories == 'medical_result_failed')
                                                @if ($data->enrollment_assessment->medical_result)
                                                    @if ($data->medical_result->is_fit !== null)
                                                        @if ($data->medical_result->is_fit === 1)
                                                            <span class="badge bg-primary mb-4">FIT TO ENROLL</span>
                                                        @else
                                                            <span class="badge bg-danger mb-4">FAILED</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-info mb-4">PENDING RESULT</span>
                                                        <a href="{{ route('medical.applicant-medical-result') . '?result=' . base64_encode(1) . '&applicant=' . base64_encode($data->id) }}"
                                                            class="btn btn-primary btn-sm w-100 mb-2">FIT</a>
                                                        <a class="btn btn-danger btn-sm w-100 mb-2 btn-medical"
                                                            data-applicant="{{ base64_encode($data->id) }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target=".modal-medical-fail">FAIL</a>
                                                    @endif
                                                    <span
                                                        class="badge bg-secondary">{{ $data->medical_result->created_at->format('F d,Y') }}</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
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
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('assets\plugins\sweetalert2\sweetalert2.all.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets\plugins\sweetalert2\sweetalert2.min.css') }}">
    <script>
        window.addEventListener('swal:alert', event => {
            Swal.fire({
                    title: event.detail.title,
                    text: event.detail.text,
                    icon: event.detail.type,
                });
            //alert('Name updated to: ' + event.detail.newName);
        })
        window.addEventListener('name-updated', event => {
            alert('Name updated to: ' + event.detail.newName);
        })
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('swal:confirm', function(options) {
                Swal.fire({
                    title: options.title,
                    text: options.text,
                    icon: options.type,
                    showCancelButton: true,
                    confirmButtonText: options.confirmButtonText,
                    cancelButtonText: options.cancelButtonText,
                }).then((result) => {
                    if (result.isConfirmed && options.method) {
                        Livewire.emit(options.method);
                    }
                });
            });

            Livewire.on('swal:alert', function(options) {
                Swal.fire({
                    title: options.title,
                    text: options.text,
                    icon: options.type,
                });
            });
        });
    </script>
@endpush
