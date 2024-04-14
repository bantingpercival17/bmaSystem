@forelse ($profile->shipboard_information  as $key => $item)
    <div class="card">
        <div class="card-header ">
            <div class="float-end">
                <span class="badge bg-primary"> {{ strtoupper($item->shipboard_status) }}</span>
            </div>
            <h5 class="mb-1 text-primary"><b>{{ strtoupper($item->vessel_name) }}</b></h5>
            <small class="text-muted fw-bolder">{{ strtoupper($item->company_name) }}</small>

        </div>
        <nav class=" nav nav-underline bg-soft-primary pb-0 text-center " aria-label="Secondary navigation">

            <div class="d-flex" id="head-check">
                <a class="nav-link {{ $subCardContent == 'card-content-mr-' . $key ? 'active' : 'text-muted' }}"
                    wire:click="subCardSwtich('{{ 'card-content-mr-' . $key }}')">
                    MONITORING REPORT
                </a>
                <a class="nav-link {{ $subCardContent == 'card-content-si-' . $key ? 'active' : 'text-muted' }}"
                    wire:click="subCardSwtich('{{ 'card-content-si-' . $key }}')">
                    SHIPBOARD INFORMATION
                </a>
                <a class="nav-link {{ $subCardContent == 'card-content-sd-' . $key ? 'active' : 'text-muted' }}"
                    wire:click="subCardSwtich('{{ 'card-content-sd-' . $key }}')">
                    SHIPBOARD DOCUMENTS
                </a>

            </div>
        </nav>
        <div class="card-body">
            @if ($subCardContent == 'card-content-si-' . $key)
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
            @endif
            @if ($subCardContent == 'card-content-sd-' . $key)
                <div class="documents-view">
                    <h6 class="mb-1 text-primary fw-bolder">DOCUMENT REQUIREMENTS</h6>
                    @foreach ($item->document_requirements as $requirement)
                        <div class="form-group">

                            <span
                                class="fw-bolder text-secondary">{{ strtoupper($requirement->documents->document_name) }}
                            </span>
                            <small class="btn btn-outline-primary btn-xs btn-form-document btn-round float-end"
                                data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                wire:click="showDocuments('{{ $requirement->file_path }}')" title=""
                                data-bs-original-title="View Image">
                                View Document
                            </small>
                            @if ($requirement->document_status == 1)
                                <br>
                                <small class="fw-bolder text-primary">DOCUMENT APPROVED</small>
                                <br>
                                <span>
                                    <small>APPROVED DATE:</small>
                                    <small role="button" data-bs-toggle="popover" data-trigger="focus"
                                        class="fw-bolder" title="APPROVED DETAILS"
                                        data-bs-content="Approved By: {{ $requirement->staff ? $requirement->staff->user->name : '-' }} Approved Date: {{ $requirement->updated_at->format('F d,Y') }}">{{ $requirement->updated_at->format('F d,Y') }}</small>
                                </span>
                                <div class="form-group">
                                    <form class="row"
                                        action="{{ route('onboard.midshipman-shipboard-application') }}">
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
                                                        stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </button>

                                        </div>
                                    </form>
                                </div>
                            @elseif($requirement->document_status == 2)
                                <br>
                                <small class="fw-bolder text-danger">DOCUMENT DISAPPROVED</small>
                                <br>
                                <span>
                                    <small>REMARKS: </small>
                                    <span role="button" data-bs-toggle="popover" data-trigger="focus"
                                        class="fw-bolder" title="APPROVED DETAILS"
                                        data-bs-content="Approved By: {{ $requirement->staff ? $requirement->staff->user->name : '-' }} Verified Date: {{ $requirement->updated_at->format('F d,Y') }}">{{ $requirement->document_comment }}</span>
                                </span>
                            @else
                                <div class="form-group">
                                    <form class="row"
                                        action="{{ route('onboard.midshipman-shipboard-application') }}">
                                        <div class="col-md-9">
                                            <input type="hidden" name="_document"
                                                value="{{ base64_encode($requirement->id) }}">
                                            <input type="text"
                                                class="form-control form-control-sm rounded-pill mt-2" name="_comment"
                                                placeholder="Comment!" required="">
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
                                                        stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M8.43994 12.0002L10.8139 14.3732L15.5599 9.6272"
                                                        stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
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
                                                        stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </button>

                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
            @if ($subCardContent == 'card-content-mr-' . $key)
                <div class="header-title ">
                    <a href="{{ route('onboard.narative-summary-report-v2') . '?_midshipman=' . base64_encode($profile->id) }}"
                        class="badge bg-primary float-end" target="_blank">GENERATE REPORT</a>
                    <span class="h5 text-primary fw-bolder">MONTHLY OBT PERFORMANCE MONITORING REPORT
                        (MOPM)</span>


                </div>
                <div class="table-responsive mt-4">
                    <table id="basic-table" class="table table-striped mb-0" role="grid"
                        data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>Narrative Report</th>
                                <th>Progress</th>
                                <th>Summary Report</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($item->performance_report) > 0)
                                @foreach ($item->performance_report as $report)
                                    <tr>
                                        <td>
                                            <a
                                                href=" {{ route('onboard.performance-report') }}?report={{ base64_encode($report->id) }}&midshipman={{ base64_encode($profile->id) }}">
                                                {{ date('F - Y', strtotime($report->month)) }}
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $percentage = 0;
                                                if ($report->document_attachments->count() > 0) {
                                                    $percentage =
                                                        ($report->approved_document_attachments->count() /
                                                            $report->document_attachments->count()) *
                                                        100;
                                                }
                                            @endphp
                                            <div class="d-flex align-items-center mb-2">
                                                <h6>{{ $percentage }}%
                                                </h6>
                                            </div>
                                            <div class="progress bg-soft-info shadow-none w-100" style="height: 6px">
                                                <div class="progress-bar bg-info" data-toggle="progress-bar"
                                                    role="progressbar" aria-valuenow="{{ $percentage }}"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($report->document_attachments->count() > 0)
                                                @if ($report->approved_document_attachments->count() === $report->document_attachments->count())
                                                    <a href="{{ route('onboard.narative-report-monthly-summary-v2') . '?_midshipman=' . base64_encode($profile->id) . '&narativeReport=' . base64_encode($report->id) }}"
                                                        class="btn btn-primary btn-sm" target="_blank">VIEW</a>
                                                @endif
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">NO MONTHLY REPORT</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@empty
    <div class="card">
        <div class="card-header">
            <h6 class="mb-1 text-info"><b>NO APPLICATION</b></h6>
        </div>
    </div>
@endforelse
