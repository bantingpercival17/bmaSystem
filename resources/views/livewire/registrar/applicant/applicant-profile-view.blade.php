@php
$pageTitle = 'Applicant Profile view';
@endphp
@section('page-title', $pageTitle)
<div class="row">
    <div class="col-md-12">
        <p class="display-6 fw-bolder text-primary">{{ $pageTitle }}</p>
        <div class="row">
            <div class="col-lg-8">
                @if ($profile)
                <div class="card mb-2">
                    <div class="row no-gutters">
                        <div class="col-md-3">
                            @if ($profile && $profile->profile_picture())
                            <img src="{{ $profile->profile_picture() }}" class="avatar-130 rounded" alt="applicant-profile">
                            @endif
                        </div>
                        <div class="col-md ps-0">
                            <div class="card-body p-3 me-2">
                                @php
                                $applicantName = $profile
                                ? ($profile->applicant
                                ? strtoupper(
                                $profile->applicant->last_name .
                                ', ' .
                                $profile->applicant->first_name,
                                )
                                : strtoupper($profile->name))
                                : 'MIDSHIPMAN NAME';
                                @endphp
                                <label for="" class="fw-bolder text-primary h4">{{ $applicantName }}</label>
                                <p class="mb-0">
                                    @if ($profile)
                                    <small class="fw-bolder badge {{ $profile->color_course() }}">
                                        {{ $profile->course->course_name }}
                                    </small> -
                                    <small class="badge bg-primary">
                                        {{ $profile->applicant_number }}
                                    </small>
                                    <small class="badge bg-primary">
                                        {{ $profile->email }}
                                    </small>-
                                    <small class="badge bg-primary">
                                        {{ $profile->strand }}
                                    </small>
                                    @endif
                                </p>
                                <div class="mt-2">
                                    @if ($profile)
                                    <small class="badge  border border-secondary text-secondary" data-bs-toggle="modal" data-bs-target=".modal-change-course" data-bs-toggle="change course" title="">
                                        CHANGE COURSE
                                    </small>
                                    @if ($profile->is_alumnia)
                                    <span class="badge bg-primary float-end">
                                        BMA SENIOR HIGH ALUMNUS
                                    </span>
                                    @else
                                    @if ($profile->applicant && $profile->senior_high_school())
                                    <button class="badge border border-primary text-primary" id="btn-alumnia" data-id="{{ base64_encode($profile->id) }}">
                                        BMA SENIOR HIGH ALUMNUS
                                    </button>
                                    @endif
                                    @if (Auth::user() && (Auth::user()->email == 'p.banting@bma.edu.ph' || Auth::user()->email == 'k.j.cruz@bma.edu.ph'))
                                    <button class="badge border border-primary text-primary" wire:click="dialogBoxSHS({{ $profile->id }})">BMA</button>
                                    @endif
                                    @endif
                                    @endif
                                    <small class="badge  border border-info text-info" title="Reset Password" wire:click="resetPassword('{{ $profile->id }}')">
                                        RESET PASSWORD
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="nav nav-underline text-muted bg-soft-primary text-center mt-3" id="myTab-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">PROFILE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="documents-tab" data-bs-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="true">DOCUMENTS</a>
                    </li>
                    <li class="nav-item text-muted">
                        <a class="nav-link" id="examination-tab" data-bs-toggle="tab" href="#examination" role="tab" aria-controls="examination" aria-selected="false">EXAMINATION</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="medical-tab" data-bs-toggle="tab" href="#medical" role="tab" aria-controls="medical" aria-selected="false"> ORIENTATION & MEDICAL</a>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="myTabContent-2">
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        @include('livewire.registrar.applicant.profile-components.information')
                    </div>
                    <div class="tab-pane fade  active show" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                        @include('livewire.registrar.applicant.profile-components.documents')
                    </div>
                    <div class="tab-pane fade" id="examination" role="tabpanel" aria-labelledby="examination-tab">
                        @include('livewire.registrar.applicant.profile-components.entrance-examination')
                    </div>
                </div>
                @else
                <div class="card mb-2">
                    <div class="row no-gutters">
                        <div class="col-md-3">
                            <img src="{{ 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}" class="card-img" alt="#">
                        </div>
                        <div class="col-md ps-0">
                            <div class="card-body p-3 me-2">
                                <h4 class="card-title text-primary fw-bolder">
                                    APPLICANT'S NAME
                                </h4>
                                <p class="card-text fw-bolder">
                                    <span>APPLICANT NO.| COURSE</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-12">
                        <small class="text-primary"><b>SEARCH STUDENT NAME</b></small>
                        <div class="form-group search-input">
                            <input type="search" class="form-control border border-primary" placeholder="Search Pattern: Lastname, Firstname" wire:model="searchInput">
                        </div>
                    </div>
                    <div class="col-12">
                        <small class="text-primary"><b>CATEGORY</b></small>
                        <div class="form-group search-input">
                            <select class="form-select border border-primary form-select-sm" wire:model="selectCategories">
                                @foreach ($filterContent as $item)
                                <optgroup label="{{ $item[0] }}">
                                    @foreach ($item[1] as $item)
                                    <option value="{{ $item }}">
                                        {{ ucwords(str_replace('_', ' ', $item)) }}
                                    </option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <small class="text-primary"><b>COURSE</b></small>
                        <div class="form-group search-input">
                            <select wire:model="selectCourse" class="form-select form-select-sm border border-primary " wire:click="categoryCourse">
                                <option value="ALL COURSE">{{ ucwords('all courses') }}</option>
                                @foreach ($filterCourses as $course)
                                <option value="{{ $course->id }}">{{ ucwords($course->course_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="filter-section m-0 p-0">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="fw-bolder text-muted">CATEGORY : </small> <br>
                            <label for="" class="fw-bolder text-primary">{{ str_replace('_', ' ', strtoupper($selectCategories)) }}</label>
                        </div>
                        <div class="col-md-6">
                            <small class="fw-bolder text-muted">COURSE : </small> <br>
                            <label for="" class="fw-bolder text-primary">{{ $selectedCourse }}</label>
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
                    {{-- @foreach ($dataLists as $item)
                            <p> {{ $item }}</p>
                    @endforeach --}}
                    @foreach ($dataLists as $data)
                    <div class="card mb-2">
                        <a href="{{ route('applicant.profile-view') }}?_applicant={{ base64_encode($data->id) }}&_academic={{ $this->academic }}&_catergory={{ $selectCategories }}">
                            <div class="row no-gutters">
                                <div class="col-md-4">

                                    @if ($data->image)
                                    <img src="{{ json_decode($data->image->file_links)[0] }}" class="avatar-100 rounded" alt="applicant-profile">
                                    @endif
                                </div>
                                <div class="col-md p-1">
                                    <div class="card-body p-2">
                                        @if ($data->applicant)
                                        <small class="text-primary fw-bolder">{{ strtoupper($data->applicant->last_name . ', ' . $data->applicant->first_name) }}</small>
                                        @else
                                        <small class="text-primary fw-bolder">{{ strtoupper($data->name) }}</small>
                                        @endif
                                        <br>
                                        <small class="badge {{ $data->color_course() }}">{{ $data->course->course_name }}</small>
                                        <br>
                                        <span>{{ $data->applicant_number }}</span>

                                    </div>
                                </div>
                            </div>
                        </a>

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
@if ($profile)
<div class="modal fade modal-change-course" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder" id="exampleModalLabel1">Change Course</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('applicant.applicant-change-course') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ $profile->id }}" name="applicant">
                    <div class="form-group">
                        <small class="fw-bolder">COURSE</small>
                        <select name="course" id="" class="form-select">
                            <option value="1" {{ $profile->course_id === 1 ? 'selected' : '' }}>BS
                                MARINE
                                ENGINEERING </option>
                            <option value="2" {{ $profile->course_id === 2 ? 'selected' : '' }}>BS
                                MARINE
                                TRANSPORTATION </option>
                            <option value="3" {{ $profile->course_id === 3 ? 'selected' : '' }}>PBM
                                SPECIALIZATION </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Update Course</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif