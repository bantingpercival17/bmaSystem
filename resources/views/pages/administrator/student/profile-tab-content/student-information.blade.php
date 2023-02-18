<div class="card">
    <div class="card-header pb-0 p-3">
        </p>
        <a href="{{ route('admin.student-qrcode') }}?_student={{ base64_encode($_student->id) }}" class="btn btn-primary btn-sm float-end">GENERATE QR-CODE</a>
        <a href="{{ route('registrar.student-application-view') }}?_student={{ base64_encode($_student->id) }}" class="btn btn-primary btn-sm float-end">FORM RG-01</a>
        <h5 class="mb-1"><b>PROFILE INFORMATION</b></h5>
        <p class="text-sm">Student Information of the cadet's/ student's at Baliwag Maritime Academy

    </div>
    <div class="card-body p-3">

        <div class="form-view">
            <h6 class="mb-1"><b>FULL NAME</b></h6>
            <div class="row">
                <div class="col-md">
                    <small class="text-muted fw-bolder">LAST NAME</small> <br>
                    <label for="" class="form-control fw-bolder">{{ ucwords($_student->last_name) }}</label>
                </div>
                <div class="col-md">
                    <small class="text-muted fw-bolder">FIRST NAME</small> <br>
                    <label for="" class="form-control fw-bolder">{{ ucwords($_student->first_name) }}</label>
                </div>
                <div class="col-md">
                    <small class="text-muted fw-bolder">MIDDLE NAME</small> <br>
                    <label for="" class="form-control fw-bolder">{{ ucwords($_student->middle_name) }}</label>
                </div>
                <div class="col-md-3">
                    <small class="text-muted fw-bolder">EXTENSION</small> <br>
                    <label for="" class="form-control fw-bolder">{{ $_student->extention_name ? ucwords($_student->extention_name) : '' }}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <small class="text-muted fw-bolder">GENDER</small> <br>
                        <label class="fw-bolder form-control">{{ ucwords($_student->sex) }}</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <small class="text-muted fw-bolder">CIVIL STATUS</small> <br>
                        <label class="fw-bolder form-control">{{ ucwords($_student->civil_status) }}</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <small class="text-muted fw-bolder">NATIONALITY</small> <br>
                        <label class="fw-bolder form-control">{{ $_student->nationality }}</label>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <small class="text-muted fw-bolder">Birthday</small> <br>
                        <label class="fw-bolder form-control">{{ $_student->birthday }}</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <small class="text-muted fw-bolder">Birth
                            Place</small> <br>
                        <label class="fw-bolder form-control">{{ ucwords($_student->birth_place) }}</label>
                    </div>
                </div>
            </div>
            <h6 class="mb-1"><b>ADDRESS</b></h6>
            <div class="row">
                <div class="col-xl-5 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Hous no /
                            Street /
                            Bldg
                            no</label>
                        <span class="form-control">{{ ucwords($_student->street) }}</span>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Barangay</label>
                        <span class="form-control">{{ ucwords($_student->barangay) }}</span>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Zip
                            Code</label>
                        <span class="form-control">{{ ucwords($_student->zip_code) }}</span>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Municipality</label>
                        <span class="form-control">{{ ucwords($_student->municipality) }}</span>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Province</label>
                        <span class="form-control">{{ ucwords($_student->province) }}</span>
                    </div>
                </div>
            </div>
            <h6 class="mb-1"><b>CONTACT DETIALS</b></h6>
            <div class="row">
                <div class="col-xl-6 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Contact
                            Number</label>

                        <span class="form-control">{{ $_student->contact_number ?: 'Contact Number' }}</span>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 mb-xl-0">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Email</label>
                        <span class="form-control">{{ $_student->account ? $_student->account->personal_email : 'Personal Email' }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>