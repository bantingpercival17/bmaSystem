@extends('layouts.app-main')
@section('page-title', 'Applicant Payment')
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>Applicant Payment
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md-4 col-lg-2">

                        <img src="{{ $_student ? '' : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="avatar-130 rounded" alt="#">
                    </div>
                    <div class="col-md col-lg">
                        <div class="card-body">
                            <h4 class="card-title text-primary">
                                <b>{{ $_student? strtoupper($_student->applicant->last_name . ', ' . $_student->applicant->first_name): 'APPLICANT NAME' }}</b>
                            </h4>
                            <p class="card-text">
                                <span>
                                    <b>
                                        {{ $_student ? $_student->applicant_number : 'APPLICANT NUMBER.' }}
                                        |
                                        {{ $_student ? $_student->course->course_name : 'COURSE' }}
                                        <br>
                                        {{ $_student ? $_student->email : 'EMAIL' }}
                                    </b>
                                </span>

                            </p>

                        </div>
                    </div>
                </div>
            </div>
            @if ($_student)
                <div class="card mt-2">
                    <div class="card-body">

                        <div class="payment-history">
                            <h5>ONLINE PAYMENT</h5>
                            <ul class="media-story mt-2 p-0">
                                @if ($_student->payments)
                                    @if (count($_student->payments) > 0)
                                        @foreach ($_student->payments as $item)
                                            <li class="d-flex  align-items-center">
                                                <div class="stories-data ">
                                                    <p class="mb-0">{{ $item->created_at->format('d, F Y') }}
                                                    </p>
                                                    <div class="row">
                                                        <div class="col-md">
                                                            <small>REFERENCE NO: </small> <br>
                                                            <h5><span
                                                                    class="text-primary">{{ $item->reference_number }}</span>
                                                            </h5>
                                                        </div>
                                                        <div class="col-md">
                                                            <small>AMOUNT: </small> <br>
                                                            <h5><span
                                                                    class="text-primary">{{ number_format($item->amount_paid, 2) }}</span>
                                                            </h5>
                                                        </div>
                                                        <div class="col-md">
                                                            <small>REFERENCE NO: </small> <br>
                                                            <h5><span
                                                                    class="text-primary">{{ ucwords(str_replace('_', ' ', $item->transaction_type)) }}</span>
                                                            </h5>
                                                        </div>
                                                        <div class="col-md-12">

                                                            <button type="button"
                                                                class="btn btn-primary btn-sm btn-form-document w-100 mt-2"
                                                                data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                                                data-document-url="{{ $item->reciept_attach_path }}">
                                                                VIEW</button>
                                                        </div>
                                                    </div>
                                                    @if ($item->is_approved === 0)
                                                        <div class="d-flex justify-content-between mt-2">
                                                            <div>
                                                                <small class="text-danger fw-bolder">DISAPPROVED </small>
                                                                <br>
                                                                <h5><span
                                                                        class="text-muted">{{ $item->comment_remarks }}</span>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    @else
                                                        @if ($item->is_approved == 1)
                                                            <div class="d-flex justify-content-between mt-2">
                                                                <div>
                                                                    <small class="text-primary fw-bolder">VERIFIED PAYMENT
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="d-flex justify-content-between mt-2">
                                                                <div>
                                                                    <form
                                                                        action="{{ route('accounting.applicant-transaction-verification') }}"
                                                                        method="get">
                                                                        <input type="hidden" name="transaction"
                                                                            value="{{ base64_encode($item->id) }}">
                                                                        <input type="hidden" name="status" value="approved">
                                                                        <button type="submit"
                                                                            class="btn btn-primary btn-sm">APPROVED
                                                                            PAYMENT</button>
                                                                    </form>
                                                                    {{-- <a href="{{ route('accounting.applicant-transaction-verification') }}?_applicant-transaction={{ base64_encode($item->id) }}"
                                                                        class="btn btn-primary btn-sm">
                                                                        APPROVED PAYMENT</a> --}}
                                                                </div>
                                                                <div class="d-flex justify-content-between ms-2">
                                                                    <form
                                                                        action="{{ route('accounting.applicant-transaction-verification') }}"
                                                                        method="get" class="">
                                                                        @csrf
                                                                        <input type="hidden" name="transaction"
                                                                            value="{{ base64_encode($item->id) }}">
                                                                        <input type="hidden" name="status"
                                                                            value="disapproveds">
                                                                        <div>
                                                                            <button type="submit"
                                                                                class="btn btn-danger btn-sm w-100">DISSAPPROVED</button>
                                                                        </div>
                                                                        <div class="mt-2">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="remarks" name="remarks"
                                                                                required>
                                                                        </div>

                                                                    </form>
                                                                </div>

                                                            </div>
                                                        @endif
                                                    @endif


                                                </div>

                                            </li>
                                            <hr>
                                        @endforeach
                                    @else
                                        <li class="d-flex mb-4 align-items-center">

                                            <div class="stories-data ms-3">
                                                <h5 class="text-muted">No Online Transaction</h5>
                                            </div>
                                        </li>
                                    @endif
                                @endif

                            </ul>

                        </div>
                        <hr>
                        {{-- <div class="payment-history">
                            <h5>PAYMENT HISTORY</h5>
                            <div class="mt-2">
                                @if ($_payment_details)
                                    @if (count($_payment_details->payment_transaction) > 0)
                                        @foreach ($_payment_details->payment_transaction as $_payment)
                                            <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">

                                                <div>
                                                    <small>PARTIAL: </small> <br>
                                                    <h5><span class="text-primary">{{ $_payment->remarks }}</span>
                                                    </h5>
                                                </div>
                                                <div>
                                                    <small>AMOUNT: </small> <br>
                                                    <h5><span
                                                            class="text-primary">{{ number_format($_payment->payment_amount, 2) }}</span>
                                                    </h5>
                                                </div>
                                                <div>
                                                    <small>OR NUMBER: </small> <br>
                                                    <h5><span class="text-primary">{{ $_payment->or_number }}</span>
                                                </div>
                                            </div>
                                            <p class="mb-0">
                                                <small>{{ $_payment->transaction_date }}</small>
                                            </p>
                                        @endforeach
                                    @else
                                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                            <div>
                                                <h5>No Payment Transaction</h5>
                                                <p></p>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                            </div>
                        </div> --}}
                    </div>
                </div>
            @endif

        </div>
        <div class="col-md-4">
            <form action="" method="get">
                @if (request()->input('search_name'))
                    <input type="hidden" name="search_name" value="{{ request()->input('search_name') }}">
                @endif
                <div class="form-group search-input">
                    <input type="search" class="form-control" placeholder="Search..." name="_applicants">
                </div>
            </form>

            @if ($_payment_transaction)
                @foreach ($_payment_transaction as $item)
                    <div class="card border-bottom border-4 border-0 border-primary">
                        <a href="?_applicant={{ base64_encode($item->id) }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span
                                            class="text-primary"><b>{{ strtoupper($item->applicant->last_name . ', ' . $item->applicant->first_name) }}</b></span>
                                    </div>
                                    <div>
                                        <span>{{ $item ? $item->applicant_number : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                    </div>
                @endforeach
            @else
                <div class="card border-bottom border-4 border-0 border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span>NO DATA</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
    <div class="modal fade document-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Online Payment View</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <iframe class="iframe-container form-view iframe-placeholder" src="" width="100%" height="600px">
                </iframe>
            </div>
        </div>
    </div>

@section('js')
    <script>
        $(document).on('click', '.btn-form-document', function(evt) {
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'webp'];
            var file = $(this).data('document-url');
            file = file.replace(/^.*\./, '');
            //console.log(fileExtension);
            // $('.image-fr').empty()
            if (fileExtension.includes(file)) {
                $(".form-view").contents().find("body").html('');
                $('.form-view').contents().find('body').append($("<img/>").attr('class', 'image-frame').attr("src",
                    $(this).data('document-url')).attr("title",
                    "sometitle").attr('width', '100%'))
                console.log(file)
            } else {
                $('.form-view').attr('src', $(this).data('document-url'))
                console.log(file)
            }

        });
    </script>
@endsection
@endsection
