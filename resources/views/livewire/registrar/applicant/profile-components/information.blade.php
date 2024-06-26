<div class="card shadow">
    <div class="card-body">
        <a href="{{ route('applicant-form') }}?_applicant={{ base64_encode($profile->id) }}" target="_blank"
            class="badge bg-primary float-end">
            @if ($profile->course_id == 3)
                FORM RG-01
            @else
                FORM RG-03
            @endif
        </a>
        <label for="" class="h5 text-primary fw-bolder mb-3">STUDENT'S INFORMATION</label> <br>
        <div class="row">
            <div class="col-xl col-md-6 ">
                <div class="form-group">
                    <small class="text-center">LAST NAME</small>
                    <span class="form-control form-control-sm border border-primary fw-bolder text-primary">
                        {{ ucwords($profile->applicant->last_name) }}
                    </span>
                </div>
            </div>
            <div class="col-xl col-md-6 ">
                <div class="form-group">
                    <small class="form-control-label">FIRST NAME</small>
                    <span class="form-control form-control-sm border border-primary fw-bolder text-primary">
                        {{ ucwords($profile->applicant->first_name) }}
                    </span>
                </div>
            </div>
            <div class="col-xl col-md-6 ">
                <div class="form-group">
                    <small class="form-control-label">MIDDLE NAME</small>
                    <span class="form-control form-control-sm border border-primary fw-bolder text-primary">
                        {{ ucwords($profile->applicant->middle_name) }}
                    </span>
                </div>
            </div>
            <div class="col-xl-2 col-md-6 ">
                <div class="form-group">
                    <small class="form-control-label">EXT.</small>
                    <span class="form-control form-control-sm border border-primary fw-bolder text-primary">
                        {{ $profile->applicant->extention_name ? ucwords($profile->applicant->extention_name) : 'none' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-md-6 mb-xl-0">
                <div class="form-group">
                    <small class="text-center">GENDER</small>
                    <span class="form-control form-control-sm border border-primary fw-bolder text-primary">
                        {{ ucwords($profile->applicant->sex) }}
                    </span>
                </div>
            </div>
            <div class="col-xl col-md-6 mb-xl-0">
                <div class="form-group">
                    <small class="text-center">BIRTHDAY</small>
                    <span class="form-control form-control-sm border border-primary fw-bolder text-primary">
                        {{ $profile->applicant->birthday }}
                    </span>
                </div>
            </div>
            <div class="col-xl col-md-6 mb-xl-0">
                <div class="form-group">
                    <small class="text-center">CIVIL STATUS</small>
                    <span class="form-control form-control-sm border border-primary fw-bolder text-primary">
                        {{ $profile->applicant->civil_status }}
                    </span>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xl col-md-6 mb-xl-0">
                <div class="form-group">
                    <small class="text-center">HEIGHT</small>
                    <span class="form-control form-control-sm border border-primary fw-bolder text-primary">
                        {{ ucwords($profile->applicant->height) . ' cm' }}
                    </span>
                </div>
            </div>
            <div class="col-xl col-md-6 mb-xl-0">
                <div class="form-group">
                    <small class="text-center">WEIGHT</small>
                    <span class="form-control form-control-sm border border-primary fw-bolder text-primary">
                        {{ $profile->applicant->weight . ' pounds' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-xl-0">
                <div class="form-group">
                    <small class="text-center">NATIONALITY</small>
                    <span class="form-control form-control-sm border border-primary fw-bolder text-primary">
                        {{ $profile->applicant->nationality }}
                    </span>
                </div>
            </div>
            <div class="col-xl col-md-6 mb-xl-0">
                <div class="form-group">
                    <small class="text-center">BIRTH PLACE</small>
                    <span class="form-control form-control-sm border border-primary fw-bolder text-primary">
                        {{ ucwords($profile->applicant->birth_place) }}
                    </span>
                </div>
            </div>
        </div>
        <label for="" class="h6 text-primary fw-bolder mt-3">ADDRESS</label>
        <div class="row">
            <div class="col-xl-9 col-md-6 mb-xl-0">
                <div class="form-group">
                    <small class="text-center">HOUSE NO./ STREET / BLDG NO.</small>
                    <span class="form-control form-control-sm border border-primary fw-bolder text-primary">
                        {{ ucwords($profile->applicant->street) }}
                    </span>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-xl-0">
                <div class="form-group">
                    <small for="example-text-input" class="text-center">ZIP CODE</small>
                    <span class="form-control form-control-sm border border-primary fw-bolder text-primary">
                        {{ ucwords($profile->applicant->zip_code) }}
                    </span>
                </div>
            </div>
            <div class="col-xl col-md-6 mb-xl-0">
                <div class="form-group">
                    <small class="text-center">BARANGAY</small>
                    <span
                        class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ ucwords($profile->applicant->barangay) }}</span>
                </div>
            </div>
            <div class="col-xl col-md-6 mb-xl-0">
                <div class="form-group">
                    <small class="text-center">MUNICIPALITY</small>
                    <span
                        class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ ucwords($profile->applicant->municipality) }}</span>
                </div>
            </div>
            <div class="col-xl col-md-6 mb-xl-0">
                <div class="form-group">
                    <small class="text-center">PROVINCE</small>
                    <span
                        class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ ucwords($profile->applicant->province) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="form-view">
            <h6 class="h5 mb-1 text-primary"><b>PARENTS'S INFORMATION</b></h6>
            <div class="row">
                <div class="col-xl-8 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">FATHER'S NAME</small>

                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ ucwords($profile->applicant->father_first_name . ' ' . $profile->applicant->father_last_name) ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">CONTACT NUMBER</small>

                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ $profile->applicant->father_contact_number ?: '' }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-8 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">MOTHER'S MAIDEN NAME:</small>

                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ ucwords($profile->applicant->mother_first_name . ' ' . $profile->applicant->mother_last_name) ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">CONTACT NUMBER</small>

                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ $profile->applicant->mother_contact_number ?: '' }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-8 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">GUARDIAN'S NAME</small>

                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ ucwords($profile->applicant->guardian_first_name . ' ' . $profile->applicant->guardian_last_name) ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">CONTACT NUMBER</small>

                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ $profile->applicant->guardian_contact_number ?: '' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="form-view">
            <h6 class="h5 mb-1 text-primary"><b>EDUCATIONAL BACKGROUND</b></h6>
            <label for="" class="text-primary fw-bolder">ELEMENTARY SCHOOL</label>
            <div class="row">
                <div class="col-xl-8 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">SCHOOL
                            NAME</small>

                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ $profile->applicant->elementary_school_name ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">YEAR
                            GRADUATED</small>

                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ $profile->applicant->elementary_school_year ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-12 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">SCHOOL
                            ADDRESS</small>
                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ $profile->applicant->elementary_school_address }}</span>
                    </div>
                </div>
            </div>
            <label for="" class="text-primary fw-bolder">JUNIOR HIGH SCHOOL</label>
            <div class="row">
                <div class="col-xl-8 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">SCHOOL
                            NAME</small>

                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ $profile->applicant->junior_high_school_name ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">YEAR
                            GRADUATED</small>

                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ $profile->applicant->junior_high_school_year ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-12 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">SCHOOL
                            ADDRESS</small>
                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ $profile->applicant->junior_high_school_address }}</span>
                    </div>
                </div>
            </div>
        </div>
        @if ($profile->applicant->senior_high_school_name)
            <label for="" class="text-primary fw-bolder">SENIOR HIGH SCHOOL</label>
            <div class="row">
                <div class="col-xl-8 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">SCHOOL
                            NAME</small>

                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ $profile->applicant->senior_high_school_name ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">YEAR
                            GRADUATED</small>

                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ $profile->applicant->senior_high_school_year ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-12 col-md mb-xl-0">
                    <div class="form-group">
                        <small class="text-center">SCHOOL
                            ADDRESS</small>
                        <span
                            class="form-control form-control-sm border border-primary fw-bolder text-primary">{{ $profile->applicant->senior_high_school_address }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
