<div class="card">
    <div class="card-header pb-0 p-3">
        <h5 class="mb-1"><b>PROFILE INFORMATION</b></h5>

    </div>
    <div class="card-body p-3">

        <div class="form-view">
            <h6 class="mb-1 text-primary"><b>FULL NAME</b></h6>
            <div class="row">
                <div class="col-md">
                    <small class="text-muted">LAST NAME</small> <br>
                    <label
                        class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($employee->last_name) }}</label>
                </div>
                <div class="col-md">
                    <small class="text-muted">FIRST NAME</small> <br>
                    <label
                        class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($employee->first_name) }}</label>
                </div>
                <div class="col-md">
                    <small class="text-muted">MIDDLE NAME</small> <br>
                    <label
                        class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($employee->middle_name) }}</label>
                </div>
                <div class="col-md-2">
                    <small class="text-muted">EXT</small> <br>
                    <label
                        class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $employee->extention_name ? ucwords($employee->extention_name) : '' }}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <small class="text-muted">GENDER</small> <br>
                        <label
                            class="fw-bolder form-control form-control-sm  border border-primary text-primary">{{ ucwords($employee->sex) }}</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <small class="text-muted">CIVIL STATUS</small> <br>
                        <label
                            class="fw-bolder form-control form-control-sm  border border-primary text-primary">{{ ucwords($employee->civil_status) }}</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <small class="text-muted">NATIONALITY</small> <br>
                        <label
                            class="fw-bolder form-control form-control-sm  border border-primary text-primary">{{ $employee->nationality }}</label>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <small class="text-muted">BIRTHDAY</small> <br>
                        <label
                            class="fw-bolder form-control form-control-sm  border border-primary text-primary">{{ $employee->birthday }}</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <small class="text-muted">BIRTH PLACE</small> <br>
                        <label
                            class="fw-bolder form-control form-control-sm  border border-primary text-primary">{{ ucwords($employee->birth_place) }}</label>
                    </div>
                </div>
            </div>
            <h6 class="mb-1 text-primary"><b>ADDRESS</b></h6>
            <div class="row">
                <div class="col-xl-9 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">HOUSE NO. / STREET / BLDG NO.</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($employee->street) }}</span>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">ZIP CODE</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($employee->zip_code) }}</span>
                    </div>
                </div>
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">BARANGAY</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($employee->barangay) }}</span>
                    </div>
                </div>
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">MUNICIPALITY</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($employee->municipality) }}</span>
                    </div>
                </div>
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">PROVINCE</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ ucwords($employee->province) }}</span>
                    </div>
                </div>
            </div>
            <h6 class="mb-1 text-primary"><b>CONTACT DETAILS</b></h6>
            <div class="row">
                <div class="col-xl-6 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">CONTACT NUMBER</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $employee->contact_number ?: 'Contact Number' }}</span>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">EMAIL</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $employee->account ? $employee->account->personal_email : 'Personal Email' }}</span>
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
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $employee->account ? ($employee->parent_details ? strtoupper($employee->parent_details->father_first_name . ' ' . $employee->parent_details->father_last_name) : 'NULL') : 'NO DATA' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">CONTACT NUMBER</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $employee->parent_details ? $employee->parent_details->father_contact_number : 'Contact Number' }}</span>
                    </div>
                </div>
            </div>
            <h6 class="mb-1 text-primary"><b>MOTHER'S INFORMATION</b></h6>
            <div class="row">
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">MOTHER'S NAME</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $employee->account ? ($employee->parent_details ? strtoupper($employee->parent_details->mother_first_name . ' ' . $employee->parent_details->mother_last_name) : 'NULL') : 'NO DATA' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">CONTACT NUMBER</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $employee->parent_details ? $employee->parent_details->mother_contact_number : 'Contact Number' }}</span>
                    </div>
                </div>
            </div>
            <h6 class="mb-1 text-primary"><b>GUARDIAN'S INFORMATION</b></h6>
            <div class="row">
                <div class="col-xl col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">GUARDIAN'S NAME</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $employee->account ? ($employee->parent_details ? strtoupper($employee->parent_details->guardian_first_name . ' ' . $employee->parent_details->guardian_last_name) : 'NULL') : 'NO DATA' }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <small class="text-muted">CONTACT NUMBER</small>
                        <span
                            class="form-control form-control-sm border border-primary text-primary fw-bolder">{{ $employee->parent_details ? $employee->parent_details->guardian_contact_number : 'Contact Number' }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
