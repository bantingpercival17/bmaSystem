@if (request()->input('_fill') == 'document')
    <div class="tab-content" id="pills-tabContent-2">
        <div class="tab-pane fade active show">
            @if (count($_account->empty_documents()) > 0)

                @foreach ($_account->empty_documents() as $docu)
                    @php
                        $item = $docu->applicant_document;
                        
                    @endphp
                    <div class="mt-5">
                        <div class="col-md-12">
                            <h5 class="fw-bolder text-muted">{{ $docu->document_name }}</h5>
                        </div>
                        @if ($item)
                            @if ($item->is_approved == null)
                                <form class="row" action="{{ route('document-verification') }}">
                                    <div class="col-md-8">
                                        <input type="hidden" name="_document" value="{{ base64_encode($item->id) }}">
                                        <input type="text" class="form-control form-control-sm rounded-pill mt-2"
                                            name="_comment" placeholder="Comment!" required>
                                    </div>
                                    <div class="col-md">
                                        <a href="{{ route('document-verification') }}?_document={{ base64_encode($item->id) }}&_verification_status=1"
                                            class="mt-2 btn btn-outline-primary btn-sm rounded-pill "
                                            data-bs-toggle="tooltip" title=""
                                            data-bs-original-title="Approved Document">
                                            <svg width="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path d="M8.43994 12.0002L10.8139 14.3732L15.5599 9.6272"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </a>
                                        <button type="submit" class=" mt-2 btn btn-outline-danger btn-sm rounded-pill "
                                            data-bs-toggle="tooltip" title=""
                                            data-bs-original-title="Disapprove Document">
                                            <svg width="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.3955 9.59497L9.60352 14.387" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                </path>
                                                <path d="M14.3971 14.3898L9.60107 9.59277" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                </path>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </button>
                                        <a class="btn btn-outline-info btn-sm rounded-pill btn-form-document mt-2"
                                            data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                            data-document-url="{{ json_decode($item->file_links)[0] }}"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="View Image">
                                            <svg width="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </form>
                            @endif

                            @switch($item->is_approved)
                                @case(1)
                                    <div class="row mt-2">
                                        <div class="col-md-8">
                                            <h6 class="fw-bolder text-primary">DOCUMENT APPROVED</h6>
                                            <span>
                                                <small>APPROVED DATE:</small>
                                                <span role="button" data-bs-toggle="popover" data-trigger="focus"
                                                    class="fw-bolder" title="APPROVED DETAILS"
                                                    data-bs-content="Approved By: {{ $item->staff ? $item->staff->user->name : '-' }} Approved Date: {{ $item->created_at->format('F d,Y') }}">{{ $item->created_at->format('F d,Y') }}</span>
                                            </span>
                                        </div>
                                        <div class="col-md">
                                            <a class="btn btn-outline-info btn-sm rounded-pill btn-form-document mt-2 w-100"
                                                data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                                data-document-url="{{ json_decode($item->file_links)[0] }}">
                                                <svg width="20" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                                View
                                            </a>
                                        </div>
                                    </div>
                                @break

                                @case(2)
                                    <div class="row mt-2">
                                        <div class="col-md-8">
                                            <h6 class="fw-bolder text-danger">DOCUMENT DISAPPROVED</h6>
                                            <span>
                                                <small>REMARKS: </small>
                                                <span role="button" data-bs-toggle="popover" data-trigger="focus"
                                                    class="fw-bolder" title="APPROVED DETAILS"
                                                    data-bs-content="Approved By: {{ $item->staff ? $item->staff->user->name : '-' }} Approved Date: {{ $item->created_at->format('F d,Y') }}">{{ $item->feedback }}</span>
                                            </span>
                                        </div>
                                        <div class="col-md">
                                            <a class="btn btn-outline-info btn-sm rounded-pill btn-form-document mt-2 w-100"
                                                data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                                data-document-url="{{ json_decode($item->file_links)[0] }}"
                                                data-bs-toggle="tooltip" title="" data-bs-original-title="View Image">

                                                <svg width="20" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                                View
                                            </a>
                                        </div>
                                    </div>
                                @break

                                @default
                            @endswitch
                        @else
                            <div class="row">
                                <div class="col-md-8">
                                    <p>Missing Document</p>
                                </div>
                                <div class="col-md">
                                    <a class="btn btn-outline-warning btn-sm rounded-pill btn-form-document mt-2"
                                        href="{{ route('document-notification') }}?_applicant={{ base64_encode($_account->id) }}"
                                        data-bs-toggle="tooltip" title=""
                                        data-bs-original-title="Send a Notification">
                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M15.8325 8.17463L10.109 13.9592L3.59944 9.88767C2.66675 9.30414 2.86077 7.88744 3.91572 7.57893L19.3712 3.05277C20.3373 2.76963 21.2326 3.67283 20.9456 4.642L16.3731 20.0868C16.0598 21.1432 14.6512 21.332 14.0732 20.3953L10.106 13.9602"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
            @endif
        </div>
    </div>
@elseif(request()->input('_fill') == 'entrance-examination')
    <div class="tab-content" id="pills-tabContent-2">
        <div class="tab-pane fade active show">
            <a href="{{ route('applicant-examination-log') }}?_applicant={{ base64_encode($_account->id) }}"
                class="btn btn-secondary btn-sm">Examination Log</a>
            <div class="row">
                <div class="col-md">
                    <div class="form-view">
                        <small
                            class="badge bg-info">{{ $_account->applicant_examination->updated_at->format('F d, Y') }}</small>
                        <div class="row">
                            <div class="col-md">
                                <small class="fw-bolder">SCORE</small>
                                <h3 class="text-primary fw-bolder mt-3">

                                    {{ $_account->applicant_examination->examination_result()[0] }}</h3>
                            </div>
                            <div class="col-md">
                                <small class="fw-bolder">PERCENTILE</small>
                                <h3 class="text-primary fw-bolder mt-3">

                                    {{ $_account->applicant_examination->examination_result()[1] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
             
                <div class="col-md">
                    <p>
                        <a href="{{ route('applicant-examination-reset') }}?_applicant={{ base64_encode($_account->id) }}"
                            class="">Reset Examination</a>
                    </p>
                </div>
            </div>
            @foreach (Auth::user()->roles as $role)
                @if ($role->id == 1)
                    @foreach ($_account->examination_list as $data)
                        <div class="row">
                            <div class="col-md">
                                <label for="" class="text-info">EXAMINATION STATUS</label>
                                {{ $data->is_finish }}
                                @if ($data->is_finish === 1)
                                    <span class="fw-bolder">Examination Done</span>
                                @elseif($data->is_finish === 0)
                                    <span class="fw-bolder">Examination Ongoing</span>
                                @else
                                    <span class="fw-bolder">Ready for Examination</span>
                                @endif
                            </div>
                            <div class="col-md">
                                <label for="" class="text-info">EXAMINATION CODE</label>
                                <span class="fw-bolder">{{ $data->examination_code }}</span>
                            </div>
                            <div class="col-md">
                                <label for="" class="text-info">IS REMOVE</label>
                                @if ($data->is_removed == false)
                                    <br>
                                    <a href="{{ route('examination.remove') }}?examination={{ $data->id }}"
                                        class="btn btn-sm btn-danger">remove</a>
                                @else
                                    <br>
                                    <span class="badge bg-info">REMOVED</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach

            
        </div>
    </div>
@else
    <div class="tab-content" id="pills-tabContent-2">
        <div class="tab-pane fade active show">
            <div class="form-view">
                <h6 class="mb-1"><b>FULL NAME</b></h6>
                <div class="row">
                    <div class="col-xl col-md-6 ">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Last
                                name</small>
                            <span class="form-control">{{ ucwords($_account->applicant->last_name) }}</span>
                        </div>
                    </div>
                    <div class="col-xl col-md-6 ">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">First
                                name</small>
                            <span class="form-control">{{ ucwords($_account->applicant->first_name) }}</span>
                        </div>
                    </div>
                    <div class="col-xl col-md-6 ">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Middle
                                name</small>
                            <span class="form-control">{{ ucwords($_account->applicant->middle_name) }}</span>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 ">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Extension</small>
                            <span
                                class="form-control">{{ $_account->applicant->extention_name ? ucwords($_account->applicant->extention_name) : 'none' }}</span>
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
                            <span class="form-control">{{ $_account->applicant->birthday }}</span>
                        </div>
                    </div>
                    <div class="col-xl col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Birth
                                Place</small>
                            <span class="form-control">{{ ucwords($_account->applicant->birth_place) }}</span>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xl col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Civil
                                Status</small>
                            <span class="form-control">{{ ucwords($_account->applicant->civil_status) }}</span>
                        </div>
                    </div>
                    <div class="col-xl col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Nationality</small>
                            <span class="form-control">{{ $_account->applicant->nationality }}</span>
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
                            <span class="form-control">{{ ucwords($_account->applicant->street) }}</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Barangay</small>
                            <span class="form-control">{{ ucwords($_account->applicant->barangay) }}</span>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Zip
                                Code</small>
                            <span class="form-control">{{ ucwords($_account->applicant->zip_code) }}</span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Municipality</small>
                            <span class="form-control">{{ ucwords($_account->applicant->municipality) }}</span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Province</small>
                            <span class="form-control">{{ ucwords($_account->applicant->province) }}</span>
                        </div>
                    </div>
                </div>
                <h6 class="mb-1"><b>CONTACT DETIALS</b></h6>
                <div class="row">
                    <div class="col-xl-6 col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Contact
                                Number</small>

                            <span class="form-control">{{ $_account->contact_number ?: '' }}</span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Email</small>
                            <span class="form-control">{{ $_account->email }}</span>
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

                            <span class="form-control">{{ $_account->applicant->elementary_school_name ?: '' }}</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">YEAR
                                GRADUATED</small>

                            <span class="form-control">{{ $_account->applicant->elementary_school_year ?: '' }}</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-md mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">SCHOOL
                                ADDRESS</small>
                            <span class="form-control">{{ $_account->applicant->elementary_school_address }}</span>
                        </div>
                    </div>
                </div>
                <label for="" class="form-label fw-bolder">JUNIOR HIGH SCHOOL</label>
                <div class="row">
                    <div class="col-xl-8 col-md mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">SCHOOL
                                NAME</small>

                            <span
                                class="form-control">{{ $_account->applicant->junior_high_school_name ?: '' }}</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">YEAR
                                GRADUATED</small>

                            <span
                                class="form-control">{{ $_account->applicant->junior_high_school_year ?: '' }}</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-md mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">SCHOOL
                                ADDRESS</small>
                            <span class="form-control">{{ $_account->applicant->junior_high_school_address }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @if ($_account->applicant->senior_high_school_name)
                <label for="" class="form-label fw-bolder">SENIOR HIGH SCHOOL</label>
                <div class="row">
                    <div class="col-xl-8 col-md mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">SCHOOL
                                NAME</small>

                            <span
                                class="form-control">{{ $_account->applicant->senior_high_school_name ?: '' }}</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">YEAR
                                GRADUATED</small>

                            <span
                                class="form-control">{{ $_account->applicant->senior_high_school_year ?: '' }}</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-md mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">SCHOOL
                                ADDRESS</small>
                            <span class="form-control">{{ $_account->applicant->senior_high_school_address }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
