@php
    $pageTitle = 'Payment Transaction';
    $courseColor = 'bg-secondary';
    if ($profile) {
        $courseColor = $profile->enrollment_assessment ? $profile->enrollment_assessment->color_course() : 'bg-secondary';
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
                                    {{ $profile ? ($profile->enrollment_assessment ? $profile->enrollment_assessment->course->course_name : 'COURSE') : 'COURSE' }}
                                </small> |
                                <small class="fw-bolder badge {{ $courseColor }}">
                                    {{ $profile ? ($profile->enrollment_status ? strtoupper(Auth::user()->staff->convert_year_level($profile->enrollment_status->year_level)) : 'YEAR LEVEL') : 'YEAR LEVEL' }}
                                </small>
                            </p>
                            <p class="mb-0">
                                <small class="fw-bolder badge {{ $courseColor }}">
                                    {{ $profile ? ($profile->enrollment_assessment ? $profile->enrollment_assessment->curriculum->curriculum_name : 'NO CURRICULUM') : 'CURRICULUM' }}
                                </small> |
                                <small class="fw-bolder badge bg-secondary">
                                    {{ $profile ? ($profile->scholarship_grant ? $profile->$profile->scholarship_grant->voucher->voucher_name : 'NO SCHOLARSHIP') : 'SCHOLARSHIP' }}
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
                                    @if ($profile->enrollment_assessment->year_level == '4')
                                        |
                                        <small class="fw-bolder badge bg-secondary">
                                            {{ $profile ? ($profile->enrollment_assessment ? ($profile->enrollment_assessment->bridging_program == 'with' ? 'WITH BRIDGING PROGRAM' : 'NO BRIDGING PROGRAM') : 'NO BRIDGING PROGRAM') : 'BRIDGING PROGRAM' }}
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
                <div class="card mt-3 shadow">
                    <div class="card-body">
                        <label for="" class="h4">
                            <b>
                                {{ strtoupper($profile->enrollment_status->academic->semester) }}
                                <small class="text-primary">
                                    {{ $profile->enrollment_status->academic->school_year }}</small>
                            </b>
                        </label>
                        <div class="float-end">
                            <a href="{{ route('accounting.student-card') }}?student={{ request()->input('midshipman') }}"
                                target="_blank" class="btn btn-info text-white btn-sm">STUDENT CARD</a>
                        </div>
                        @php
                            $_payment_details = $profile->enrollment_status->payment_assessments;
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
                        @if (count($profile->enrollment_status->payment_assessments->additional_fees) > 0)
                            <label class="h5 text-primary fw-bolder">
                                ADDTIONAL FEES
                            </label>
                            @foreach ($profile->enrollment_status->payment_assessments->additional_fees as $additionalFees)
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
                            @endforeach
                        @endif
                    </div>
                </div>
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
</div>
