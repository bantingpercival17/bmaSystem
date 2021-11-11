@extends('app')
@section('page-title', 'Assessment Fee')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active">Assessment Fee</li>
    </ol>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-widget widget-user-2">
                <div class="widget-user-header bg-success">
                    <h5 class="">
                        <b>{{ $_student ? strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name) : 'Student Name' }}</b>
                    </h5>
                    <h6>STUDENT NO: <b>{{ $_student ? $_student->account->student_number : 'XX-XXXXX' }}</b></h6>
                </div>
            </div>

            <div class="
                        card">
                <div class="card-body">
                    <form role="form">
                        <input type="text" class="form-control text-code input-search"
                            placeholder="Search Last Name or First Name" data-container="search-container"
                            data-component="panel" data-url="/accounting/student-search"
                            data-link="/accounting/assessment-fee?_search_student=">
                    </form>
                </div>
            </div>
            <div class="search-container">
                @if ($_enrollment->count() > 0)
                    @foreach ($_enrollment as $assessment)
                        <a href="/accounting/assessment-fee?search_student={{ Crpyt::encrpty($assessment->student_id) }}"
                            class="btn btn-outline-success btn-block"
                            style="text-decoration: none">{{ strtoupper($assessment->student->last_name . ', ' . $assessment->student->first_name) . ' | ' . $assessment->student->user->client_code }}</a>
                    @endforeach
                @else
                    <a href="" class="btn btn-outline-success btn-block" style="text-decoration: none">STUDENT NAME |
                        STUDENT NUMBER</a>
                @endif

            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="header-text"><b class="text-success">ENROLLMENT ASSESSMENT</b></h5>
                </div>
                <div class="card-body">

                    <form class="form-assessments-view" role="form">
                        @csrf
                        @php
                            $_assessment = $_student ? $_student->enrollment_assessment : '';
                            $_total_fee = 0;
                            $_monthly_fee = ['1ST MONTHLY', '2ND MONTHLY', '3RD MONTHLY', '4TH MONTHLY'];
                        @endphp
                        <div class="row">
                            <div class="col-md">
                                <div class="form-group">
                                    <span class="text-muted"><b>COURSE / STRAND</b></span><br>
                                    <input type="hidden" name="student" class="data"
                                        value="{{ $_student ? $_student->id : '' }}">
                                    <label
                                        class="input_5 text-success h5">{{ $_assessment ? $_assessment->course->course_name : '' }}</label>
                                    <input type="hidden" name="course" class="data-1"
                                        value="{{ $_assessment ? $_assessment->course_id : '' }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <span class="text-muted"><b>YEAR LEVEL</b></span>
                                    <label
                                        class="input_5 text-success h5">{{ $_assessment ? ($_assessment->course->id != 3 ? $_assessment->year_level . ' CLASS' : 'GRADE ' . $_assessment->year_level) : '' }}</label>
                                    <input type="hidden" name="year" class="data-2"
                                        value="{{ $_assessment ? $_assessment->school_year : '' }}">
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <span class="text-muted"><b>ACADEMIC YEAR</b></span>
                                    <label
                                        class="input_5 text-success h5">{{ $_assessment ? $_assessment->academic->semester . ' | ' . $_assessment->academic->school_year : '' }}</label>
                                    <input type="hidden" name="academic" class="data-3"
                                        value="{{ $_assessment ? $_assessment->academic_id : '' }}">
                                </div>
                            </div>
                        </div>
                        <span class="text-success h5"><b>| TERMS OF PAYMENT</b></span>
                        <div class="row">
                            @if ($_assessment)
                                @if ($_assessment->course->department == 3)

                                    <div class="col-md">
                                        <div class="form-group">
                                            <span class="text-muted"><b>SCHOOL STANDARD :</b></span>
                                            <select class="form-control select-school select-input">
                                                <option value="14000.00">Private</option>
                                                <option value="17500.00">Public</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="form-group">
                                            <span class="text-muted"><b>ESC VOUCHER :</b></span>
                                            <select class="form-control select-voucher select-input">
                                                <option value="0">No Voucher</option>
                                                <option value="1">With Voucher</option>
                                            </select>
                                        </div>
                                    </div>
                                @else
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
                                    <span class="text-muted"><b>MODE :</b></span>
                                    <div class="col-sm">
                                        <select name="mode" class="form-control select-mode">
                                            <option value="0">Fullpayment</option>
                                            <option value="1">Installment</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="text-success h5"><b>| PAYMENT DETAILS</b></span>
                        <div class="row">
                            <div class="col-md">
                                <p class="text-muted h6"><b>PARTICULARS</b></p>
                                @if ($_assessment)

                                @else
                                    <p class="h6">
                                        <b class="">-</b>
                                        <b class="float-right">0.00</b>
                                    </p>
                                @endif
                                {{-- @if ($_assessment)
                                    @foreach ($_assessment->assessment_fee($_assessment) as $_fees)
                                        @if ($_fees->fee_id == 6)
                                            @if ($_assessment->bridging_program == 'with')
                                                @php
                                                    $_total_fee += $_fees->fee_amount;
                                                @endphp
                                                <p class="h6">
                                                    <b class="badge bg-secondary">{{ $_fees->particular->fee_name }}</b>
                                                    <span
                                                        class="float-right ">{{ number_format($_fees->fee_amount, 2) }}</span>
                                                </p>

                                            @endif
                                        @else
                                            @php
                                                $_total_fee += $_fees->fee_amount;
                                            @endphp
                                            <p class="h6">
                                                <b class="badge bg-secondary">{{ $_fees->particular->fee_name }}</b>
                                                <span class="float-right fee-{{ $_fees->particular->fee_name }}"
                                                    data-amount={{ $_fees->fee_amount }}>{{ number_format($_fees->fee_amount, 2) }}</span>
                                            </p>
                                        @endif
                                    @endforeach
                                @else
                                    <p class="h6">
                                        <b class="badge bg-secondary">{{ $_fees->particular->fee_name }}</b>
                                        <span class="float-right fee-{{ $_fees->particular->fee_name }}"
                                            data-amount={{ $_fees->fee_amount }}>{{ number_format($_fees->fee_amount, 2) }}</span>
                                    </p>
                                @endif --}}
                                <p class="h6">
                                    <b class="badge bg-info">TOTAL TUITION FEE</b>
                                    <input type="hidden" class="total-amount-fee" name="total-amount-fee"
                                        value="{{ $_total_fee }}" data-fee="{{ $_total_fee }}" disabled>
                                    <b class="float-right totalt-amount-fee">{{ number_format($_total_fee, 2) }}</b>
                                </p>
                                <p class="h6">
                                    <b class="badge bg-info">VOUCHER DISCOUNT </b>
                                    <span class="badge bg-warning">(LESS)</span>
                                    <input type="hidden" class="voucher-less" name="voucher-less" value="0" disabled>
                                    <b class="float-right voucher-less">0.00</b>
                                </p>
                                <p class="h6">
                                    <b class="badge bg-info">TOTAL PAYMENT</b>
                                    <input type="hidden" class="total-fee" name="total-payment"
                                        value="{{ $_total_fee }}" data-fee="{{ $_total_fee }}" disabled>
                                    <b class="float-right total-fee">{{ number_format($_total_fee, 2) }}</b>
                                </p>
                            </div>
                            <div class="col-md">
                                <span class="text-muted h6"><b>SCHEDULE PAYMENT</b></span>
                                <p class="h6">
                                    <span class="badge badge-info badge-lg center">UPON ENROLLMENT</span>
                                    <span class="float-right">
                                        <b class="payment-0">{{ number_format($_total_fee, 2) }}</b>
                                        <input type="hidden" class="payment-0 text-muted" name="upon-enrollment"
                                            value="{{ $_total_fee }}" disabled>
                                    </span>
                                </p>
                                @foreach ($_monthly_fee as $key => $_value)
                                    <p class="h6">
                                        <span class="badge badge-info badge-lg"> {{ $_value }} </span>
                                        <span class="float-right">
                                            <span class="payment-{{ $key += 1 }}">-</span>
                                        </span>
                                    </p>
                                @endforeach


                            </div>
                            <button type="submit" class="btn btn-success btn-block">SUBMIT</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
