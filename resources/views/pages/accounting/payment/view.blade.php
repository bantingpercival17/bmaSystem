@extends('layouts.app-main')
@section('page-title', 'Payment Transaction')
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>Payment Transaction
    </li>
@endsection
@section('page-content')

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <!-- <img src="{{ $_student ? $_student->profile_pic($_student->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="card-img" alt="#"> -->
                    </div>
                    <div class="col-md ps-0">
                        <div class="card-body p-3 me-2">
                            <label for=""
                                class="fw-bolder text-primary h4">{{ $_student ? strtoupper($_student->last_name . ', ' . $_student->first_name) : 'MIDSHIPMAN NAME' }}</label>
                            <p class="mb-0">
                                <small class="fw-bolder badge bg-secondary">
                                    {{ $_student ? ($_student->account ? $_student->account->student_number : 'STUDENT NO.') : 'NEW STUDENT' }}
                                </small> |
                                <small class="fw-bolder badge bg-secondary">
                                    {{ $_student ? ($_student->enrollment_status ? strtoupper(Auth::user()->staff->convert_year_level($_student->enrollment_status->year_level)) : 'YEAR LEVEL') : 'YEAR LEVEL' }}
                                </small> |
                                <small class="fw-bolder badge bg-secondary">
                                    {{ $_student ? ($_student->enrollment_status ? $_student->enrollment_status->course->course_name : 'COURSE') : 'COURSE' }}
                                </small>
                            </p>
                            <div class="row mt-0">

                                <div class="col-md">
                                    <small class="fw-bolder text-muted">CURRICULUM:</small> <br>
                                    <small class="badge bg-primary">
                                        {{ $_student ? ($_student->enrollment_status ? strtoupper($_student->enrollment_status->curriculum->curriculum_name) : 'CURRICULUM') : 'CURRICULUM' }}
                                    </small>
                                </div>
                                <div class="col-md">
                                    <small class="fw-bolder text-muted">SECTION:</small> <br>
                                    <small class="badge bg-primary">
                                        {{ $_student ? ($_student->enrollment_status ? strtoupper($_student->enrollment_status->academic->semester . ' | ' . $_student->enrollment_status->academic->school_year) : 'SECTION') : 'SECTION' }}
                                    </small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            @if ($_student)
                <nav class="nav nav-underline bg-soft-primary pb-0 text-center" aria-label="Secondary navigation">
                    <div class="dropdown mt-3 mb-2 w-100">
                        <a class=" dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <span class="text-muted">Academic Year :</span>
                            <b>{{ request()->input('_academic') ? Auth::user()->staff->current_academic()->semester : $_student->enrollment_status->academic->semester }}
                                |
                                {{ request()->input('_academic') ? Auth::user()->staff->current_academic()->school_year : $_student->enrollment_status->academic->school_year }}</b>
                        </a>
                        {{-- {{ $_student->enrollment_history }} --}}
                        <ul class="dropdown-menu w-100" data-popper-placement="bottom-start">
                            @php
                                $_url = request()->is('accounting/particular/fee*') ? route('accounting.particular-fee-view') : '';
                            @endphp
                            @if ($_student->enrollment_history->count() > 0)
                                @foreach ($_student->enrollment_history as $_enrollment)
                                    <li>
                                        <a class="dropdown-item "
                                            href="{{ $_url }}?_academic={{ base64_encode($_enrollment->academic_id) }}&midshipman={{ request()->input('midshipman') }}{{ request()->is('accounting/particular/fee*') ? '&_department=' . request()->input('_department') : '' }}">
                                            {{ $_enrollment->academic->semester }} |
                                            {{ $_enrollment->academic->school_year }}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </nav>
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="">
                            <label for="" class="h4">
                                <b>
                                    {{ strtoupper($_student->enrollment_status->academic->semester) }}
                                    <small class="text-primary">
                                        {{ $_student->enrollment_status->academic->school_year }}</small>
                                </b>
                            </label>
                            <div class="float-end">
                                <a href="{{ route('accounting.student-card') }}?student={{ request()->input('midshipman') }}"
                                    target="_blank" class="btn btn-info text-white btn-sm">STUDENT CARD</a>
                            </div>
                            @php
                                $_payment_details = $_student->enrollment_status->payment_assessments;
                            @endphp
                            <div class=" row mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <small class="form-label">Mode of Payment:</small>
                                        <br>
                                        <label class="h5 text-info form-label">
                                            {{ $_payment_details ? ($_payment_details->payment_mode == 1 ? 'INSTALLMENT' : 'FULL-PAYMENT') : '-' }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <small class="form-label">Total Payable:</small>
                                        <br>
                                        <label class="h5 text-primary form-label">
                                            {{ $_payment_details
                                                ? ($_payment_details->course_semestral_fee_id
                                                    ? number_format($_payment_details->course_semestral_fee->total_payments($_payment_details), 2)
                                                    : number_format($_payment_details->total_payment, 2))
                                                : '-' }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <small class="form-label">Total Paid:</small>
                                        <br>
                                        <label class="h5 text-primary form-label">
                                            {{ $_payment_details ? number_format($_payment_details->total_paid_amount->sum('payment_amount'), 2) : '-' }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <small class="form-label">Balance:</small>
                                        <br>
                                        <label class="h5 text-danger form-label">
                                            {{ $_payment_details ? number_format(($_payment_details->course_semestral_fee_id ? $_payment_details->course_semestral_fee->total_payments($_payment_details) : $_payment_details->total_payment) - $_payment_details->total_paid_amount->sum('payment_amount'), 2) : '-' }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @if (request()->input('add-transaction') == true)
                            <div class="payment-transaction">
                                <form action="{{ route('accounting.payment-transaction') }}" method="post">
                                    <h5>PAYMENT TRANSACTION</h5>

                                    @if (request()->input('payment_approved'))
                                        <input type="hidden" name="_online_payment" value="{{ $_online_payment->id }}">
                                    @endif
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for=""><small>PAYMENT AMOUNT</small></label>
                                                <input type="hidden" name="_assessment"
                                                    value="{{ $_payment_details->id }}">
                                                <input type="text" class="form-control" name="_payment"
                                                    id="input-payment"
                                                    value=" {{ $_payment_details ? ($_payment_details->course_semestral_fee_id ? number_format($_payment_details->course_semestral_fee->payment_amount($_payment_details), 2) : number_format($_payment_details->total_paid_amount, 2)) : '-' }} ">
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <label class="form-label"><small>REMARKS:</small></label>
                                            <select name="remarks" class="form-select">
                                                <option value="Upon Enrollment">UPON ENROLLMENT</option>
                                                <option value="1ST MONTHLY">1ST MONTHLY</option>
                                                <option value="2ND MONTHLY">2ND MONTHLY</option>
                                                <option value="3RD MONTHLY">3RD MONTHLY</option>
                                                <option value="4TH MONTHLY">4TH MONTHLY</option>
                                                <option value="Tuition Fee">TUITION FEE</option>
                                                <option value="UNIFORM">UNIFORM</option>
                                                <option value="GRADUATION FEE">GRADUATION FEE</option>
                                                <option value="TABLET FEE">TABLET FEE</option>
                                            </select>
                                        </div>
                                        <div class="col-md">
                                            <label for="" class="form-label"><small>PAYMENT
                                                    METHOD:</small></label>
                                            <select name="payment_method" class="form-select">
                                                <option value="CASH">CASH</option>
                                                <option value="CASH">GCASH</option>
                                                <option value="CHECK">CHECK</option>
                                                <option value="DEPOSIT SLIP">DEPOSIT SLIP</option>
                                                <option value="VOUCHER">VOUCHER</option>
                                                <option value="LOAN">LOAN</option>
                                                <option value="CREDIT CARD">CREDIT CARD</option>
                                                <option value="OVER-PAYMENT">OVER-PAYMENT</option>
                                                <option value="DAND SCHOLAR">BAND SCHOLAR</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="" class="form-label"><small>OR / REFERENCE
                                                        NO.:</small></label>
                                                <input type="text" class="form-control" name="or_number">
                                                @error('or_number')
                                                    <span class="badge bg-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="" class="form-label"><small>TRANSACTION
                                                        DATE</small></label>
                                                <input type="date" class="form-control" name="tran_date"
                                                    {{ request()->input('payment_approved') ? 'value=' . $_online_payment->created_at . '' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="" class="form-label"><small>VOUCHER:</small></label>
                                            <select name="voucher" class="form-select">
                                                <option value="" selected>No Voucher</option>
                                                @if (count($_vouchers) > 0)
                                                    @foreach ($_vouchers as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->voucher_name }} - {{ $item->voucher_amount }}
                                                        </option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <label for="" class="form-label"><small>AMOUNT:</small></label>
                                                <input type="text" class="form-control" name="amount"
                                                    {{ request()->input('payment_approved') ? 'value=' . $_online_payment->amount_paid . '' : '' }}>
                                                @error('or_number')
                                                    <span class="badge bg-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <button type="submit" class="btn btn-primary w-100">ADD TRANSACTION</button>
                                    </div>
                                </form>

                            </div>
                        @else
                            <div class="row">
                                <div class="col-md">
                                    <a href="{{ route('accounting.assessments') }}?midshipman={{ request()->input('midshipman') }}&reassessment=true"
                                        class="btn btn-outline-primary w-100">RE-ASSESS TUITION FEE</a>
                                </div>
                                <div class="col-md">
                                    <a href="{{ route('accounting.payment-transactions') }}?midshipman={{ request()->input('midshipman') }}&add-transaction=true{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}"
                                        class="btn btn-outline-primary w-100">
                                        ADD TRANSACTION</a>
                                </div>
                            </div>


                        @endif
                        <hr>

                        <div class="online-payment-transaction">
                            <h5 class="text-primary fw-bolder">ONLINE PAYMENT TRANSACTION </h5>
                            @if ($_payment_details)
                                @if (count($_payment_details->online_payment_transaction) > 0)
                                    @foreach ($_payment_details->online_payment_transaction as $item)
                                        <div class="payment-details">
                                            <p class="mb-0">
                                                <small>{{ $item->created_at->format('d, F Y') }}</small>
                                                @if ($item->is_approved !== 1)
                                                    <small class="float-end badge bg-danger btn-remove"
                                                        data-url="{{ route('accounting.online-payment-transaction-remove') . '?transaction=' . base64_encode($item->id) }}">
                                                        REMOVE TRANSACTION
                                                    </small>
                                                @endif

                                            </p>
                                            <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">

                                                <div>
                                                    <small>REFERENCE NO: </small> <br>
                                                    <span
                                                        class="text-secondary fw-bolder">{{ $item->reference_number }}</span>
                                                </div>
                                                <div>
                                                    <small>AMOUNT: </small> <br>
                                                    <span
                                                        class="text-secondary fw-bolder">{{ number_format($item->amount_paid, 2) }}</span>
                                                </div>
                                                {{-- <div>
                                                    <small>TRANSACTION DATE: </small> <br>
                                                    <span class="text-secondary fw-bolder">{{ $item->transaction_date }}</span>

                                                </div> --}}
                                                <div>
                                                    <small>TRANSACTION TYPE</small> <br>
                                                    <span
                                                        class="text-secondary fw-bolder">{{ ucwords(str_replace('_', ' ', $item->transaction_type)) }}</span>

                                                </div>
                                                <div>
                                                    <small>PROOF OF PAYMENT: </small> <br>
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm btn-form-document w-100 mt-2"
                                                        data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                                        data-document-url="{{ str_replace('bma.edu.ph', '20.0.0.120', $item->reciept_attach_path) }}">
                                                        VIEW</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md">
                                                    @if ($item->is_approved === 0)
                                                        <div class="payment-verification">
                                                            <span class="text-secondary fw-bolder">PAYMENT
                                                                VERIFICATION</span>
                                                            <div>
                                                                <small class="text-danger fw-bolder">DISAPPROVED </small>
                                                                <br>
                                                                <h5><span
                                                                        class="text-muted">{{ $item->comment_remarks }}</span>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    @else
                                                        @if ($item->is_approved === 1)
                                                            <div class="payment-verification">
                                                                {{-- <span class="text-secondary fw-bolder fw-bolder">VERIFIED
                                                                    PAYMENT</span> --}}
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                                                    <div>
                                                                        <small>OR NUMBER: </small> <br>
                                                                        <span
                                                                            class="text-secondary fw-bolder">{{ $item->or_number }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <small>VERIFIED DATE </small> <br>
                                                                        {{ $item->staff_id }}
                                                                        <span
                                                                            class="text-secondary fw-bolder">{{ $item->updated_at->format('F d, Y') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="payment-verification">
                                                                <span class="text-secondary fw-bolder">PAYMENT
                                                                    VERIFICATION</span>
                                                                <div class="row">
                                                                    <div class="col-md">

                                                                        <a href="{{ route('accounting.payment-transactions') }}?midshipman={{ request()->input('midshipman') }}&add-transaction=true&payment_approved={{ base64_encode($item->id) }}"
                                                                            class="btn btn-primary btn-sm">
                                                                            APPROVED PAYMENT</a>
                                                                    </div>
                                                                    <div class="col-md">
                                                                        <form
                                                                            action="{{ route('accounting.online-payment-disapproved') }}"
                                                                            method="post" class="form-group">
                                                                            @csrf
                                                                            <input type="hidden" name="_online_payment"
                                                                                value="{{ $item->id }}">

                                                                            <div class="mt-2">
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="remarks" name="remarks"
                                                                                    required>
                                                                            </div>
                                                                            <div>
                                                                                <button type="submit"
                                                                                    class="btn btn-outline-danger btn-sm w-100">DISAPPROVED</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>

                                            </div>



                                        </div>
                                    @endforeach
                                @else
                                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                        <div>
                                            <h6 class="text-muted">No Online Payment Transaction</h6>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                    <div>
                                        <h6 class="text-muted">No Payment Details</h6>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <hr>
                        <div class="additional-payment">
                            <h5 class="text-primary fw-bolder">ONLINE ADDITIONAL PAYMENT</h5>
                            @if ($_student->enrollment_status)
                                @if ($_student->enrollment_status->additional_payment)
                                    @if (count($_student->enrollment_status->additional_payment) > 0)
                                        @foreach ($_student->enrollment_status->additional_payment as $item)
                                            <div class="payment-details">
                                                <p class="mb-0">
                                                    <small>{{ $item->created_at->format('d, F Y') }}</small>
                                                </p>
                                                <div
                                                    class="d-flex justify-content-between align-items-center flex-wrap mb-2">

                                                    <div>
                                                        <small>REFERENCE NO: </small> <br>
                                                        <span class="text-primary h5">{{ $item->reference_number }}</span>
                                                    </div>
                                                    <div>
                                                        <small>AMOUNT: </small> <br>
                                                        <span
                                                            class="text-primary h5">{{ number_format($item->amount_paid, 2) }}</span>
                                                    </div>
                                                    <div>
                                                        <small>TRANSACTION DATE: </small> <br>
                                                        <span class="text-primary h5">{{ $item->transaction_date }}</span>

                                                    </div>
                                                    <div>
                                                        <small>TRANSACTION TYPE</small> <br>
                                                        <span
                                                            class="text-primary h5">{{ ucwords(str_replace('_', ' ', $item->transaction_type)) }}</span>

                                                    </div>
                                                </div>
                                                <div
                                                    class="d-flex justify-content-between align-items-center flex-wrap mb-2">

                                                    <div>
                                                        <small>PROOF OF PAYMENT: </small> <br>
                                                        <button type="button"
                                                            class="btn btn-primary btn-sm btn-form-document w-100 mt-2"
                                                            data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                                            data-document-url="{{ $item->reciept_attach_path }}">
                                                            VIEW</button>
                                                        <a href="{{ $item->reciept_attach_path }}"
                                                            class="btn btn-outline-primary btn-sm"
                                                            target="_blank">view</a>
                                                    </div>
                                                </div>
                                                @if ($item->is_approved === 0)
                                                    <div class="payment-verification">
                                                        <span class="text-secondary fw-bolder">PAYMENT
                                                            VERIFICATION</span>
                                                        <div>
                                                            <small class="text-danger fw-bolder">DISAPPROVED </small>
                                                            <br>
                                                            <h5><span
                                                                    class="text-muted">{{ $item->comment_remarks }}</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if ($item->is_approved === 1)
                                                        <div class="payment-verification">
                                                            <span class="text-primary fw-bolder">VERIFIED
                                                                PAYMENT</span>
                                                            <div
                                                                class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                                                <div>
                                                                    <small>OR NUMBER: </small> <br>
                                                                    <span
                                                                        class="text-primary h5">{{ $item->or_number }}</span>
                                                                </div>
                                                                <div>
                                                                    <small>VERIFIED DATE </small> <br>
                                                                    {{ $item->staff_id }}
                                                                    <span
                                                                        class="text-primary h5">{{ $item->updated_at->format('F d, Y') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="payment-verification">
                                                            <span class="text-secondary fw-bolder">PAYMENT
                                                                VERIFICATION</span>
                                                            <div class="row">
                                                                <div class="col-md">
                                                                    <form
                                                                        action="{{ route('accounting.online-additional-payment-approved') }}"
                                                                        method="post" class="form-group">
                                                                        @csrf
                                                                        <input type="hidden" name="_online_payment"
                                                                            value="{{ $item->id }}">

                                                                        <div class="mt-2">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Or Number" name="or_number"
                                                                                required>
                                                                        </div>
                                                                        <div>
                                                                            <button type="submit"
                                                                                class="btn btn-outline-primary btn-sm w-100">APPROVED</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="col-md">
                                                                    <form
                                                                        action="{{ route('accounting.online-additional-payment-disapproved') }}"
                                                                        method="post" class="form-group">
                                                                        @csrf
                                                                        <input type="hidden" name="_online_payment"
                                                                            value="{{ $item->id }}">

                                                                        <div class="mt-2">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="remarks" name="remarks"
                                                                                required>
                                                                        </div>
                                                                        <div>
                                                                            <button type="submit"
                                                                                class="btn btn-outline-danger btn-sm w-100">DISAPPROVED</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif


                                            </div>

                                            <hr>
                                        @endforeach
                                    @else
                                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                            <div>
                                                <h6 class="text-muted">No Additional Online Payment Transaction</h6>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                            @endif
                            @if ($_payment_details)
                            @else
                            @endif


                        </div>
                        <hr>
                        <div class="payment-history">
                            <h5 class="text-primary fw-bolder">PAYMENT HISTORY</h5>
                            <div class="mt-2">
                                @if ($_payment_details)
                                    @if (count($_payment_details->payment_transaction) > 0)
                                        @foreach ($_payment_details->payment_transaction as $_payment)
                                            <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">

                                                <div>
                                                    <small>PARTIAL: </small> <br>
                                                    <a href="{{ route('accounting.print-reciept') }}?reciept={{ base64_encode($_payment->id) }}"
                                                        target="_blank">
                                                        <h5><span
                                                                class="text-primary fw-bolder">{{ $_payment->remarks }}</span>
                                                        </h5>
                                                    </a>

                                                </div>
                                                <div>
                                                    <small>AMOUNT: </small> <br>
                                                    <h5><span
                                                            class="text-secondary fw-bolder">{{ number_format($_payment->payment_amount, 2) }}</span>
                                                    </h5>
                                                </div>
                                                <div>
                                                    <small>OR NUMBER: </small> <br>
                                                    <h5><span
                                                            class="text-secondary fw-bolder">{{ $_payment->or_number }}</span>
                                                </div>
                                                <div>
                                                    @if ($_payment->payment_void)
                                                        @if ($_payment->payment_void->is_approved)
                                                        @else
                                                            <span class="badge bg-info">Void Pending</span>
                                                        @endif
                                                    @else
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm btn-form-void w-100 mt-2"
                                                            data-bs-toggle="modal" data-bs-target=".void-view-modal"
                                                            data-void-payment="{{ base64_encode($_payment->id) }}">
                                                            VOID</button>
                                                    @endif

                                                    {{-- <button class="btn btn-danger btn-sm">VOID</button> --}}
                                                </div>
                                            </div>
                                            <p class="mb-0">
                                                <small>{{ $_payment->staff->user->name }}</small> |
                                                <small>{{ $_payment->created_at }}</small>
                                            </p>
                                        @endforeach
                                    @else
                                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                            <div>
                                                <h5>No Payment Transaction</h5>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <div class="col-md-4">
            <form action="" method="get">

                <div class="form-group search-input">
                    <input type="search" class="form-control" placeholder="Search..." name="_students">
                </div>
                <div class="row form-group">

                    <div class="col-md">
                        <a href="?_payment_category=additional-payment" class="btn btn-outline-primary btn-sm">Additional
                            Payment</a>
                    </div>
                </div>
            </form>
            <div class=" d-flex justify-content-between mb-2">
                <h6 class=" fw-bolder text-muted">
                    {{ request()->input('_student') ? 'Search Result: ' . request()->input('_student') : 'Recent Payment' }}
                </h6>
                <span class="text-primary h6">
                    No. Result: <b>{{ count($_students) }}</b>
                </span>

            </div>
            @if ($_students)
                @if (!request()->input('_students'))
                    <div class="mb-3">
                        {{ $_students->links() }}
                    </div>
                @endif


                @foreach ($_students as $item)
                    <div class="card mb-2">
                        <a
                            href="?midshipman={{ base64_encode($item->id) }}{{ request()->input('_payment_category') ? '&_payment_category=' . request()->input('_payment_category') : '' }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                            <div class="row no-gutters">
                                <div class="col-md-4">

                                   <!--  <img src="{{ $item ? $item->profile_pic($item->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                                        class="avatar-100 rounded card-img" alt="student-image"> -->
                                </div>
                                <div class="col-md p-1">
                                    <div class="card-body p-2">
                                        <small
                                            class="text-primary fw-bolder">{{ strtoupper($item->last_name . ', ' . $item->first_name) }}</small>
                                        <br>
                                        @php
                                            if ($item->enrollment_assessment) {
                                                $_course_color = $item->enrollment_assessment->course_id == 1 ? 'bg-info' : '';
                                                $_course_color = $item->enrollment_assessment->course_id == 2 ? 'bg-primary' : $_course_color;
                                                $_course_color = $item->enrollment_assessment->course_id == 3 ? 'bg-warning text-white' : $_course_color;
                                            } else {
                                                $_course_color = 'text-muted';
                                            }
                                            
                                            //echo $_student->enrollment_assessment->course_id;
                                            
                                        @endphp
                                        <small
                                            class="badge {{ $_course_color }}">{{ $item->enrollment_assessment ? $item->enrollment_assessment->course->course_code : '-' }}</small>
                                        -
                                        <span>{{ $item->account ? $item->account->student_number : '' }}</span>

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
                <iframe class="iframe-container form-view iframe-placeholder" src="" width="100%"
                    height="600px">
                </iframe>
            </div>
        </div>
    </div>
    <div class="modal fade void-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bolder">Online Payment View</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('accounting.transaction-void') }}" method="post">
                        @csrf
                        <input type="hidden" name="payment" class="modal-payment">
                        <div class="form-group">
                            <small for="" class="form-label fw-bolder">VOID REASON</small>
                            <input type="text" class="form-control" name="reason">
                        </div>
                        <button class="btn btn-primary btn-sm w-100">REQUEST VOID</button>
                    </form>
                </div>
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
                var type = $(this).data('type'),
                    image = '';
                console.log(type)
                if (type == '_bridging_program') {
                    url = $(this).data('document-url')
                    console.log(url)
                } else {
                    image = $(this).data('document-url');
                }
                $('.form-view').contents().find('body').append($("<img/>").attr('class', 'image-frame').attr("src",
                    image).attr("title",
                    "sometitle").attr('width', '100%'))
                console.log(file)
            } else {
                $('.form-view').attr('src', $(this).data('document-url'))
                console.log(file)
            }

        });
        $('.btn-remove').click(function(event) {
            Swal.fire({
                title: 'Payment Transaction ',
                text: "Do you want to remove?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var _url = $(this).data('url');
                if (result.isConfirmed) {
                    window.location.href = _url
                }
            })
            event.preventDefault();
        })
        $('.btn-form-void').click(function() {
            $('.modal-payment').val($(this).data('void-payment'))
            //alert($(this).data('void-payment'));
        })
    </script>
@endsection
@endsection
