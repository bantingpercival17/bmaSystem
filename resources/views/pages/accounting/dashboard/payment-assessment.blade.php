@extends('layouts.app-main')
@section('page-title', 'Payment Pending')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('registrar.dashboard') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Dashboard
        </a>

    </li>
    <li class="breadcrumb-item active" aria-current="page">
        Payment Assessment
    </li>
@endsection
@section('page-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Payment Assessment Overview</h4>
                <small class="text-muted mt-2 fw-bolder">{{ $_course->course_name }}</small>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive mt-4">
                <table id="basic-table" class="table table-striped mb-0" role="grid">
                    <thead>
                        <tr>
                            <th>STUDENT NAME</th>
                            <th>YEAR LEVEL</th>
                            <th>PAYMENT STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $_payment = request()->input('_payment') == 'true' ? $_course->payment_transaction : [];
                            $_payment = request()->input('_assessment') == 'true' ? $_course->payment_assessment : $_payment;
                        @endphp
                        @if (count($_payment))
                            @foreach ($_payment as $_payment)
                                <tr>
                                    <td>{{ $_payment->student->first_name . ' ' . $_payment->student->last_name }}</td>
                                    <td>
                                        @if ($_payment->course_id == 3)
                                            Grade {{ $_payment->year_level }}
                                        @else
                                            {{ $_payment->year_level }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($_online_enrollment = $_payment->student->enrollment_assessment->payment_assessments->online_enrollment_payment)
                                            <button type="button" class="btn btn-info text-white btn-sm btn-form-document "
                                                data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                                data-document-url="{{ $_online_enrollment->reciept_attach_path }}">
                                                VIEW</button>
                                            <a href="{{ route('accounting.payment-transactions') }}?_midshipman={{ base64_encode($_payment->student->id) }}&add-transaction=true&payment_approved={{ base64_encode($_online_enrollment->id) }}"
                                                class="btn btn-primary btn-sm">VERIFY PAYMENT</a>
                                        @else
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2">No Data</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
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
