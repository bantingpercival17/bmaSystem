<div class="card">
    <div class="card-header pb-0 p-3">
        <a href="{{ route('admin.student-qrcode') }}?_student={{ base64_encode($profile->id) }}" target="_blank"
            class="badge bg-primary ms-2 float-end">GENERATE QR-CODE</a>
        <a href="{{ route('registrar.student-application-view') }}?_student={{ base64_encode($profile->id) }}"
            target="_blank" class="badge bg-primary float-end">FORM RG-01</a>
        <h5 class="mb-1"><b>PROFILE INFORMATION</b></h5>
        <p class="text-sm">Student Information of the cadet's/ student's at Baliwag Maritime Academy</p>

    </div>
    <div class="card-body p-3">

        <div class="form-view">
            <h6 class="mb-1 text-primary"><b>FULL NAME</b></h6>
            <div class="row">
                <div class="col-md">
                    <small class="text-muted">LAST NAME</small> <br>
                    <label
                        class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($profile->last_name) }}</label>
                </div>
                <div class="col-md">
                    <small class="text-muted">FIRST NAME</small> <br>
                    <label
                        class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($profile->first_name) }}</label>
                </div>
                <div class="col-md">
                    <small class="text-muted">MIDDLE NAME</small> <br>
                    <label
                        class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($profile->middle_name) }}</label>
                </div>
                <div class="col-md-2">
                    <small class="text-muted">EXT</small> <br>
                    <label
                        class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $profile->extention_name ? ucwords($profile->extention_name) : '' }}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <small class="text-muted">GENDER</small> <br>
                        <label
                            class="fw-bolder form-control form-control-sm  border border-primary text-primary">{{ ucwords($profile->sex) }}</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <small class="text-muted">CIVIL STATUS</small> <br>
                        <label
                            class="fw-bolder form-control form-control-sm  border border-primary text-primary">{{ ucwords($profile->civil_status) }}</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <small class="text-muted">NATIONALITY</small> <br>
                        <label
                            class="fw-bolder form-control form-control-sm  border border-primary text-primary">{{ $profile->nationality }}</label>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <small class="text-muted">BIRTHDAY</small> <br>
                        <label
                            class="fw-bolder form-control form-control-sm  border border-primary text-primary">{{ $profile->birthday }}</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <small class="text-muted">BIRTH PLACE</small> <br>
                        <label
                            class="fw-bolder form-control form-control-sm  border border-primary text-primary">{{ ucwords($profile->birth_place) }}</label>
                    </div>
                </div>
            </div>
            <h6 class="mb-1 text-primary"><b>ADDRESS</b></h6>
            <div class="row">
                <div class="col-xl-9 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">HOUSE NO. / STREET / BLDG NO.</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($profile->street) }}</span>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">ZIP CODE</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($profile->zip_code) }}</span>
                    </div>
                </div>
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">BARANGAY</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($profile->barangay) }}</span>
                    </div>
                </div>
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">MUNICIPALITY</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($profile->municipality) }}</span>
                    </div>
                </div>
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">PROVINCE</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($profile->province) }}</span>
                    </div>
                </div>
            </div>
            <h6 class="mb-1 text-primary"><b>CONTACT DETAILS</b></h6>
            <div class="row">
                <div class="col-xl-6 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">CONTACT NUMBER</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $profile->contact_number ?: 'Contact Number' }}</span>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">EMAIL</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $profile->account ? $profile->account->personal_email : 'Personal Email' }}</span>
                    </div>
                </div>
            </div>
            <h6 class="mb-1 text-primary h4"><b>PARENT'S DETAILS</b></h6>
            <h6 class="mb-1 text-primary"><b>FATHER'S INFORMATION</b></h6>
            <div class="row">
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">FATHER'S NAME</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $profile->account ? ($profile->parent_details ? strtoupper($profile->parent_details->father_first_name . ' ' . $profile->parent_details->father_last_name) : 'NULL') : 'NO DATA' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">CONTACT NUMBER</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $profile->parent_details ? $profile->parent_details->father_contact_number : 'Contact Number' }}</span>
                    </div>
                </div>
            </div>
            <h6 class="mb-1 text-primary"><b>MOTHER'S INFORMATION</b></h6>
            <div class="row">
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">MOTHER'S NAME</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $profile->account ? ($profile->parent_details ? strtoupper($profile->parent_details->mother_first_name . ' ' . $profile->parent_details->mother_last_name) : 'NULL') : 'NO DATA' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">CONTACT NUMBER</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $profile->parent_details ? $profile->parent_details->mother_contact_number : 'Contact Number' }}</span>
                    </div>
                </div>
            </div>
            <h6 class="mb-1 text-primary"><b>GUARDIAN'S INFORMATION</b></h6>
            <div class="row">
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">GUARDIAN'S NAME</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $profile->account ? ($profile->parent_details ? strtoupper($profile->parent_details->guardian_first_name . ' ' . $profile->parent_details->guardian_last_name) : 'NULL') : 'NO DATA' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">CONTACT NUMBER</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $profile->parent_details ? $profile->parent_details->guardian_contact_number : 'Contact Number' }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
