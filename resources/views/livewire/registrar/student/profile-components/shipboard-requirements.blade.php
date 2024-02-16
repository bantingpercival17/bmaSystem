<div class="card">
    <div class="card-header pb-0 p-3">
        <h5 class="mb-1 text-primary"><b>PRE-DEPLOYMENT REQUIREMENTS</b></h5>
    </div>
    <div class="card-body p-3">
        @foreach ($profile->pre_onboard_documents() as $requirement)
            <div class="document-form">
                <div class="form-group">
                    <span class="fw-bolder text-info">{{ strtoupper($requirement->document_name) }}</span>
                    {{ $requirement->student_onboard_requirements }}
                    @if ($requirement->student_onboard_requirements)
                        <small class="badge bg-primary btn-form-document float-end" data-bs-toggle="modal"
                            data-document-url="{{ $requirement->student_onboard_requirements->document_path }}"
                            data-bs-target=".document-view-modal" {{-- wire:click="showDocuments('{{ json_decode($requirement->file_links) }}')"  --}} title="View Image"
                            data-bs-original-title="View Image">
                            <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                            </svg>
                        </small>
                    @endif


                </div>
                <div class="form-remarks">
                    {{ $requirement->file_links }}
                </div>
                {{-- @if ($requirement->document_status == 1)
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
                <span role="button" data-bs-toggle="popover" data-trigger="focus" class="fw-bolder" title="APPROVED DETAILS" data-bs-content="Approved By: {{ $requirement->staff ? $requirement->staff->user->name : '-' }} Verified Date: {{ $requirement->updated_at->format('F d,Y') }}">{{ $requirement->document_comment }}</span>
            </span>
            @else
            <div class="form-group">
                <form class="row" action="{{ route('onboard.midshipman-shipboard-application') }}">
                    <div class="col-md-9">
                        <input type="hidden" name="_document" value="{{ base64_encode($requirement->id) }}">
                        <input type="text" class="form-control form-control-sm rounded-pill mt-2" name="_comment" placeholder="Comment!" required="">
                    </div>
                    <div class="col-md">
                        <a href="{{ route('onboard.midshipman-shipboard-application') . '?_document=' . base64_encode($requirement->id) . '&document_status=1' }}" class="mt-2 btn btn-outline-primary btn-sm rounded-pill " data-bs-toggle="tooltip" title="" data-bs-original-title="Approved Document">
                            <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M8.43994 12.0002L10.8139 14.3732L15.5599 9.6272" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                        <button type="submit" class=" mt-2 btn btn-outline-danger btn-sm rounded-pill " data-bs-toggle="tooltip" title="" data-bs-original-title="Disapprove Document">
                            <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.3955 9.59497L9.60352 14.387" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path d="M14.3971 14.3898L9.60107 9.59277" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>

                    </div>
                </form>
            </div>
            @endif --}}
            </div>

            <hr>
        @endforeach

    </div>
    <div class="modal fade document-view-modal" id="document-view-modal" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary fw-bolder" id="exampleModalLabel1">DOCUMENT'S REVIEW</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <iframe id="my-iframe" class="iframe-container form-view iframe-placeholder" src=""
                    width="100%" height="700px">
                </iframe>
            </div>
        </div>
    </div>
</div>
@section('script')
    <script>
        $(document).on('click', '.btn-form-document', function(evt) {
            $('.form-view').attr('src', '')
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'webp'];
            var file = $(this).data('document-url');
            console.log(file)
            $('.form-view').attr('src', $(this).data('document-url'))
            $('.form-view').css('width', '300px');
        });
    </script>
@endsection
