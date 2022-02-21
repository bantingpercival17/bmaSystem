@extends('layouts.app-main')
@section('page-title', 'Assessment Fee')
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>Assessment Fee
    </li>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            var mode = $('.payment-mode').val();
            computation(mode)
        });
        $('.payment-mode').change(function() {
            var mode = $(this).val();
            computation(mode)
        })


        function computation(mode) {
            if (mode == 1) {
                console.log('Installment')
                var _tuition_fee = parseFloat($('.tuition-fee').text().replace(/,/g, ''))
                _computetion = $('.course').val() == 3 ? computetion_of_senior(_tuition_fee) :
                    computetion_of_college(_tuition_fee)
                console.log(_computetion)
                console.log(_computetion.total_tuition_fee)
                $('.final-tuition').text(_computetion.total_tuition_fee.toFixed(2).toLocaleString())
                $('.upon-enrollment').text(_computetion.upon_enrollment.toFixed(2).toLocaleString())
                $('.monthly-fee').text(_computetion.monthly.toFixed(2).toLocaleString())
            } else {
                var _tuition_fee = $('.tuition-fee').text()
                $('.final-tuition').text(_tuition_fee)
                $('.upon-enrollment').text(_tuition_fee)
                $('.monthly-fee').text('-')
                console.log('full')
            }
        }

        function computetion_of_senior(_tuition_fee) {
            _interest = 710; // This interest in Static Value
            _tuition_fee += _interest // Total Tuition Fee with Books
            var _init_tuition = parseInt($('#tuition_tags').val()) + _interest; // uition and Miscellaneous Fee
            _upon_enrollment = /* Get the 20% of Tuition and Miscellaneous */
                (_init_tuition * 0.20) + (_tuition_fee - _init_tuition)
            /* Subtract the total TFee and Additional Fee to the TFee and Miscellaneous */
            _monthly_fee = (_tuition_fee - _upon_enrollment) / 4
            return {
                "total_tuition_fee": _tuition_fee,
                'upon_enrollment': _upon_enrollment,
                'monthly': _monthly_fee
            };
        }

        function computetion_of_college(_tuition_fee) {
            console.log('College')
            total_fee = 0;
            console.log(_tuition_fee)
            _intest = (_tuition_fee * 0.035)

            console.log("Payment Interest: " + _intest);
            _total_fee = parseFloat(_tuition_fee) + parseFloat(_intest)
            _upon_enrollment = parseFloat(_tuition_fee) * 0.2
            _monthly_fee = (_total_fee - _upon_enrollment) / 4;
            return {
                "total_tuition_fee": _total_fee,
                'upon_enrollment': _upon_enrollment,
                'monthly': _monthly_fee
            };
        }
    </script>
@endsection
@section('page-content')

    @php
    $_assessment = $_student ? $_student->enrollment_assessment : [];
    $_total_fee = 0;
    $_monthly_fee = ['1ST MONTHLY', '2ND MONTHLY', '3RD MONTHLY', '4TH MONTHLY'];
    @endphp
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md-4 col-lg-2">

                        <img src="{{ $_student? $_student->profile_pic($_student->account): 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="avatar-130 rounded" alt="#">
                    </div>
                    <div class="col-md col-lg">
                        <div class="card-body">
                            <h4 class="card-title text-primary">
                                <b>{{ $_student ? strtoupper($_student->last_name . ', ' . $_student->first_name) : 'MIDSHIPMAN NAME' }}</b>
                            </h4>
                            <p class="card-text">
                                <span>STUDENT NUMBER: <b>
                                        {{ $_student ? $_student->account->student_number : '-' }}</b></span>
                            </p>

                        </div>
                    </div>
                </div>
                <div class="row p-3">
                    <span class="text-primary"><b>| ENROLLMENT DETAILS</b></span>

                    <div class="row">
                        <div class="col-md">
                            <label for="" class="form-label"><small><b>COURSE / STRAND</b></small>:</label>
                            <label for=""
                                class="text-primary"><b>{{ $_assessment ? $_assessment->course->course_name : '-' }}</b></label>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label"><small><b>YEAR LEVEL</b></small>:</label>
                            <label for=""
                                class="text-primary"><b>{{ $_assessment? ($_assessment->course->id != 3? $_assessment->year_level . ' CLASS': 'GRADE ' . $_assessment->year_level): '-' }}</b></label>

                        </div>
                        <div class="col-md-12">
                            <label for="" class="form-label"><small><b>ACADEMIC YEAR</b></small>: </label>
                            <label class="text-primary">
                                <b>{{ $_assessment ? $_assessment->academic->semester . ' | ' . $_assessment->academic->school_year : '-' }}
                                </b>
                            </label>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="header-text"><b class="text-primary">PAYMENT ASSESSMENT</b></h5>
                </div>
                <div class="card-body">
                    @if ($_assessment)
                        <form class="form-assessments-view" role="form"
                            action="{{ route('accounting.payment-assessment') }}" method="post">
                            @csrf
                            <input type="hidden" name="enrollment" value="{{ $_assessment ? $_assessment->id : '' }}">
                            <input type="hidden" name="_student" value="{{ base64_encode($_student->id) }}">
                            <input type="hidden" name="semestral_fees"
                                value="{{ $_semestral_fees ? $_assessment->course_semestral_fees($_assessment)->id : '' }}">
                            <span class="text-primary h5"><b>| TERMS OF PAYMENT</b></span>
                            <div class="row">
                                @if ($_assessment)
                                    @if ($_assessment->course_id != 3)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <span class="text-muted"><b>BRIDGING PROGRAM :</b></span>
                                                <label for=""
                                                    class="form-control">{{ $_assessment->bridging_program == 'with' ? 'WITH BRIDGING' : 'WITHOUT BRIDGING' }}</label>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                <div class="col-md">
                                    <div class="form-group">
                                        <input type="hidden" class="course"
                                            value="{{ $_assessment->course_id }}">
                                        <span class="text-muted"><b>MODE :</b></span>
                                        <div class="col-sm">
                                            <select name="mode" class="form-select payment-mode">
                                                <option value="0"
                                                    {{ $_student->enrollment_application->payment_mode == 0 ? 'selected' : '' }}>
                                                    Fullpayment</option>
                                                <option value="1"
                                                    {{ $_student->enrollment_application->payment_mode == 2 || $_student->enrollment_application->payment_mode == 1? 'selected': '' }}>
                                                    Installment</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="text-primary h5"><b>| PAYMENT DETAILS</b></span>
                            <div class="row">
                                <div class="col-md">
                                    <label for="" class=""><b>PARTICULARS</b></label>
                                    @if (count($_semestral_fees) > 0)
                                        {{ $_course_semestral_fee->particular_tags('tuition_tags') }}
                                        @foreach ($_semestral_fees as $item)
                                            <div class="row">
                                                <div class="col-md">
                                                    <span class="mt-2 badge bg-info">
                                                        {{ ucwords(str_replace(['_', 'tags'], [' ', 'Fee'], $item->particular_tag)) }}</span>

                                                </div>
                                                <div class="col-md-4 ">
                                                    <span class="mt-2 float-end">
                                                        @php
                                                            $_particular_amount = $_assessment->course->id == 3 ? $item->fees : $_course_semestral_fee->particular_tags($item->particular_tag);
                                                            
                                                            $_total_fee += $_particular_amount;
                                                        @endphp
                                                        <b> {{ number_format($_particular_amount, 2) }}</b>
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                        <input type="hidden" id="tuition_tags" value="{{ $_total_fee }}">
                                        @if ($_assessment->course_id == 3)
                                            @foreach ($_course_semestral_fee->additional_fees($_course_semestral_fee->id) as $item)
                                                <div class="row">
                                                    <div class="col-md">
                                                        <span class="mt-2 badge bg-success">
                                                            {{ ucwords(str_replace(['_', 'tags'], [' ', 'Fee'], $item->particular_name)) }}</span>

                                                    </div>
                                                    <div class="col-md-4 ">
                                                        <span class="mt-2 float-end">
                                                            @php
                                                                
                                                                $_total_fee += $item->particular_amount;
                                                            @endphp
                                                            <b> {{ number_format($item->particular_amount, 2) }}</b>
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @else
                                        <div class="row">
                                            <div class="col-md">
                                                <span class="mt-2 badge bg-info">
                                                    Please Setup the Tuition Fee
                                                </span>

                                            </div>
                                            <div class="col-md-4 ">
                                                <span class="mt-2 ">
                                                    <a href="{{ route('accounting.fees') }}">
                                                        <span class="mt-2 badge bg-primary">
                                                            click here
                                                        </span>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md">
                                            <span class="mt-2 badge bg-info">
                                                Total Tution Fees</span>

                                        </div>
                                        <div class="col-md-4 ">
                                            <span class="mt-2 float-end">
                                                <b class="tuition-fee"> {{ number_format($_total_fee, 2) }}</b>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <span class="text-muted h6"><b>SCHEDULE PAYMENT</b></span>
                                    <div class="row">
                                        <div class="col-md">
                                            <span class="mt-2 badge bg-info">
                                                TOTAL TUITION FEE</span>
                                        </div>
                                        <div class="col-md-4 ">
                                            <span class="mt-2 float-end">
                                                <b class="final-tuition">{{ number_format($_total_fee, 2) }}</b>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md">
                                            <span class="mt-2 badge bg-info">
                                                UPON ENROLLMENT </span>
                                        </div>
                                        <div class="col-md-4 ">
                                            <span class="mt-2 float-end">
                                                <b class="upon-enrollment">{{ number_format($_total_fee, 2) }}</b>
                                            </span>
                                        </div>
                                    </div>
                                    @foreach ($_monthly_fee as $key => $_value)
                                        <div class="row">
                                            <div class="col-md">
                                                <span class="mt-2 badge bg-info">
                                                    {{ $_value }} </span>

                                            </div>
                                            <div class="col-md-4 ">
                                                <span class="mt-2 float-end">
                                                    <b class="monthly-fee">-</b>
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach


                                </div>
                                <button type="submit" class="btn btn-primary btn-block mt-2">SUBMIT</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <form action="" method="get">
                @if (request()->input('search_name'))
                    <input type="hidden" name="search_name" value="{{ request()->input('search_name') }}">
                @endif
                <div class="form-group search-input">
                    <input type="search" class="form-control" placeholder="Search..." name="_students">
                </div>
            </form>

            @if ($_students)
                @foreach ($_students as $item)
                    <div
                        class="card border-bottom border-4 border-0 {{ $item->account->student->enrollment_application->payment_mode ? 'border-danger' : 'border-primary' }} ">
                        <a href="?_midshipman={{ base64_encode($item->id) }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span
                                            class="{{ $item->account->student->enrollment_application->payment_mode ? 'text-danger' : 'text-primary' }}"><b>{{ strtoupper($item->last_name . ', ' . $item->first_name) }}</b></span>
                                    </div>
                                    <div>
                                        <span
                                            class="{{ $item->account->student->enrollment_application->payment_mode ? 'text-danger' : 'text-primary' }}">{{ $item->account ? $item->account->student_number : '' }}</span>
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
@endsection
