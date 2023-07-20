@php
    $pageTitle = 'Assessment Fee';
@endphp
@section('page-title', $pageTitle)
<div>
    <div class="row">
        <div class="col-md-8">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
            <div class="card mb-2 shadow shadow-primary">
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
                                <small class="fw-bolder badge bg-secondary">
                                    {{ $profile ? ($profile->account ? $profile->account->student_number : 'NEW STUDENT') : 'STUDENT NUMBER' }}
                                </small> -

                                <small
                                    class="fw-bolder badge {{ $profile ? ($profile->enrollment_status ? $profile->enrollment_status->color_course() : 'bg-secondary') : 'bg-secondary' }}">
                                    {{ $profile ? ($profile->enrollment_status ? $profile->enrollment_status->course->course_name : 'COURSE') : 'COURSE' }}
                                </small>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
            @if ($profile)
                <div class="card shadow">
                    <div class="card-header p-3">
                        <h5 class="header-text"><b class="text-primary">ENROLLMENT ASSESSMENT DETAILS</b>
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="enrollment-details">
                            <div class="row">
                                <div class="form-group col-md">
                                    <small class="fw-bolder text-muted">COURSE</small>
                                    <label for=""
                                        class="form-control form-control-sm border border-info">{{ $profile->enrollment_status ? $profile->enrollment_status->course->course_name : 'NO COURSE' }}</label>
                                </div>
                                <div class="form-group col-md">
                                    <small class="fw-bolder text-muted">ACADEMIC YEAR</small>
                                    <label for=""
                                        class="form-control form-control-sm border border-info">{{ $profile->enrollment_status ? $profile->enrollment_status->academic->school_year . '-' . $profile->enrollment_status->academic->semester : 'NO COURSE' }}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md">
                                    <small class="fw-bolder text-muted">YEAR LEVEL</small>
                                    <label for=""
                                        class="form-control form-control-sm border border-info">{{ $profile->enrollment_status ? Auth::user()->staff->convert_year_level($profile->enrollment_status->year_level) : 'NO COURSE' }}</label>
                                </div>
                                <div class="form-group col-md">
                                    <small class="fw-bolder text-muted">CURRICULUM</small>
                                    <label for=""
                                        class="form-control form-control-sm border border-info">{{ $profile->enrollment_status ? $profile->enrollment_status->curriculum->curriculum_name : 'NO CURRICULUM' }}</label>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-md">
                                    <small class="fw-bolder text-muted">SCHOLARSHIP GRANT</small>
                                    <label for=""
                                        class="form-control form-control-sm border border-info">{{ $profile->scholarship_grant ? $profile->$profile->scholarship_grant->voucher->voucher_name : 'NO SCHOLARSHIP' }}</label>
                                </div>
                                <div class="form-group col-md">
                                    <small class="fw-bolder text-muted">BRIDGING PROGRAM</small>
                                    <label for=""
                                        class="form-control form-control-sm border border-info">{{ $profile->enrollment_status ? strtoupper($profile->enrollment_status->bridging_program) : 'NO BRIDGING PROGRAM' }}</label>
                                </div>
                                @if ($profile->enrollment_application_v2)
                                    @if ($profile->enrollment_application_v2->enrollment_category == 'SBT ENROLLMENT')
                                        <div class="form-group col-md">
                                            <small class="text-muted fw-bolder">SEA EXPERIENCE</small>
                                            <label for=""
                                                class="form-control form-control-sm border border-info">{{ strtoupper($profile->shipboard_training->shipping_company) }}</label>
                                        </div>
                                    @endif

                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-header p-3">
                        <h5 class="header-text"><b class="text-primary">PAYMENT ASSESSMENT DETAILS </b>
                        </h5>
                    </div>
                    <div class="card-body p-5">
                        <form wire:submit.prevent="storeAssessment">
                            <h6 class="text-info fw-bolder">TOTAL TUITION FEE</h6>
                            <div class="row">
                                <div class="form-group col-md">
                                    <small class="form-label">FEE NAME</small>
                                    <br>
                                    <label class="h5 text-primary form-label">
                                        TUITION FEE
                                    </label>
                                </div>
                                <div class="form-group col-md">
                                    <small class="form-label">FEE AMOUNT</small>
                                    <br>
                                    <label class="h5 text-primary form-label">
                                        @if ($tuition_fees)
                                            {{ number_format($tuition_fees['fee_amount'], 2) }}
                                        @else
                                            0.00
                                        @endif
                                    </label>
                                </div>
                                <div class="form-group col-md">
                                    <small class="form-label">UPON ENROLLMENT</small>
                                    <br>
                                    <label class="h5 text-primary form-label">
                                        @if ($tuition_fees)
                                            {{ number_format($tuition_fees['upon_enrollment'], 2) }}
                                        @else
                                            0.00
                                        @endif
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md">
                                    <small class="form-label">MONTHLY FEE</small>
                                    <br>
                                    <label class="h5 text-primary form-label">
                                        @if ($tuition_fees)
                                            {{ number_format($tuition_fees['monthly'], 2) }}
                                        @else
                                            0.00
                                        @endif
                                    </label>
                                </div>
                                <div class="form-group col-md">
                                    <small class="form-label">TOTAL FEES</small>
                                    <br>
                                    <label class="h5 text-primary form-label">
                                        @if ($tuition_fees)
                                            {{ number_format($tuition_fees['total_fees'], 2) }}
                                        @else
                                            0.00
                                        @endif
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <small class="form-label">PAYMENT MODE</small>
                                    <br>
                                    <div class="col-sm">
                                        <select name="mode" wire:model="paymentMode"
                                            class="form-select form-select-sm payment-mode">
                                            <option value="0">Fullpayment</option>
                                            <option value="1">Installment</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <span class="badge bg-primary float-end" data-bs-toggle="modal"
                                data-bs-target=".view-modal">ADD
                                ADDITIONAL FEES</span>
                            <h6 class="text-info fw-bolder">ADDITIONAL
                                FEES</h6>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>FEE NAME</td>
                                        <td>FEE AMOUNT</td>
                                        <td>ACTION</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($particularLists as $item)
                                        <tr>
                                            <td class="text-muted">
                                                {{ $item['particular']['particular_name'] }}
                                            </td>
                                            <td>{{ number_format($item['amount'], 2) }}</td>
                                            <td></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-muted">NO FEES</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <hr>
                            <h6 class="text-info fw-bolder">SCHOLARSHIP GRANT</h6>
                            <div class="row">
                                <div class="form-group col-md">
                                    <small class="fw-bolder text-muted">SCHOLARSHIP GRANT</small>
                                    <label for=""
                                        class="form-control form-control-sm border border-info">{{ $profile->scholarship_grant ? $profile->$profile->scholarship_grant->voucher->voucher_name : 'NO SCHOLARSHIP' }}</label>
                                </div>
                            </div>
                            <hr>
                            <h6 class="text-info fw-bolder">FORWARDED OVER-PAYMENT</h6>
                            <div class="row">
                                <div class="form-group col-md">
                                    <small class="fw-bolder text-muted">FORWARDED AMOUNT</small>
                                    <label for=""
                                        class="form-control form-control-sm border border-info">{{ number_format($profile->enrollment_assessment->over_payment() * -1, 2) }}</label>
                                </div>
                            </div>
                            <div class="row">
                                {{-- <div class="form-group col-md-8">
                                    <small class="form-label">TOTAL AMOUNT</small>
                                    <br>
                                    <label class="h5 text-primary form-label">
                                        {{ number_format($totalSemestralFees, 2) }}
                                    </label>
                                </div> --}}
                                <div class="form-group col-md">
                                    <button type="submit" class="btn btn-primary float-end">SUBMIT</button>
                                </div>

                            </div>

                        </form>

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
                    <a href="{{ route('accounting.assessments-v2') }}?student={{ base64_encode($item->id) }}">
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
                                            class="badge {{ $item->enrollment_status ? $item->enrollment_status->color_course() : 'bg-secondary' }} ">{{ $item->enrollment_status ? $item->enrollment_status->course->course_code : '-' }}</small>
                                        -
                                        <span>{{ $item->account ? $item->account->student_number : 'NEW STUDENT' }}</span>
                                        <div>
                                            <span
                                                class="text-danger">{{ $item->enrollment_application_payment ? ($item->enrollment_application_payment->payment_mode === 0 ? 'FULL-PAYMENT' : ($item->enrollment_application_payment->payment_mode === 1 || $item->enrollment_application_payment->payment_mode === 2 ? 'INSTALLMENT' : '-')) : '-' }}</span>
                                        </div>
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
