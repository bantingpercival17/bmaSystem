@extends('widgets.report.grade.report_layout_1')
@section('title-report', 'FORM RG-03 - STUDENT REGISTRATION : ' . strtoupper($student->last_name . ', ' .
    $student->first_name . ' ' . $student->middle_name))
@section('form-code', 'RG - 03')
@section('content')
    <main class="content">
        <div class="form-rg-assessment">
            <h3 class="text-center">ENROLLMENT REGISTRATION</h3>
            <h6 class="text-center">A.Y.
                {{ strtoupper($assessment->academic->school_year . ' | ' . $assessment->academic->semester) }}
            </h6>
            @php
                $yearLevel = $assessment->year_level == '4' ? 'First Year' : '';
                $yearLevel = $assessment->year_level == '3' ? 'Second Year' : $yearLevel;
                $yearLevel = $assessment->year_level == '2' ? 'Third Year' : $yearLevel;
                $yearLevel = $assessment->year_level == '1' ? 'Fourth Year' : $yearLevel;
                $yearLevel = $assessment->year_level == '11' ? 'Grade 11' : $yearLevel;
                $yearLevel = $assessment->year_level == '12' ? 'Grade 12' : $yearLevel;
            @endphp
            <table class="table">
                <tbody>
                    <tr>
                        <td> <small>DATE:</small>
                            <b>{{ strtoupper(date('F j, Y', strtotime($assessment->created_at))) }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="{{ $assessment->bridging_program == 'with' ? 2 : 1 }}">
                            <small>STUDENT NAME: </small>
                            @php
                                $middleName = strtoupper($student->middle_name) !== 'N/A' ? $student->middle_name : '';
                                $extensionName = strtoupper($student->extention_name) !== 'N/A' ? $student->extention_name : '';
                            @endphp
                            <b>{{ strtoupper($student->last_name . ', ' . $student->first_name . ' ' . $middleName . ' ' . $extensionName) }}</b>
                        </td>
                        <td><small>STUDENT NO:</small> <b>{{ $student->account->student_number }}</b> </td>
                    </tr>
                    <tr>
                        <td><small>{{ $assessment->course_id != 3 ? 'COURSE:' : 'STRAND: ' }} </small>
                            <b>
                                {{ $assessment->course->course_name }}
                            </b>
                        </td>

                        @if ($assessment->bridging_program == 'with')
                            <td>
                                <small>LEVEL :</small>
                                <b>
                                    {{ strtoupper($yearLevel) }}
                                </b>
                            </td>
                            <td>
                                <small>BRIDGING PROGRAM : </small>
                                <b>
                                    {{ $assessment->bridging_program == 'with' ? 'YES' : 'NONE' }}
                                </b>
                            </td>
                        @else
                            <td>

                                <small>LEVEL :</small>
                                <b>
                                    {{ strtoupper($yearLevel) }}
                                </b>
                            </td>
                        @endif

                    </tr>

                </tbody>
            </table>
            <div class="subject-detials">
                <p class="title-header"><b>ENROLLMENT DETAILS</b></p>
                @php
                    $_units = 0;
                @endphp
                <table class="subject-list-table">
                    <thead>
                        <tr>
                            <th>SUBJECT CODE</th>
                            <th>DESCRIPTIVE TITLE</th>
                            @if ($assessment->course_id != 3)
                                <th>LEC. HOURS</th>
                                <th>LAB. HOURS</th>
                            @endif
                            <th>UNIT</th>
                        </tr>

                    </thead>
                    <tbody>
                        @if (count($assessment->course_subjects($assessment)))
                            @foreach ($assessment->course_subjects($assessment) as $_data)
                                @if ($assessment->bridging_program == 'with' || $_data->subject->subject_code != 'BRDGE')
                                    <tr>
                                        <td>{{ $_data->subject->subject_code }}</td>
                                        <td>{{ $_data->subject->subject_name }}</td>
                                        @if ($assessment->course_id != 3)
                                            <td style="text-align: center">{{ $_data->subject->lecture_hours }}</td>
                                            <td style="text-align: center">{{ $_data->subject->laboratory_hours }}</td>
                                        @endif
                                        <td style="text-align: center">{{ $_data->subject->units }}</td>
                                        @php
                                            $_units += $_data->subject->units;
                                        @endphp

                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="{{ $assessment->course_id != 3 ? 5 : 3 }}">No Subjects Encoded
                                </td>
                            </tr>
                        @endif

                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="{{ $assessment->course_id != 3 ? 4 : 2 }}">TOTAL UNITS</th>
                            <th class="total-unit">{{ $_units }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <br><br>
            <div class="assessment-fees">
                @php
                    $_assessment = $assessment->payment_assessments;
                    $_course_semestral_fee = $assessment->payment_assessments->course_semestral_fee;
                    $_payment_details = $_assessment;
                    $_total_payment = 0;
                    $_upon_enrollment = 0;
                    $_monthly_payment = 0;
                    $_total_fees = 0;
                @endphp
                <p class="title-header"><b>ASSESSMENT SUMMARY</b></p>
                <table class="subject-list-table ">
                    <thead>
                        <tr>
                            <th colspan="2">ASSESSMENT</th>
                        </tr>
                    </thead>
                    <tbody>
                        Assessment Details
                        <tr>
                            <td>PAYMENT MODE:</td>
                            <td class="text-center">
                                {{ $_payment_details->payment_mode == 0 ? 'FULLPAYMENT' : 'INSTALLMENT' }}

                            </td>

                        </tr>
                        @if (count($_course_semestral_fee->semestral_fees()) > 0)
                            @foreach ($_course_semestral_fee->semestral_fees() as $item)
                                <tr>
                                    <td>
                                        {{ ucwords(str_replace(['_', 'tags'], [' ', 'Fee'], $item->particular_tag)) }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $_particular_amount = $assessment->course_id == 3 ? $item->fees : $_course_semestral_fee->particular_tags($item->particular_tag);

                                            $_total_payment += $_particular_amount;
                                        @endphp
                                        <b> {{ number_format($_particular_amount, 2) }}</b>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($assessment->course_id == 3)
                                @foreach ($_course_semestral_fee->additional_fees($_course_semestral_fee->id) as $item)
                                    <tr>
                                        <td> <span class="mt-2 badge bg-success">
                                                {{ ucwords(str_replace(['_', 'tags'], [' ', 'Fee'], $item->particular_name)) }}</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $_total_payment += $item->particular_amount;
                                            @endphp
                                            <b> {{ number_format($item->particular_amount, 2) }}</b>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endif
                        @php

                        @endphp
                        <tr>
                            <td class="text-center"><b>TOTAL PAYMENT</b> </td>
                            <td class="text-center">
                                <b>{{ number_format($_total_payment, '2') }}</b>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2">SCHEDULE OF PAYMENT</th>
                        </tr>
                        <tr>
                            <td class="text-center">PAYMENT DUE DATE</td>
                            <td class="text-center">AMOUNT</td>
                        </tr>
                        <tr>
                            <td>UPON ENROLLMENT</td>
                            <td class="text-center">
                                {{ number_format($tuition_fees['upon_enrollment'], '2') }}
                            </td>
                        </tr>
                        <tr>
                            <td>4 MONTHLY INSTALLMENT</td>
                            <td class="text-center">
                                {{ number_format($tuition_fees['monthly'], '2') }}
                            </td>
                        </tr>
                        <tr>
                            <td><b>TOTAL FEES</b></td>
                            <td class="text-center">
                                <b>{{ $_payment_details
                                    ? ($_payment_details->course_semestral_fee_id
                                        ? number_format($_payment_details->course_semestral_fee->total_payments($_payment_details), 2)
                                        : number_format($_payment_details->total_payment, 2))
                                    : '-' }}</b>
                            </td>
                        </tr>
                        {{-- @foreach ($_monthly_fee as $_due)
                  <tr>
                      <td>{{$_due}}</td>
                    <td class="text-center">{{$_monthly_payment >0 ? number_format($_monthly_payment,'2'):'-'}}</td>
                    </tr>
                    @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
