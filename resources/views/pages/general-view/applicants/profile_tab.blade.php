@if (request()->input('_fill')=='document')
    <div class="tab-content" id="pills-tabContent-2">
        <div class="tab-pane fade active show">
            @if (count($_account->applicant_documents) > 0)
                @foreach ($_account->applicant_documents as $item)
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center message-icon me-3">
                                <span
                                    class="ms-1 fw-bolder">{{ $item->document->document_name }}</span>
                            </div>
                            <div class="">
                                <a class="badge bg-primary btn-form-document col"
                                    data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                    data-document-url="{{ json_decode($item->file_links)[0] }}">
                                    view
                                </a>
                            </div>
                        </div>
                        @if ($_account->document_history($item->document_id)->count() > 0)
                            <div class="history">
                                <a tabindex="0" class="badge bg-secondary text-white" role="button"
                                    data-bs-toggle="popover" data-trigger="focus"
                                    title="List of Dissapproved Requirments"
                                    data-bs-content="We have {{ $_account->document_history($item->document_id)->count() }} disapproved Document/s">Document
                                    History</a>
                            </div>
                        @endif

                    </div>
                    @if ($item->is_approved === 1)
                        <span class="fw-bolder text-primary">DOCUMENT APPROVED</span>
                        <div class="row">
                            <div class="col-md"><small><i>VERIFIED
                                        BY:</i></small>
                                <b>{{ $item->staff ? $item->staff->user->name : '-' }}</b>
                            </div>
                            <div class="col-md"><small><i>VERIFIED DATE:</i></small>
                                {{ $item->created_at->format('F d,Y') }}</div>
                        </div>
                        @endif 
                        @if ($item->is_approved === 2)
                            <span class="fw-bolder text-danger">DOCUMENT DISAPPROVED</span><br>
                            <span class="text-muted"><i>Remarks: </i>
                                <b> {{ $item->feedback }}</b></span>
                            <div class="row">
                                <div class="col-md"><small><i>VERIFIED
                                            BY:</i></small>
                                    <b>{{ $item->staff ? $item->staff->user->name : '-' }}</b>
                                </div>
                                <div class="col-md"><small><i>VERIFIED DATE:</i></small>
                                    {{ $item->created_at->format('F d,Y') }}</div>
                            </div>
                        @endif
                        @if ($item->is_approved === null)
                            <form class="comment-text d-flex align-items-center mt-3"
                                action="{{ route('document-verification') }}">
                                <input type="hidden" name="_document"
                                    value="{{ base64_encode($item->id) }}">
                                <input type="hidden" name="_verification_status" value="0">
                                <input type="text" class="form-control rounded-pill"
                                    name="_comment" placeholder="Comment!">
                                <div class="comment-attagement d-flex">
                                    <button type="submit"
                                        class=" me-2 btn btn-danger btn-sm rounded-pill">DISAPPROVE
                                    </button>
                                    <a href="{{ route('document-verification') }}?_document={{ base64_encode($item->id) }}&_verification_status=1"
                                        class="me btn btn-primary btn-sm rounded-pill">
                                        APPROVE
                                    </a>
                                </div>
                            </form>
                        @endif

                        <hr>
                    @endforeach
                @else
                    <div class="mt-5">
                        No Attach Requirement. <a
                            href="{{ route('document-notification') }}?_applicant={{ base64_encode($_account->id) }}"
                            class="badge bg-info">click here</a> to
                        notify the applicant.
                        @foreach ($_account->empty_documents() as $item)
                            <div
                                class="d-flex flex-wrap justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center message-icon me-3">
                                        <span
                                            class="ms-1 fw-bolder">{{ $item->document_name }}</span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                @endif
        </div>
    </div>

@elseif(request()->input('_fill')=='entrance-examination')
<div class="tab-content" id="pills-tabContent-2">
    <div class="tab-pane fade active show">
        <div class="form-view">
            {{$_account->examination}}
        </div>
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
                            <span
                                class="form-control">{{ ucwords($_account->applicant->last_name) }}</span>
                        </div>
                    </div>
                    <div class="col-xl col-md-6 ">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">First
                                name</small>
                            <span
                                class="form-control">{{ ucwords($_account->applicant->first_name) }}</span>
                        </div>
                    </div>
                    <div class="col-xl col-md-6 ">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Middle
                                name</small>
                            <span
                                class="form-control">{{ ucwords($_account->applicant->middle_name) }}</span>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 ">
                        <div class="form-group">
                            <small for="example-text-input"
                                class="form-control-label">Extension</small>
                            <span
                                class="form-control">{{ $_account->applicant->extention_name ? ucwords($_account->applicant->extention_name) : 'none' }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input"
                                class="form-control-label">Gender</small>
                            <span class="form-control">{{ ucwords('male') }}</span>
                        </div>
                    </div>
                    <div class="col-xl col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input"
                                class="form-control-label">Birthday</small>
                            <span
                                class="form-control">{{ $_account->applicant->birthday }}</span>
                        </div>
                    </div>
                    <div class="col-xl col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Birth
                                Place</small>
                            <span
                                class="form-control">{{ ucwords($_account->applicant->birth_place) }}</span>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xl col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Civil
                                Status</small>
                            <span
                                class="form-control">{{ ucwords($_account->applicant->civil_status) }}</span>
                        </div>
                    </div>
                    <div class="col-xl col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input"
                                class="form-control-label">Nationality</small>
                            <span
                                class="form-control">{{ $_account->applicant->nationality }}</span>
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
                            <span
                                class="form-control">{{ ucwords($_account->applicant->street) }}</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input"
                                class="form-control-label">Barangay</small>
                            <span
                                class="form-control">{{ ucwords($_account->applicant->barangay) }}</span>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Zip
                                Code</small>
                            <span
                                class="form-control">{{ ucwords($_account->applicant->zip_code) }}</span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input"
                                class="form-control-label">Municipality</small>
                            <span
                                class="form-control">{{ ucwords($_account->applicant->municipality) }}</span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input"
                                class="form-control-label">Province</small>
                            <span
                                class="form-control">{{ ucwords($_account->applicant->province) }}</span>
                        </div>
                    </div>
                </div>
                <h6 class="mb-1"><b>CONTACT DETIALS</b></h6>
                <div class="row">
                    <div class="col-xl-6 col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">Contact
                                Number</small>

                            <span
                                class="form-control">{{ $_account->contact_number ?: '' }}</span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input"
                                class="form-control-label">Email</small>
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

                            <span
                                class="form-control">{{ $_account->applicant->elementary_school_name ?: '' }}</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">YEAR
                                GRADUATED</small>

                            <span
                                class="form-control">{{ $_account->applicant->elementary_school_year ?: '' }}</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-md mb-xl-0">
                        <div class="form-group">
                            <small for="example-text-input" class="form-control-label">SCHOOL
                                ADDRESS</small>
                            <span
                                class="form-control">{{ $_account->applicant->elementary_school_address }}</span>
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
                            <span
                                class="form-control">{{ $_account->applicant->junior_high_school_address }}</span>
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
                            <span
                                class="form-control">{{ $_account->applicant->senior_high_school_address }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif