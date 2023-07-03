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
                                @if ($profile->image)
                                    <img src="{{ json_decode($profile->image->file_links)[0] }}"
                                        class="avatar-130 rounded" alt="applicant-profile">
                                @endif
                                {{-- <img src="{{ $_student ? $_student->profile_picture() : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="card-img" alt="#"> --}}
                            </div>
                            <div class="col-md ps-0">
                                <div class="card-body p-3 me-2">
                                    <p class="float-end">
                                        <small class="badge bg-info" data-bs-toggle="modal"
                                            data-bs-target=".modal-change-course" data-bs-toggle="change course"
                                            title="">
                                            CHANGE COURSE
                                        </small>
                                    </p>
                                    <label for=""
                                        class="fw-bolder text-primary h4">{{ $profile ? strtoupper($profile->applicant->last_name . ', ' . $profile->applicant->first_name) : 'MIDSHIPMAN NAME' }}</label>
                                    <p class="mb-0">
                                        <small class="fw-bolder badge {{ $profile->color_course() }}">
                                            {{ $profile->course->course_name }}
                                        </small> -
                                        <small class="badge bg-primary">
                                            {{ $profile->applicant_number }}
                                        </small>
                                    </p>
                                    <p>

                                        @if ($profile->is_alumnia)
                                            <span class="badge bg-primary float-end">
                                                BMA SENIOR HIGH ALUMNUS
                                            </span>
                                        @else
                                            @if ($profile->senior_high_school())
                                                <button class="btn btn-outline-primary btn-sm float-end rounded-pill"
                                                    id="btn-alumnia" data-id="{{ base64_encode($profile->id) }}"> BMA
                                                    SENIOR HIGH ALUMNUS</button>
                                            @endif
                                            @if (Auth::user()->email == 'p.banting@bma.edu.ph' || Auth::user()->email == 'k.j.cruz@bma.edu.ph')
                                                <button class="btn btn-primary btn-sm float-end"
                                                    wire:click="dialogBoxSHS({{ $profile->id }})">BMA</button>
                                            @endif
                                        @endif

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="nav nav-underline bg-soft-primary pb-0 text-center" aria-label="Secondary navigation">
                        <thead class="d-flex">
                            <tr>
                                <td class="nav-link  {{ $activeTab == 'overiew' ? 'active' : 'text-muted' }} "
                                wire:click="swtchTab('overiew')">OVERVIEW</td>
                                <td class="nav-link  {{ $activeTab == 'profile' ? 'active' : 'text-muted' }} "
                                wire:click="swtchTab('profile')">PROFILE</td>
                                <td class="nav-link  {{ $activeTab == 'overiew' ? 'active' : 'text-muted' }} "
                                wire:click="swtchTab('overiew')">OVERVIEW</td>

                            </tr>
                        </thead>
                    </table>
                    <nav class="nav nav-underline bg-soft-primary pb-0 text-center" aria-label="Secondary navigation">

                        <div class="d-flex" id="head-check">
                            <a class="nav-link {{ $activeTab == 'overiew' ? 'active' : 'text-muted' }}"
                                wire:click="swtchTab('overiew')">OVERVIEW</a>
                            <a class="nav-link {{ $activeTab == 'profile' ? 'active' : 'text-muted' }}"
                                wire:click="swtchTab('profile')">PROFILE</a>
                            <a class="nav-link {{ $activeTab == 'documents' ? 'active' : 'text-muted' }}"
                                wire:click="swtchTab('documents')">DOCUMENTS</a>

                            <a class="nav-link   {{ request()->input('view') == 'grades' ? 'active' : 'text-muted' }}"
                                href="{{ route('registrar.student-profile') }}?student={{ base64_encode($profile->id) }}&view=grades">ENTRANCE
                                EXAMINATION</a>
                            <a class="nav-link {{ $activeTab == 'medical' ? 'active' : 'text-muted' }}"
                                wire:click="swtchTab('medical')">ORIENTATION & MEDICAL</a>
                        </div>
                    </nav>
                    <div class="mt-4">
                        @if ($activeTab == 'profile')
                            @include('livewire.registrar.applicant.profile-components.information')
                        @endif
                        @if ($activeTab == 'documents')
                            @include('livewire.registrar.applicant.profile-components.documents')
                        @endif
                        @if ($activeTab == 'account')
                            @include('livewire.registrar.applicant.profile-components.entrance-examination')
                        @endif
                        @if ($activeTab == 'grades')
                            @include('pages.administrator.student.profile-tab-content.grade-view')
                        @endif
                        @if ($activeTab == 'medical')
                            @include('livewire.registrar.applicant.profile-components.medical-view')
                        @endif
                    </div>
                @else
                    <div class="card mb-2">
                        <div class="row no-gutters">
                            <div class="col-md-3">
                                <img src="{{ 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                                    class="card-img" alt="#">
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
                            <input type="search" class="form-control" placeholder="Search Pattern: Lastname, Firstname"
                                wire:model="searchInput">
                        </div>
                    </div>
                    <div class="col-12">
                        <small class="text-primary"><b>CATEGORY</b></small>
                        <div class="form-group search-input">
                            <select class="form-select form-select-sm" wire:model="selectCategories">
                                @foreach ($filterContent as $item)
                                    <option value="{{ $item }}">{{ ucwords(str_replace('_', ' ', $item)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <small class="text-primary"><b>COURSE</b></small>
                        <div class="form-group search-input">
                            <select wire:model="selectCourse" class="form-select form-select-sm"
                                wire:click="categoryCourse">
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
                            <label for=""
                                class="fw-bolder text-primary">{{ str_replace('_', ' ', strtoupper($selectCategories)) }}</label>
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
                                <a
                                    href="{{ route('applicant.profile-view') }}?_applicant={{ base64_encode($data->id) }}&_academic={{ $this->academic }}&_catergory={{ $selectCategories }}">
                                    <div class="row no-gutters">
                                        <div class="col-md-4">

                                            @if ($data->image)
                                                <img src="{{ json_decode($data->image->file_links)[0] }}"
                                                    class="avatar-100 rounded" alt="applicant-profile">
                                            @endif
                                        </div>
                                        <div class="col-md p-1">
                                            <div class="card-body p-2">
                                                <small
                                                    class="text-primary fw-bolder">{{ strtoupper($data->applicant->last_name . ', ' . $data->applicant->first_name) }}</small>
                                                <br>
                                                <small
                                                    class="badge {{ $data->color_course() }}">{{ $data->course->course_name }}</small>
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
