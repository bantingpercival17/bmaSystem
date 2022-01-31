@extends('layouts.app-main')
@section('page-title', 'Assessment Fee')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active">Assessment Fee</li>
    </ol>
@endsection
@section('js')
    <script>
        $('.payment-mode').change(function() {
            var mode = $(this).val();
            if (mode == 1) {
                console.log('Installment')
                var _tuition_fee = parseInt($('.tuition-fee').text().replace(/,/g, ''))
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
        })

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
            _intest = (_tuition_fee * 0.035)
            _total_fee = _tuition_fee + _intest
            _monthly_fee = _total_fee / 5;
            _upon_enrollment = _monthly_fee
            return {
                "total_tuition_fee": _tuition_fee,
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

                        <img src="{{ $_student ? $_student->profile_pic($_student->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
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
                                class="text-primary"><b>{{ $_assessment ? ($_assessment->course->id != 3 ? $_assessment->year_level . ' CLASS' : 'GRADE ' . $_assessment->year_level) : '-' }}</b></label>

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
                                                <option value="0">Fullpayment</option>
                                                <option value="1">Installment</option>
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
                                        @foreach ($_semestral_fees as $item)
                                            <div class="row">
                                                <div class="col-md">
                                                    <span class="mt-2 badge bg-info">
                                                        {{ ucwords(str_replace(['_', 'tags'], [' ', 'Fee'], $item->particular_tag)) }}</span>

                                                </div>
                                                <div class="col-md-4 ">
                                                    <span class="mt-2 float-end">
                                                        @php
                                                            /*  if ($item->particular_tag == 'tuition_tags') {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            if ($_assessment->course_id == 3) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $_total_fee += $item->fees;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $item_fee = $item->fees;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $_number_units = $_assessment->course_semestral_fees($_assessment)->course->units($_assessment)->units;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                if ($_assessment->bridging_program == 'with') {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $_total_fee = $item->fees * $_assessment->course_semestral_fees($_assessment)->course->units($_assessment)->units;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $item_fee = $item->fees * $_assessment->course_semestral_fees($_assessment)->course->units($_assessment)->units;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $_total_fee = $item->fees * ($_number_units - 3);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $item_fee = $_total_fee;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            $_total_fee += $item->fees;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            $item_fee = $item->fees;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        } */
                                                            $_total_fee += $item->fees;
                                                        @endphp
                                                        <b> {{ number_format($item->fees, 2) }}</b>
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
                    <div class="card border-bottom border-4 border-0 border-primary">
                        <a href="?_midshipman={{ base64_encode($item->id) }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span
                                            class="text-primary"><b>{{ strtoupper($item->last_name . ', ' . $item->first_name) }}</b></span>
                                    </div>
                                    <div>
                                        <span>{{ $item->account->student_number }}</span>
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