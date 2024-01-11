<div class="card">
    <div class="card-header pb-0 p-3">
        <h5 class="mb-1"><b>SHIPBOARD APPLICATION'S</b></h5>
        <p class="text-sm">Midshipman Shipboard Training Application</p>
    </div>
    <div class="card-body p-3">
        @forelse ($profile->shipboard_information as $item)
            <h6 class="mb-1 text-primary"><b>SHIPPING DETAILS</b></h6>
            {{-- {{ $item }} --}}
            <div class="form-view">
                <div class="row">
                    <div class="form-group col-md">
                        <label class="form-label-sm"><small>COMPANY NAME</small></label>
                        <br>
                        <label
                            class="text-primary form-control form-control-sm border border-primary"><b>{{ $item->company_name }}</b></label>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label-sm"><small>VESSEL TYPE</small></label>
                        <br>
                        <label
                            class="text-primary form-control form-control-sm border border-primary"><b>{{ $item->vessel_type }}</b></label>

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md">
                        <label class="form-label-sm"><small>VESSEL NAME</small></label>
                        <br>
                        <label class="text-primary form-control form-control-sm border border-primary">
                            <b>{{ $item->vessel_name }}</b>
                        </label>
                    </div>
                    <div class="form-group col-md">
                        <label class="form-label-sm"><small>OBT BATCH</small></label>
                        <br>
                        <label
                            class="text-primary form-control form-control-sm border border-primary"><b>{{ $item->sbt_batch }}</b></label>

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md">
                        <label class="form-label-sm"><small>SEA EXPERIENCE</small></label>
                        <br>
                        <label
                            class="text-primary form-control form-control-sm border border-primary"><b>{{ strtoupper($item->shipping_company) }}</b></label>
                    </div>
                    <div class="form-group col-md">
                        <label class="form-label-sm"><small>STATUS</small></label>
                        <br>
                        <label class="text-primary form-control form-control-sm border border-primary">
                            <b> {{ strtoupper($item->shipboard_status) }}
                            </b>
                        </label>
                    </div>
                    <div class="form-group col-md">
                        <label class="form-label-sm"><small>DATE OF EMBARKED</small></label>
                        <br>
                        <label
                            class="text-primary form-control form-control-sm border border-primary"><b>{{ $item->embarked }}</b></label>
                    </div>
                </div>
            </div>
            <div class="documents-view">
                <h6 class="mb-1 text-primary fw-bolder">DOCUMENT REQUIREMENTS</h6>
                @foreach ($item->document_requirements as $requirement)
                    <div class="form-group">

                        <span class="fw-bolder text-secondary">{{ strtoupper($requirement->documents->document_name) }}
                        </span>
                        <small class="btn btn-outline-primary btn-xs btn-form-document btn-round float-end" data-bs-toggle="modal"
                            data-bs-target=".document-view-modal"
                            wire:click="showDocuments('{{ $requirement->file_path }}')" title=""
                            data-bs-original-title="View Image">
                           View Document
                        </small>
                        {{--  <small class="badge bg-primary btn-form-document float-left" data-bs-toggle="modal"
                            data-bs-target=".document-view-modal" data-document-url="{{ $requirement->file_path }}"
                            title="" data-bs-original-title="View Image">
                            <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </small> --}}
                        @if ($requirement->document_status == 1)
                            <br>
                            <small class="fw-bolder text-primary">DOCUMENT APPROVED</small>
                            <br>
                            <span>
                                <small>APPROVED DATE:</small>
                                <small role="button" data-bs-toggle="popover" data-trigger="focus" class="fw-bolder"
                                    title="APPROVED DETAILS"
                                    data-bs-content="Approved By: {{ $requirement->staff ? $requirement->staff->user->name : '-' }} Approved Date: {{ $requirement->updated_at->format('F d,Y') }}">{{ $requirement->updated_at->format('F d,Y') }}</small>
                            </span>
                        @elseif($requirement->document_status == 2)
                            <br>
                            <small class="fw-bolder text-danger">DOCUMENT DISAPPROVED</small>
                            <br>
                            <span>
                                <small>REMARKS: </small>
                                <span role="button" data-bs-toggle="popover" data-trigger="focus" class="fw-bolder"
                                    title="APPROVED DETAILS"
                                    data-bs-content="Approved By: {{ $requirement->staff ? $requirement->staff->user->name : '-' }} Verified Date: {{ $requirement->updated_at->format('F d,Y') }}">{{ $requirement->document_comment }}</span>
                            </span>
                        @else
                            <div class="form-group">
                                <form class="row" action="{{ route('onboard.midshipman-shipboard-application') }}">
                                    <div class="col-md-9">
                                        <input type="hidden" name="_document"
                                            value="{{ base64_encode($requirement->id) }}">
                                        <input type="text" class="form-control form-control-sm rounded-pill mt-2"
                                            name="_comment" placeholder="Comment!" required="">
                                    </div>
                                    <div class="col-md">
                                        <a href="{{ route('onboard.midshipman-shipboard-application') . '?_document=' . base64_encode($requirement->id) . '&document_status=1' }}"
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
                                        <button type="submit"
                                            class=" mt-2 btn btn-outline-danger btn-sm rounded-pill "
                                            data-bs-toggle="tooltip" title=""
                                            data-bs-original-title="Disapprove Document">
                                            <svg width="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.3955 9.59497L9.60352 14.387" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                </path>
                                                <path d="M14.3971 14.3898L9.60107 9.59277" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                </path>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </button>

                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <hr>
        @empty
            <h6 class="mb-1 text-info"><b>NO APPLICATION</b></h6>
        @endforelse

    </div>
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="modal fade show"
                style="display: block;   background-color: rgb(0 0 0 / 77%);
            width: 100%;
            height: 100%;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header p-3">
                            <h5 class="fw-bolder text-primary" id="exampleModalLabel1">Document Review</h5>
                            <button type="button" class="btn-close" wire:click="hideDocuments">
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ $documentLink }}" style=" width: 100%; " alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
