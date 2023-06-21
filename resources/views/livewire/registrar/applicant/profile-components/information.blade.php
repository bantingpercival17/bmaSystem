<div class="card">
    <div class="card-body">
        <div class="form-view">
            <h6 class="mb-1"><b>FULL NAME</b></h6>
            <div class="row">
                <div class="col-xl col-md-6 ">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Last
                            name</small>
                        <span class="form-control">{{ ucwords($profile->applicant->last_name) }}</span>
                    </div>
                </div>
                <div class="col-xl col-md-6 ">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">First
                            name</small>
                        <span class="form-control">{{ ucwords($profile->applicant->first_name) }}</span>
                    </div>
                </div>
                <div class="col-xl col-md-6 ">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Middle
                            name</small>
                        <span class="form-control">{{ ucwords($profile->applicant->middle_name) }}</span>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 ">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Extension</small>
                        <span
                            class="form-control">{{ $profile->applicant->extention_name ? ucwords($profile->applicant->extention_name) : 'none' }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Gender</small>
                        <span class="form-control">{{ ucwords('male') }}</span>
                    </div>
                </div>
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Birthday</small>
                        <span class="form-control">{{ $profile->applicant->birthday }}</span>
                    </div>
                </div>
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Birth
                            Place</small>
                        <span class="form-control">{{ ucwords($profile->applicant->birth_place) }}</span>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Civil
                            Status</small>
                        <span class="form-control">{{ ucwords($profile->applicant->civil_status) }}</span>
                    </div>
                </div>
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Nationality</small>
                        <span class="form-control">{{ $profile->applicant->nationality }}</span>
                    </div>
                </div>
            </div>
            <h6 class="mb-1"><b>ADDRESS</b></h6>
            <div class="row">
                <div class="col-xl-5 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Hous no /
                            Street / Bldg
                            no</small>
                        <span class="form-control">{{ ucwords($profile->applicant->street) }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Barangay</small>
                        <span class="form-control">{{ ucwords($profile->applicant->barangay) }}</span>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Zip
                            Code</small>
                        <span class="form-control">{{ ucwords($profile->applicant->zip_code) }}</span>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Municipality</small>
                        <span class="form-control">{{ ucwords($profile->applicant->municipality) }}</span>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Province</small>
                        <span class="form-control">{{ ucwords($profile->applicant->province) }}</span>
                    </div>
                </div>
            </div>
            <h6 class="mb-1"><b>CONTACT DETIALS</b></h6>
            <div class="row">
                <div class="col-xl-6 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Contact
                            Number</small>

                        <span class="form-control">{{ $profile->contact_number ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">Email</small>
                        <span class="form-control">{{ $profile->email }}</span>
                    </div>
                </div>
            </div>
            <h6 class="mb-1"><b>EDUCATIONA BACKGROUD</b></h6>
            <label for="" class="form-label fw-bolder">ELEMENTARY</label>
            <div class="row">
                <div class="col-xl-8 col-md mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">SCHOOL
                            NAME</small>

                        <span class="form-control">{{ $profile->applicant->elementary_school_name ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">YEAR
                            GRADUATED</small>

                        <span class="form-control">{{ $profile->applicant->elementary_school_year ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-12 col-md mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">SCHOOL
                            ADDRESS</small>
                        <span class="form-control">{{ $profile->applicant->elementary_school_address }}</span>
                    </div>
                </div>
            </div>
            <label for="" class="form-label fw-bolder">JUNIOR HIGH SCHOOL</label>
            <div class="row">
                <div class="col-xl-8 col-md mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">SCHOOL
                            NAME</small>

                        <span class="form-control">{{ $profile->applicant->junior_high_school_name ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">YEAR
                            GRADUATED</small>

                        <span class="form-control">{{ $profile->applicant->junior_high_school_year ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-12 col-md mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">SCHOOL
                            ADDRESS</small>
                        <span class="form-control">{{ $profile->applicant->junior_high_school_address }}</span>
                    </div>
                </div>
            </div>
        </div>
        @if ($profile->applicant->senior_high_school_name)
            <label for="" class="form-label fw-bolder">SENIOR HIGH SCHOOL</label>
            <div class="row">
                <div class="col-xl-8 col-md mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">SCHOOL
                            NAME</small>

                        <span class="form-control">{{ $profile->applicant->senior_high_school_name ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">YEAR
                            GRADUATED</small>

                        <span class="form-control">{{ $profile->applicant->senior_high_school_year ?: '' }}</span>
                    </div>
                </div>
                <div class="col-xl-12 col-md mb-xl-0">
                    <div class="form-group">
                        <small for="example-text-input" class="form-control-label">SCHOOL
                            ADDRESS</small>
                        <span class="form-control">{{ $profile->applicant->senior_high_school_address }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
