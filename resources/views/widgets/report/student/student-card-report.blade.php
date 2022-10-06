{{-- @extends('widgets.report.grade.report_layout_1') --}}
@extends('widgets.report.app_report_template')
@section('title-report', 'MIDSHIPMAN ACCOUNT CARD - ' . strtoupper($_student->last_name . ', ' . $_student->first_name))
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
            padding: 0px 0px 0px 0px;
            font-size: 10px;


        }

        .account-table th,
            {
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
    </style>
@endsection
@section('content')
    <div class="content">
        @foreach ($_student->enrollment_history as $enrollment)
            @if ($enrollment->payment_assessments)
                <h3 class="text-center"><b>MIDSHIPMAN'S ACCOUNT CARD</b></h3>
                <label for="" class="account-card-title text-center"></label>
                <table class="table-content account-table" style="width: 70%">
                    <tr>
                        <td style="width: 25%">ENROLLMENT STATUS: </td>
                        <td style="witd: 30%" class="checkbox-container">
                            <input class="checkbox-input"type="checkbox" />
                            ENROLLED
                            <input class="checkbox-input"type="checkbox" />
                            WITHDRAW
                            <input class="checkbox-input"type="checkbox" />
                            DROPPED
                        </td>

                    </tr>

                </table>
                <table class="table-content account-table">
                    <tr>
                        <td>NAME:</td>
                        <th class="text-fill-in">
                            {{ strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name) }}
                        </th>
                        <td>SCHOOL YEAR:</td>
                        <th class="text-fill-in">{{ $enrollment->academic->school_year }}</th>
                    </tr>
                    <tr>
                        <td>STUDENT NUMBER:</td>
                        <th class="text-fill-in">{{ $_student->account->student_number }}</th>
                        <td>LEVEL/COURSE/SECTION:</td>
                        @php
                            $_section = $_student->section($enrollment->academic_id)->first();
                        @endphp
                        <th class="text-fill-in">{{ $_section ? $_section->section->section_name : '-' }}</th>
                    </tr>
                </table>
                <br>
                <table class="table-content account-table">
                    <tr>
                        <td>PAYMENT SCHEME:</td>
                        <th class="checkbox-container">
                            <input class="checkbox-input"type="checkbox"
                                {{ $enrollment->payment_assessments->payment_mode == 0 ? 'checked' : '' }} />
                            FULL
                            <input class="checkbox-input"type="checkbox"
                                {{ $enrollment->payment_assessments->payment_mode == 1 ? 'checked' : '' }} />
                            PERIODIC
                        </th>
                        <td>PAYMENT SCHEDULED:</td>
                        <td>DOWN PAYMENT:</td>
                        <th class="text-fill-in">00.00</th>
                    </tr>
                    <tr>
                        <td>SCHOLARSHIP:</td>
                        <th class="text-fill-in">-</th>
                        <td></td>
                        <td>PERIODIC:</td>
                        <th class="text-fill-in">-</th>
                    </tr>
                </table>
                <table class="table-2 account-table">
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
                        @if (count($enrollment->payment_assessments->payment_transaction) > 0)
                            @foreach ($enrollment->payment_assessments->payment_transaction as $payment)
                                <tr>
                                    <th>{{ $payment->transaction_date }}</th>
                                    <th>{{ $payment->or_number }}</th>
                                    <th>{{ $payment->remarks }}</th>
                                    <th>{{ number_format($payment->payment_amount, 2) }}</th>
                                    <th></th>
                                    <th></th>
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
                        <th>{{ strtoupper($enrollment->payment_assessments->staff->first_name . ' ' . $enrollment->payment_assessments->staff->last_name) }}
                        </th>
                        <th>MYRA LYN CANOZA</th>
                        <th>IRENE CAMACHO</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>ACCOUNTING CLERK</th>
                        <th>BOOKKEEPR</th>
                        <th>ACCOUNTING HEAD-OIC</th>
                        <td></td>
                    </tr>
                </table>
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>
@endsection
