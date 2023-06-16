@php
    $pageTitle = 'Applicant Medical Overview';
@endphp
@section('page-title', $pageTitle)
<div class="row">
    <div class="col-md-12">
        <p class="display-6 fw-bolder text-primary">{{ $pageTitle }}</p>
        <div class="row">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between mb-3">
                    @if ($searchInput != '')
                        <div>
                            <p for="" class="h5">
                                <small class="text-muted"> Search Result:</small>
                                <span class="fw-bolder h6 text-primary"> {{ strtoupper($searchInput) }}</span>
                            </p>
                        </div>
                        <div>
                            No. Result: <b>{{ count($applicants) }}</b>
                        </div>
                    @else
                        <div>
                            <span class="fw-bolder">
                                RECENT APPLICANTS
                            </span>
                        </div>
                        <div>
                            No. Result: <b>{{ count($applicants) }}</b>
                        </div>
                    @endif
                </div>
                <div class="filter-section m-0 p-0">
                    <small class="fw-bolder text-info">FILTER DETAILS:</small>
                    <div class="row">
                        <div class="col-md">
                            <small class="fw-bolder text-muted">CATEGORY : </small> <br>
                            <label for="" class="fw-bolder text-primary">{{ $selectedCategory }}</label>
                        </div>
                        <div class="col-md">
                            <small class="fw-bolder text-muted">COURSE : </small> <br>
                            <label for="" class="fw-bolder text-primary">{{ $selectedCourse }}</label>
                        </div>
                    </div>
                </div>
                <div class="data-content">
                    @if (count($applicants) > 0)
                        {{--   @foreach ($applicants as $item)
                            {{ $item }}
                        @endforeach --}}
                        @foreach ($applicants as $_data)
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <span
                                                class="badge {{ $_data->course_id == 3 ? 'bg-info' : 'bg-primary' }}">{{ $_data->course->course_code }}</span>
                                            <span><b>{{ $_data ? $_data->applicant_number : '-' }}</b></span>
                                            <a
                                                href="{{ route('applicant-profile') }}?_student={{ base64_encode($_data->applicant_id) }}">
                                                <div class="mt-2 h4 fw-bolder text-primary">
                                                    {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                                                </div>
                                            </a>
                                            <span> <small>CONTACT NUMBER:</small>
                                                <b>{{ $_data->applicant ? $_data->contact_number : '-' }}</b>
                                            </span>
                                        </div>
                                        <div class="col-md-4">
                                            @if ($selecteCategories == 'waiting_for_scheduled')
                                                <label for="" class="fw-bolder text-info">MEDICAL
                                                    SCHEDULE</label>
                                                <form action="{{ route('medical.applicant-schedule') }}"
                                                    method="post">
                                                    @csrf
                                                    @php
                                                        $_format = 'D F d, Y';
                                                    @endphp
                                                    <input type="hidden" name="applicant" value="{{ $_data->id }}">
                                                    <div class="form-group">
                                                        <small for="" class="fw-bolder text-muted">SCHEDULE
                                                            DATE</small>
                                                        <select name="date" id=""
                                                            class="form-select form-select-sm">
                                                            <option disabled selected>Select Date</option>
                                                            @foreach ($dates as $date)
                                                                <option value="{{ $date->date }}"
                                                                    {{ Auth::user()->staff->medical_appointment_slot($date->date) == $date->capacity ? 'disabled' : '' }}>
                                                                    {{ date($_format, strtotime($date->date)) }} -
                                                                    <span
                                                                        class="text-info fw-bolder">{{ Auth::user()->staff->medical_appointment_slot($date->date) }}</span><small
                                                                        class="text-secondary">/{{ $date->capacity }}</small>
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit"
                                                            class="btn btn-sm btn-primary mt-2 w-100">SUBMIT</button>
                                                    </div>
                                                </form>
                                            @endif
                                            @if ($selecteCategories == 'medical_scheduled')
                                                <div class="medical-schedule-content">
                                                    <small class="text-muted fw-bolder">APPOINTMENT SCHEDULE</small>
                                                    <div class="badge bg-primary w-100">
                                                        <span>{{ $_data->medical_appointment->appointment_date }}</span>
                                                    </div>
                                                    <a href="{{ route('medical.applicant-appointment') }}?appointment={{ base64_encode($_data->medical_appointment->id) }}&status=true"
                                                        class="btn btn-sm btn-outline-info w-100 mt-2">APPROVED</a>
                                                    <a href="{{ route('medical.applicant-appointment') }}?appointment={{ base64_encode($_data->medical_appointment->id) }}&status=false"
                                                        class="btn btn-sm btn-outline-danger w-100 mt-2">DISAPPROVED</a>
                                                </div>
                                            @endif
                                            @if ($selecteCategories == 'waiting_for_medical_result')
                                                <div class="medical-result">
                                                    <label for="" class="fw-bolder text-info">MEDICAL
                                                        RESULT</label>
                                                    <a href="{{ route('medical.applicant-medical-result') . '?result=' . base64_encode(1) . '&applicant=' . base64_encode($_data->id) }}"
                                                        class="btn btn-primary btn-sm w-100 mb-2">FIT</a>
                                                    <a class="btn btn-danger btn-sm w-100 mb-2 btn-medical"
                                                        data-applicant="{{ base64_encode($_data->id) }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target=".modal-medical-fail">FAIL</a>
                                                    <a class="btn btn-info btn-sm w-100 text-white mb-2 btn-medical"
                                                        data-applicant="{{ base64_encode($_data->id) }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target=".modal-medical-pending">PENDING</a>
                                                </div>
                                            @endif
                                            @if (
                                                $selecteCategories == 'medical_result_passed' ||
                                                    $selecteCategories == 'medical_result_pending' ||
                                                    $selecteCategories == 'medical_result_failed')
                                                @if ($_data->medical_result)
                                                    @if ($_data->medical_result->is_fit !== null)
                                                        @if ($_data->medical_result->is_fit === 1)
                                                            <span class="badge bg-primary mb-4">FIT TO ENROLL</span>
                                                        @else
                                                            <span class="badge bg-danger mb-4">FAILED</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-info mb-4">PENDING RESULT</span>
                                                        <a href="{{ route('medical.applicant-medical-result') . '?result=' . base64_encode(1) . '&applicant=' . base64_encode($_data->id) }}"
                                                            class="btn btn-primary btn-sm w-100 mb-2">FIT</a>
                                                        <a class="btn btn-danger btn-sm w-100 mb-2 btn-medical"
                                                            data-applicant="{{ base64_encode($_data->id) }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target=".modal-medical-fail">FAIL</a>
                                                    @endif
                                                    <span
                                                        class="badge bg-secondary">{{ $_data->medical_result->created_at->format('F d,Y') }}</span>
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
                            <select class="form-select" wire:model="selecteCategories" wire:click="categoryChange">
                                @foreach ($selectContent as $item)
                                    <option value="{{ $item[1] }}">{{ ucwords($item[0]) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <small class="text-primary"><b>COURSE</b></small>
                        <div class="form-group search-input">
                            <select wire:model="selectCourse" class="form-select" wire:click="categoryCourse">
                                <option value="ALL COURSE">{{ ucwords('all courses') }}</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ ucwords($course->course_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
