@extends('widgets.report.app_report_template_v2')
{{-- @extends('widgets.report.main-report-template') --}}
@section('title-report', 'MIDSHIPMAN ACCOUNT CARD-' . strtoupper($academic->semester . '-' . $academic->year_level))
@section('form-code', 'ACC-12')
@section('style')
    <style>
        .account-card-title {
            text-align: center;
            font-weight: 700;
            width: 100%;
        }

        .account-table {
            font-family: Arial, Helvetica, sans-serif;
        }

        .account-table td,
        .account-table th {
            padding: 0px 10px 0px 10px;
            font-size: 10px;


        }

        .account-table th {
            text-align: center;
        }

        .checkbox-container {
            padding-top: 15px;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        }

        .checkbox-input {
            margin: 0 14px 0 14px;
            vertical-align: middle;
            position: relative;
            top: 3px;
        }

        .text-left {
            text-align: right;
        }

        .text-center {
            text-align: center;
            text-transform: uppercase;
        }

        .text-50 {
            width: 20%;
        }

        .table-content {
            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
            width: 100%;
        }

        .table-content td,
        .table-content th {
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 10px;
            font-size: 14px;

        }
    </style>
@endsection
@section('content')

    @foreach ($students as $key => $student)
        <div class="page-content">
            <h3 class="text-center"><b>MIDSHIPMAN'S ACCOUNT CARD</b></h3>
            <label for="" class="account-card-title text-center"></label>
            @php
                $enrollment = $student->enrollment_academic_year($academic->id);
                $balance = 0;
            @endphp
            <table class="table-content account-table" style="width: 70%">
                <tr>
                    <td style="width: 30%">ENROLLMENT STATUS: </td>
                    <td class="checkbox-container">
                        @if ($enrollment->payment_assessments)
                            @if ($enrollment->payment_assessments->payment_assessment_paid)
                                @if ($status = $enrollment->enrollment_cancellation)
                                    <input class="checkbox-input" type="checkbox" />
                                    ENROLLED
                                    <input class="checkbox-input" type="checkbox"
                                        {{ $status->type_of_cancellations == 'withdrawn' ? 'checked' : '' }} />
                                    WITHDRAW
                                    <input class="checkbox-input" type="checkbox"
                                        {{ $status->type_of_cancellations == 'dropped' ? 'checked' : '' }} />
                                    DROPPED
                                @else
                                    <input class="checkbox-input" type="checkbox" checked />
                                    ENROLLED
                                    <input class="checkbox-input" type="checkbox" />
                                    WITHDRAW
                                    <input class="checkbox-input" type="checkbox" />
                                    DROPPED
                                @endif
                            @else
                                <input class="checkbox-input" type="checkbox" />
                                ENROLLED
                                <input class="checkbox-input" type="checkbox" />
                                WITHDRAW
                                <input class="checkbox-input" type="checkbox" />
                                DROPPED
                            @endif
                        @else
                            <input class="checkbox-input" type="checkbox" />
                            ENROLLED
                            <input class="checkbox-input" type="checkbox" />
                            WITHDRAW
                            <input class="checkbox-input" type="checkbox" />
                            DROPPED
                        @endif


                    </td>
                </tr>
            </table>
            <table class="table-content account-table">
                <tr>
                    <td>NAME:</td>
                    <th class="text-fill-in">
                        @php
                            $extensionName = strtolower($student->extention_name) != 'n/a' ? $student->extention_name : '';

                        @endphp
                        {{ strtoupper($student->last_name . ' ' . $extensionName . ', ' . $student->first_name . ' ' . $student->middle_name) }}
                    </th>
                    <td>SCHOOL YEAR:</td>
                    <th class="text-fill-in">
                        {{ strtoupper($enrollment->academic->semester . ' - ' . $enrollment->academic->school_year) }}</th>
                </tr>
                <tr>
                    <td>STUDENT NUMBER:</td>
                    <th class="text-fill-in">
                        {{ $student->account ? $student->account->student_number : 'NEW STUDENT' }}
                    </th>
                    <td style="width:100px; ">LEVEL/COURSE/SECTION:</td>
                    @php
                        $_section = $student->section($enrollment->academic_id)->first();
                    @endphp
                    <th class="text-fill-in" style="width:200px; ">
                        {{ $_section ? $_section->section->section_name : '-' }}
                    </th>
                </tr>
            </table>
            <table class="table-content account-table">
                <tr>
                    <td>PAYMENT SCHEME:</td>
                    <th class="checkbox-container">
                        <input class="checkbox-input" type="checkbox"
                            {{ $enrollment->payment_assessments ? ($enrollment->payment_assessments->payment_mode == 0 ? 'checked' : '') : '' }} />
                        FULL
                        <input class="checkbox-input" type="checkbox"
                            {{ $enrollment->payment_assessments ? ($enrollment->payment_assessments->payment_mode == 1 ? 'checked' : '') : '' }} />
                        PERIODIC
                    </th>
                    <td style="width:150px;">PAYMENT SCHEDULED:</td>
                    <td style="width:100px;">DOWN PAYMENT:</td>
                    <th class="text-fill-in" style="width:100px;">
                        {{ $enrollment->payment_assessments ? number_format($enrollment->payment_assessments->upon_enrollment, 2) : '' }}
                    </th>
                </tr>
                <tr>
                    <td>SCHOLARSHIP:</td>
                    <th class="text-fill-in">
                        {{ $student->scholarship_grant ? $student->scholarship_grant->voucher->voucher_name : 'NO SCHOLARSHIP' }}
                    </th>
                    <td></td>
                    <td>PERIODIC:</td>
                    <th class="text-fill-in" style="width:100px;">
                        {{ $enrollment->payment_assessments ? number_format($enrollment->payment_assessments->monthly_payment, 2) : '' }}
                    </th>
                </tr>
            </table>
            <table class="table table-onboard account-table">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>OR NUMBER</th>
                        <th>PARTICULARS</th>
                        <th>DEBIT</th>
                        <th>CREDIT</th>
                        <th>BALANCE</th>
                        <th>NOTE/REMARKS</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $row = 12;
                    @endphp
                    @if ($enrollment->payment_assessments)
                        @if (count($enrollment->payment_assessments->account_card_details()) > 0)
                            @foreach ($enrollment->payment_assessments->account_card_details() as $payment)
                                <tr>
                                    <td class="text-center">{{ $payment['date'] }}</td>
                                    <td class="text-center">{{ $payment['orNumber'] }}</td>
                                    <td class="text-center text-50">{{ $payment['remarks'] }}</td>
                                    <td class="text-left">
                                        {{ $payment['debit'] ? number_format($payment['debit'], 2) : '' }}
                                    </td>
                                    <td class="text-left">
                                        {{ $payment['credit'] ? number_format($payment['credit'], 2) : '' }}
                                    </td>
                                    <td class="text-left">
                                        @php
                                            if ($payment['debit'] !== null) {
                                                $balance -= $payment['debit'];
                                            }
                                            if ($payment['credit'] !== null) {
                                                $balance += $payment['credit'];
                                            }
                                        @endphp
                                        {{ number_format($balance, 2) }}
                                    </td>
                                    <th></th>
                                </tr>
                                @php
                                    $row -= 1;
                                @endphp
                            @endforeach
                            @for ($i = 0; $i < $row; $i++)
                                <tr>
                                    <td style="color:white">-</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endfor
                        @else
                        @endif
                    @else
                        @for ($i = 0; $i < $row; $i++)
                            <tr>
                                <td style="color:white">-</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endfor
                    @endif
                </tbody>
            </table>
            <br>
            <table class="table-content account-table">
                <tr>
                    <td>Assessed By:</td>
                    <td>Updated by:</td>
                    <td>Checked by:</td>
                    <td>Acknowledged by:</td>
                </tr>
                <tr>
                    <th>
                        @if ($enrollment->payment_assessments)
                            <img src="{{ public_path() . '\assets/img/signature/' . $enrollment->payment_assessments->staff->user->email . '.png' }}"
                                alt="" style="align-content: center; width:150px; margin:0px;">
                            <br>
                            {{ strtoupper($enrollment->payment_assessments->staff->first_name . ' ' . $enrollment->payment_assessments->staff->last_name) }}
                        @else
                            -
                        @endif
                    </th>
                    <th><img src="{{ public_path() . '/assets\img\signature/payments@bma.edu.ph.png' }}" alt=""
                            style="align-content: center; width:150px; margin:0px;">
                        <br>
                        MYRA LYN CANOZA
                    </th>
                    <th><img src="{{ public_path() . '/assets\img\signature\IRENE.png' }}" alt=""
                            style="align-content: center; width:150px; margin:0px;">
                        <br>
                        IRENE CAMACHO
                    </th>
                    <th>
                        <br>
                        <br><br>
                        <br><br>
                        {{ strtoupper($student->last_name . ' ' . $extensionName . ', ' . $student->first_name) }}
                    </th>
                </tr>
                <tr>
                    <th>ACCOUNTING CLERK</th>
                    <th>BOOKKEEPER</th>
                    <th>ACCOUNTING HEAD-OIC</th>
                    <th>MIDSHIPMAN</th>
                </tr>
            </table>
            {{--  <div class="page-break"></div>
            {{ $key % 2 }} --}}
        </div>
        <div class="page-break"></div>
    @endforeach

@endsection
