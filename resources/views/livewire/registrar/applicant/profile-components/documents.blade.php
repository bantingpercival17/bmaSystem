<div class="card">
    @livewire('components.modal-component', ['showModal' => $showModal])
    <div class="card-body">
        @if (!$profile->not_qualified)
            <a href="{{ route('applicant.applicant-not-qualified') }}?applicant={{ base64_encode($profile->id) }}"
                class="badge bg-primary">Not Qualified</a>
        @endif

        @forelse ($profile->document_requirements() as $item)
            <div class="mt-5">
                <div class="col-md-12">
                    <h5 class="fw-bolder text-muted">{{ $item->document_name }}</h5>
                    @if ($document = $item->applicant_requirements_v2)
                        @if ($document->is_approved == null)
                            <form class="row" action="{{ route('document-verification') }}">
                                @php
                                    $comments = ['Grades shall covered the 1st and 2nd quarter of the first semester', 'Your Grade has been disapproved, please submit a clear copy of your Grades', 'Your Grade has been disapproved, please submit a complete copy of your Grades', 'Your Good Moral Conduct has been disapproved, please submit a clear copy of this documents', 'Your PSA Birth Certificate has been disapproved, please submit a clear copy of this documents', 'Your Barangay Clearance has been disapproved, please submit a clear copy of this documents', 'Your 2x2 Picture with Name Tag has been disapproved, please submit a clear copy of this documents', 'Sorry, you did not meet the required grades.'];
                                @endphp
                                <div class="col-md-8">
                                    <input type="hidden" name="_document" value="{{ base64_encode($document->id) }}">
                                    <select name="_comment" required
                                        class="form-select form-select-sm border border-primary">
                                        @foreach ($comments as $item)
                                            <option value="{{ $item }}">
                                                {{ $item }}
                                            </option>
                                        @endforeach

                                    </select>
                                    {{--  <input type="text"
                                        class="form-control form-control-sm border border-primary mt-2" name="_comment"
                                        placeholder="Comment!" required> --}}
                                </div>
                                <div class="col-md">
                                    <a href="{{ route('document-verification') }}?_document={{ base64_encode($document->id) }}&_verification_status=1"
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
                                        data-bs-toggle="tooltip" title="" data-bs-original-title="View Image"
                                        data-document-url="{{ json_decode($document->file_links)[0] }}">
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

                        @switch($document->is_approved)
                            @case(1)
                                <div class="row mt-2">
                                    <div class="col-md-8">
                                        <h6 class="fw-bolder text-primary">DOCUMENT APPROVED</h6>
                                        <span>
                                            <small>APPROVED DATE:</small>
                                            <span role="button" data-bs-toggle="popover" data-trigger="focus" class="fw-bolder"
                                                title="APPROVED DETAILS"
                                                data-bs-content="Approved By: {{ $document->staff ? $document->staff->user->name : '-' }} Approved Date: {{ $document->created_at->format('F d,Y') }}">{{ $document->created_at->format('F d,Y') }}</span>
                                        </span>
                                    </div>
                                    <div class="col-md">
                                        <a class="btn btn-outline-info btn-sm rounded-pill btn-form-document mt-2 w-100"
                                            data-bs-target=".document-view-modal"
                                            data-document-url="{{ json_decode($document->file_links)[0] }}"
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

                            @case(2)
                                <div class="row mt-2">
                                    <div class="col-md-8">
                                        <h6 class="fw-bolder text-danger">DOCUMENT DISAPPROVED</h6>
                                        <span>
                                            <small>REMARKS: </small>
                                            <span role="button" data-bs-toggle="popover" data-trigger="focus"
                                                class="fw-bolder" title="APPROVED DETAILS"
                                                data-bs-content="Approved By: {{ $document->staff ? $document->staff->user->name : '-' }} Approved Date: {{ $document->updated_at->format('F d,Y') }}">{{ $document->feedback }}</span>
                                        </span>
                                    </div>
                                    <div class="col-md">
                                        <a class="btn btn-outline-info btn-sm rounded-pill btn-form-document mt-2"
                                            data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="View Image"
                                            data-document-url="{{ json_decode($document->file_links)[0] }}">
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
                                </div>
                            @break

                            @default
                        @endswitch
                    @else
                        <div class="row">
                            <div class="col-md-10">
                                <p>Missing Document</p>
                                @if ($sent = $profile->sent_notification($item->id))
                                    <div class="row">
                                        <div class="col-md-12">
                                            <small class="text-primary fw-bolder h6">APPLICANT NOTIFIED</small>
                                        </div>
                                        <div class="col-md">
                                            <small class="text-muted fw-bolder">BY:</small>
                                            <small
                                                class="badge bg-info">{{ $sent->staff->first_name . ' ' . $sent->staff->last_name }}</small>
                                        </div>
                                        <div class="col-md">
                                            <small class="text-muted fw-bolder">DATE:</small>
                                            <small
                                                class="badge bg-info">{{ $sent->created_at->format('F d, Y') }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md">
                                <a class="btn btn-outline-warning btn-sm rounded-pill mt-2"
                                    href="{{ route('document-notification') }}?_applicant={{ base64_encode($profile->id) }}&document={{ $item->id }}"
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
            </div>
            @empty
            @endforelse
        </div>
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
                    width="100%" height="700px" >
                </iframe>
            </div>
        </div>
    </div>
    @section('script')
        <script>
            $(document).on('click', '.btn-form-document', function(evt) {
                $('.form-view').attr('src', '')
                var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'webp'];
                var file = $(this).data('document-url');
                $('.form-view').attr('src', $(this).data('document-url'))
            });

            $(document).ready(function() {
                function resizeIframe() {
                    var iframe = $('#my-iframe');
                    var contentWidth = iframe.contents().find('body').prop('scrollWidth');
                    var contentHeight = iframe.contents().find('body').prop('scrollHeight');

                    iframe.css('width', contentWidth + 'px');
                    iframe.css('height', contentHeight + 'px');
                }

                $('#my-iframe').on('load', function() {
                    resizeIframe();
                    $(this).contents().on('resize', resizeIframe);
                });
            });
        </script>
    @endsection
