@php
    $pageTitle = 'Payment Transaction';
    $courseColor = 'bg-secondary';
    if ($profile) {
        $courseColor = $enrollmentAssessment ? $enrollmentAssessment->color_course() : 'bg-secondary';
    }
@endphp
@section('page-title', $pageTitle)
<div>
    <div class="row">
        <div class="col-md-8">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
            <div class="card mb-2 shadow">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <img src="{{ $profile ? $profile->profile_picture() : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="card-img" alt="#">
                    </div>
                    <div class="col-md ps-0">
                        <div class="card-body p-3 me-2">
                            <label for=""
                                class="fw-bolder text-primary h4">{{ $profile ? strtoupper($profile->last_name . ', ' . $profile->first_name) : 'MIDSHIPMAN NAME' }}</label>
                            <p class="mb-0">
                                <small class="fw-bolder badge {{ $courseColor }}">
                                    {{ $profile ? ($profile->account ? $profile->account->student_number : 'NEW STUDENT') : 'STUDENT NUMBER' }}
                                </small> |
                                <small class="fw-bolder badge {{ $courseColor }}">
                                    {{ $profile ? ($enrollmentAssessment ? $enrollmentAssessment->course->course_name : 'COURSE') : 'COURSE' }}
                                </small> |
                                <small class="fw-bolder badge {{ $courseColor }}">
                                    {{ $profile ? ($enrollmentAssessment ? strtoupper(Auth::user()->staff->convert_year_level($enrollmentAssessment->year_level)) : 'YEAR LEVEL') : 'YEAR LEVEL' }}
                                </small>
                            </p>
                            <p class="mb-0">
                                <small class="fw-bolder badge {{ $courseColor }}">
                                    {{ $profile ? ($enrollmentAssessment ? $enrollmentAssessment->curriculum->curriculum_name : 'NO CURRICULUM') : 'CURRICULUM' }}
                                </small> |
                                <small class="fw-bolder badge bg-secondary">
                                    {{ $profile ? ($profile->scholarship_grant ? $profile->scholarship_grant->voucher->voucher_name : 'NO SCHOLARSHIP') : 'SCHOLARSHIP' }}
                                </small>
                                @if ($profile)
                                    @if ($profile->enrollment_application_v2)
                                        @if ($profile->enrollment_application_v2->enrollment_category == 'SBT ENROLLMENT')
                                            |
                                            <small class="fw-bolder badge bg-secondary">
                                                {{ strtoupper($profile->shipboard_training->shipping_company) }}
                                            </small>
                                        @endif
                                    @endif
                                    @if ($enrollmentAssessment->year_level == '4')
                                        |
                                        <small class="fw-bolder badge bg-secondary">
                                            {{ $profile ? ($enrollmentAssessment ? ($enrollmentAssessment->bridging_program == 'with' ? 'WITH BRIDGING PROGRAM' : 'NO BRIDGING PROGRAM') : 'NO BRIDGING PROGRAM') : 'BRIDGING PROGRAM' }}
                                        </small>
                                    @endif
                                @endif

                            </p>
                        </div>

                    </div>
                </div>
            </div>

            @if ($profile)
                {{-- Enrollment History Navbar --}}
                <nav class="nav nav-underline bg-soft-primary pb-0 text-center" aria-label="Secondary navigation">
                    <div class="dropdown mt-3 mb-2 w-100">
                        <a class=" dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            @if ($academicData)
                                <span>
                                    <span class="text-muted fw-bolder">List of Enrollment:</span>
                                    <span
                                        class="text-primary fw-bolder">{{ $academicData->semester . ' | ' . $academicData->school_year }}</span>
                                </span>
                            @endif
                        </a>

                        <ul class="dropdown-menu w-100" data-popper-placement="bottom-start">
                            @forelse ($profile->enrollment_history as $item)
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('accounting.payment-transactions-v2') }}?student={{ base64_encode($profile->id) }}{{ '&_academic=' . base64_encode($item->academic_id) }}">
                                        {{ $item->academic->semester }} |
                                        {{ $item->academic->school_year }}</a>
                                </li>
                            @empty
                                <li>
                                    No History
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </nav>
                {{-- Payment Details --}}
                <table class="nav nav-underline bg-soft-primary p-0 text-center" aria-label="Secondary navigation">
                    <thead class="d-flex">
                        <tr>
                            <td class="nav-link  {{ $activeCard == 'overview' ? 'active' : 'text-muted' }} "
                                wire:click="swtchTab('overview')">OVERVIEW</td>
                        </tr>
                        <tr>
                            <td class="nav-link  {{ $activeCard == 'transaction' ? 'active' : 'text-muted' }} "
                                wire:click="swtchTab('transaction')">TRANSACTION</td>
                        </tr>
                        <tr>
                            <td class="nav-link  {{ $activeCard == 'history' ? 'active' : 'text-muted' }} "
                                wire:click="swtchTab('history')">PAYMENT HISTORY</td>
                        </tr>
                        <tr>

                            <td class="nav-link  {{ $activeCard == 'online-payment' ? 'active' : 'text-muted' }} "
                                wire:click="swtchTab('online-payment')">ONLINE PAYMENT</td>

                        </tr>
                    </thead>
                </table>
                @if ($activeCard == 'overview')
                    <div class="card mt-3 shadow mb-2">
                        <div class="card-body">
                            <label for="" class="h4">
                                <b>
                                    {{ strtoupper($enrollmentAssessment->academic->semester) }}
                                    <small class="text-primary">
                                        {{ $enrollmentAssessment->academic->school_year }}</small>
                                </b>
                            </label>
                            <div class="float-end">
                                <a href="{{ route('accounting.assessments-v2') }}?student={{ base64_encode($profile->id) }}&reassessment=true{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}"
                                    class="badge bg-secondary me-2"> <small>RE-ASSESSMENT</small></a>
                                <a href="{{ route('accounting.student-card') }}?student={{ base64_encode($profile->id) }}"
                                    target="_blank" class="badge bg-info"><small>STUDENT CARD</small></a>
                            </div>
                            <div class=" row mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <small class="form-label">MODE OF PAYMENT</small>
                                        <br>
                                        <label class="h5 text-info form-label">
                                            {{ $paymentAssessment ? ($paymentAssessment->payment_mode == 1 ? 'INSTALLMENT' : 'FULL-PAYMENT') : '-' }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <small class="form-label">TOTAL PAYABLE:</small>
                                        <br>
                                        <label class="h5 text-primary form-label">
                                            {{ $paymentAssessment
                                                ? ($paymentAssessment->course_semestral_fee_id
                                                    ? number_format($paymentAssessment->course_semestral_fee->total_payments($paymentAssessment), 2)
                                                    : number_format($paymentAssessment->total_payment, 2))
                                                : '-' }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <small class="form-label">TOTAL PAID:</small>
                                        <br>
                                        <label class="h5 text-primary form-label">
                                            {{ $paymentAssessment ? number_format($paymentAssessment->total_paid_amount->sum('payment_amount'), 2) : '-' }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <small class="form-label">Balance:</small>
                                        <br>
                                        <label class="h5 text-danger form-label">
                                            {{ $paymentAssessment ? number_format(($paymentAssessment->course_semestral_fee_id ? $paymentAssessment->course_semestral_fee->total_payments($paymentAssessment) : $paymentAssessment->total_payment) - $paymentAssessment->total_paid_amount->sum('payment_amount'), 2) : '-' }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <label class="h5 text-primary fw-bolder">
                                ADDTIONAL FEES
                            </label>
                            <span class="badge bg-primary float-end" data-bs-toggle="modal"
                                data-bs-target=".view-modal">ADD
                                PARTICULARS</span>
                            @if (count($additional_fees) > 0)
                                @foreach ($additional_fees as $additionalFees)
                                    <div class="row p-0 mt-0 mb-0">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <small class="form-label">FEE NAME</small>
                                                <br>
                                                <label class="h5 text-info form-label">
                                                    {{ $additionalFees->fee_details->particular->particular_name }}
                                                </label>

                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <small class="form-label">FEE AMOUNT</small>
                                                <br>
                                                <label class="h5 text-primary form-label">
                                                    {{ number_format($additionalFees->fee_details->amount, 2) }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <small class="form-label">TOTAL PAID:</small>
                                                <br>
                                                <label class="h5 text-primary form-label">
                                                    {{ number_format($additionalFees->fee_total_paid(), 2) }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <small class="form-label">BALANCE:</small>
                                                <br>
                                                <label class="h5 text-danger form-label">
                                                    {{ number_format($additionalFees->fee_details->amount - $additionalFees->fee_total_paid(), 2) }}
                                                </label>
                                                @if ($additionalFees->fee_details->amount - $additionalFees->fee_total_paid() != 0)
                                                    <div class="float-end">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center flex-wrap">

                                                            <small>
                                                                <a class="badge bg-secondary"
                                                                    wire:click="addTransaction('{{ $additionalFees->fee_details->particular->particular_name }}','additional')">ADD
                                                                    TRANSACTION</a>
                                                            </small>
                                                            <small>
                                                                @if (!$additionalFees->fee_total_paid())
                                                                    <a class="badge bg-danger"
                                                                        wire:click="removeFee('{{ $additionalFees->id }}')">REMOVE
                                                                        FEE</a>
                                                                @endif
                                                            </small>
                                                        </div>


                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <br>
                                <span class="h6 text-muted">NO ADDITIONAL FEES</span>
                            @endif
                        </div>
                    </div>
                @endif
                @if ($activeCard == 'history')
                    <div class="card mt-3 shadow mb-2">
                        <div class="card-header p-3">
                            <h5 class="text-primary fw-bolder">PAYMENT HISTORY</h5>
                        </div>
                        <div class="card-body">
                            @if ($paymentAssessment)
                                @if (count($paymentAssessment->payment_transaction) > 0)
                                    @foreach ($paymentAssessment->payment_transaction as $_payment)
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
                                                    <button class="btn btn-danger btn-sm btn-form-void w-100 mt-2"
                                                        wire:click="dialogBox('{{ $_payment->id }}')">
                                                        VOID</button>
                                                @endif
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
                @endif
                @if ($activeCard == 'online-payment')
                    <div class="card mt-3 shadow mb-2">
                        <div class="card-header p-3">
                            <h5 class="text-primary fw-bolder">ONLINE PAYMENT</h5>
                        </div>
                        <div class="card-body p-2 ps-4 pe-4">
                            @forelse ($paymentAssessment->online_payment_transaction as $item)
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div>
                                        <small>REFERENCE NO: </small> <br>
                                        <span class="text-secondary fw-bolder">{{ $item->reference_number }}</span>
                                    </div>
                                    <div>
                                        <small>AMOUNT: </small> <br>
                                        <span
                                            class="text-secondary fw-bolder">{{ number_format($item->amount_paid, 2) }}</span>
                                    </div>
                                    <div>
                                        <small>TRANSACTION TYPE</small> <br>
                                        <span
                                            class="text-secondary fw-bolder">{{ ucwords(str_replace('_', ' ', $item->transaction_type)) }}</span>

                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div>
                                        <small>TRANSACTION DATE: </small> <br>
                                        <span
                                            class="text-secondary fw-bolder">{{ $item->created_at->format('d, F Y') }}</span>
                                    </div>
                                    <div>
                                        <small>PROOF OF PAYMENT: </small> <br>
                                        <a class="badge bg-primary btn-form-document w-100 mt-2"
                                            data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                            data-document-url="{{ $item->reciept_attach_path }}">
                                            VIEW</a>
                                    </div>
                                    <div>
                                        <small>PAYMENT VERIFICATION </small> <br>
                                        @if ($item->is_approved !== null)
                                            @if ($item->is_approved === 0)
                                                <span class="text-danger fw-bolder">DISAPPROVED PAYMENT <br>
                                                    <small>{{ $item->comment_remarks }}
                                                    </small>
                                                </span>
                                            @endif
                                            @if ($item->is_approved === 1)
                                                <span class="text-primary fw-bolder">APPROVED PAYMENT <br>
                                                    <small>{{ $item->or_number . ' | ' . $item->updated_at->format('F d, Y') }}
                                                    </small>
                                                </span>
                                            @endif
                                        @else
                                            <a class="badge bg-primary w-100 mt-2"
                                                wire:click="approvedPayment('{{ base64_encode($item->id) }}')">
                                                APPROVED PAYMENT</a>
                                            <br>
                                            <a class="badge bg-danger w-100 mt-2"
                                                wire:click="disapprovedDialogBox('{{ base64_encode($item->id) }}')">
                                                DISAPPROVED PAYMENT</a>
                                        @endif

                                    </div>
                                </div>
                                <hr>
                            @empty
                                <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                    <div>
                                        <span class="h6 text-muted">NO ONLINE PAYMENT TRANSACTION</span>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
                @if ($activeCard == 'transaction')
                    <div class="card mt-3 shadow mb-2">
                        <div class="card-header p-3">
                            <h5 class="text-primary fw-bolder">PAYMENT TRANSACTION</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="">PARTICULARS NAME</small> <br>
                                    <label class="h5 text-primary form-label">
                                        {{ $particularName }}
                                    </label>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <small class="form-label">TOTAL PAYABLE:</small>
                                        <br>
                                        <label class="h5 text-primary form-label">
                                            {{ $paymentAssessment
                                                ? ($paymentAssessment->course_semestral_fee_id
                                                    ? number_format($paymentAssessment->course_semestral_fee->total_payments($paymentAssessment), 2)
                                                    : number_format($paymentAssessment->total_payment, 2))
                                                : '-' }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <small class="form-label">TOTAL PAID:</small>
                                        <br>
                                        <label class="h5 text-primary form-label">
                                            {{ $paymentAssessment ? number_format($paymentAssessment->total_paid_amount->sum('payment_amount'), 2) : '-' }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <small class="form-label">Balance:</small>
                                        <br>
                                        <label class="h5 text-danger form-label">
                                            {{ $paymentAssessment ? number_format(($paymentAssessment->course_semestral_fee_id ? $paymentAssessment->course_semestral_fee->total_payments($paymentAssessment) : $paymentAssessment->total_payment) - $paymentAssessment->total_paid_amount->sum('payment_amount'), 2) : '-' }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <form wire:submit.prevent="paymentTransaction">
                                <div class="row">
                                    <div class="col-md">
                                        <small class="text-muted">REMARKS:</small>
                                        @if ($transactionStatus == 'tuition-fee')
                                            <select wire:model="transactionRemarks"
                                                class="form-select form-select-sm border border-primary">
                                                <option value="Upon Enrollment">UPON ENROLLMENT</option>
                                                <option value="1ST MONTHLY">1ST MONTHLY</option>
                                                <option value="2ND MONTHLY">2ND MONTHLY</option>
                                                <option value="3RD MONTHLY">3RD MONTHLY</option>
                                                <option value="4TH MONTHLY">4TH MONTHLY</option>
                                            </select>
                                        @else
                                            <input type="text" wire:model="transactionRemarks"
                                                class="form-control form-control-sm border border-primary">
                                        @endif

                                    </div>
                                    <div class="col-md">
                                        <small class="text-muted">PAYMENT METHOD:</small>
                                        <select wire:model="transactionPaymentMode"
                                            class="form-select form-select-sm border border-primary">
                                            <option value="CASH">CASH</option>
                                            <option value="CASH">GCASH</option>
                                            <option value="CHECK">CHECK</option>
                                            <option value="DEPOSIT SLIP">DEPOSIT SLIP</option>
                                            <option value="VOUCHER">VOUCHER</option>
                                            <option value="LOAN">LOAN</option>
                                            <option value="CREDIT CARD">CREDIT CARD</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <small class="text-muted">OR / REFERENCE NO:</small>
                                        <input type="text" wire:model="transactionOrNumber"
                                            class="form-control form-control-sm border border-primary"
                                            placeholder="0000">
                                        @error('transactionOrNumber')
                                            <small class="badge bg-danger mt-2">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">TRANSACTION DATE:</small>
                                        <input type="date" wire:model="transactionDate"
                                            class="form-control form-control-sm border border-primary">
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">AMOUNT:</small>
                                        <input type="text" wire:model="transactionAmount"
                                            class="form-control form-control-sm border border-primary"
                                            placeholder="{{ count($paymentAssessment->payment_transaction) > 0 ? number_format($paymentAssessment->monthly_payment, 2) : number_format($paymentAssessment->upon_enrollment) }}">
                                        @error('transactionAmount')
                                            <small class="badge bg-danger mt-2">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md">
                                        @if ($transactionStatus == 'tuition-fee')
                                            <small class="text-muted">SCHOLARSHIP VOUCHER:</small>
                                            <select wire:model="transactionVoucher"
                                                class="form-select form-select-sm border border-primary">
                                                @forelse ($scholarshipList as $item)
                                                    <option value="{{ $item->id }}">{{ $item->voucher_name }}
                                                    </option>
                                                @empty
                                                    <option value="">No Scholarship</option>
                                                @endforelse
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-md">
                                        <div class="float-end mt-4">
                                            <button type="submit" class="btn btn-primary btn-sm">ADD
                                                TRANSACTION</button>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                @endif

            @endif
        </div>
        <div class="col-md-4">
            <form>
                <label for="" class="text-primary fw-bolder">SEARCH STUDENT</label>
                <div class="form-group search-input">
                    <input type="search" class="form-control border border-primary" placeholder="Search..."
                        wire:model="inputStudent">
                </div>
                <div class=" d-flex justify-content-between mb-2">
                    <h6 class=" fw-bolder text-muted">
                        @if ($inputStudent != '')
                            Search Result: <span class="text-primary">{{ $inputStudent }}</span>
                        @else
                            {{ strtoupper('Recent Enrollee') }}
                        @endif
                    </h6>
                    <span class="text-muted h6">
                        No. Result: <b>{{ count($studentLists) }}</b>
                    </span>

                </div>
            </form>
            <div class="student-list">
                @forelse ($studentLists as $item)
                    <a
                        href="{{ route('accounting.payment-transactions-v2') }}?student={{ base64_encode($item->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                        <div class="card mb-2 shadow shadow-info">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="{{ $item ? $item->profile_picture() : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                                        class="avatar-100 rounded card-img" alt="student-image">
                                </div>
                                <div class="col-md p-1">
                                    <div class="card-body p-2">
                                        <small
                                            class="text-primary fw-bolder">{{ strtoupper($item->last_name . ', ' . $item->first_name) }}</small>
                                        <br>
                                        <small
                                            class="badge {{ $item->enrollment_assessment ? $item->enrollment_assessment->color_course() : 'bg-secondary' }} ">{{ $item->enrollment_assessment ? $item->enrollment_assessment->course->course_code : '-' }}</small>
                                        -
                                        <span>{{ $item->account ? $item->account->student_number : 'NEW STUDENT' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                @empty
                    <div class="card mb-2">
                        <div class="row no-gutters">
                            <div class="col-md">
                                <div class="card-body ">
                                    <small class="text-primary fw-bolder">NOT FOUND</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="modal fade view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bolder">ADD PARTICULARS FEE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>FEE NAME</td>
                                <td>FEE AMOUNT</td>
                                <td>ACTION</td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($particularFees as $item)
                                <tr>
                                    <td>{{ $item->particular->particular_name }}</td>
                                    <td>{{ number_format($item->amount, 2) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary"
                                            wire:click="addFees({{ $item->id }})" data-bs-dismiss="modal"
                                            aria-label="Close">ADD</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted">NO FEES</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade document-view-modal" id="document-view-modal" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary fw-bolder" id="exampleModalLabel1">PROOF OF PAYMENT VIEW</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <iframe class="iframe-container form-view iframe-placeholder" src="" width="100%"
                    height="600px">
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
            $('.form-view').attr('src', $(this).data('document-url'))
            console.log(file)
            /*  file = file.replace(/^.*\./, '');
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
             } */

        });
    </script>
@endsection
