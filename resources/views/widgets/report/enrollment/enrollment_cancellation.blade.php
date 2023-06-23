@extends('widgets.report.main-report-template')
@section('title-report', 'OFFICAL LIST OF WITHDRAWN AND DROP : ' . Auth::user()->staff->current_academic()->semester .
    '-' . Auth::user()->staff->current_academic()->school_year)
@section('content')
    <div class="withdrawn">
        <div class="page-content">
            <div class="summary-grade-header">
                <h3 class="text-center" style="margin:0px;"><b>OFFICAL LIST OF WITHDRAWN MIDSHIPMEN</b></h3>
                <h3 class="text-center" style="margin:0px;">
                    <b>{{ strtoupper(Auth::user()->staff->current_academic()->semester . ', AY ' . Auth::user()->staff->current_academic()->school_year) }}</b>
                </h3>

            </div>
            <div class="table-content">
                @php
                    $contentNumber = 0;
                @endphp
                <table class="table-student-content">
                    <thead>
                        <tr>
                            <th width="10px">NO.</th>
                            <th style="width: 90px;">STUDENT NUMBER</th>
                            <th>FULL NAME</th>
                            <th>COURSE</th>
                            <th>DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($data['withdrawn']) > 0)
                            @foreach ($data['withdrawn'] as $key => $enrollee)
                                @php
                                    $contentNumber += 1;
                                @endphp
                                <tr class="{{ $contentNumber >= 50 ? 'page-break' : '' }}">
                                    <th>
                                        {{ $key + 1 }}
                                    </th>
                                    <td class="text-center">
                                        {{ $enrollee->account ? $enrollee->account->student_number : '' }}
                                    </td>
                                    <td>
                                        {{ strtoupper($enrollee->last_name) }},
                                        {{ strtoupper($enrollee->first_name) }}
                                        @if (trim(strtoupper($enrollee->middle_name)) !== 'N/A')
                                            {{ strtoupper($enrollee->middle_name) }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $enrollee->enrollment_assessment->course->course_code }}

                                    </td>
                                    <td>
                                        {{ date('F d,Y', strtotime($enrollee->enrollment_assessment->enrollment_cancellation->date_of_cancellation)) }}
                                    </td>
                                </tr>
                                @if ($contentNumber >= 50)
                                    @php
                                        $contentNumber = 0;
                                    @endphp
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <th colspan="7">NO STUDENT</th>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <br>
                <div class="signatories">
                    <table class="table-content" style="font-size: 10px">
                        <tbody>

                            <tr>
                                <td>
                                    PREPARED BY:
                                </td>
                                <td>
                                    CHECKED & VALIDATED BY:
                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <u>
                                        <b>{{ strtoupper(Auth::user()->name) }}</b>
                                    </u>

                                </td>
                                <td>
                                    <u>
                                        <b>{{ strtoupper('marilen h. navarro') }}</b>
                                    </u>

                                </td>
                            </tr>
                            <tr>
                                <td><small>Registrar's Staff</small> </td>
                                <td><small>Registrar Department Head</small> </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="page-break"></div>
    <div class="dropped">
        <div class="page-content">
            <div class="summary-grade-header">
                <h3 class="text-center" style="margin:0px;"><b>OFFICAL LIST OF DROPPED MIDSHIPMEN</b></h3>
                <h3 class="text-center" style="margin:0px;">
                    <b>{{ strtoupper(Auth::user()->staff->current_academic()->semester . ', AY ' . Auth::user()->staff->current_academic()->school_year) }}</b>
                </h3>
            </div>
            <div class="table-content">
                @php
                    $contentNumber = 0;
                @endphp
                <table class="table-student-content">
                    <thead>
                        <tr>
                            <th width="10px">NO.</th>
                            <th style="width: 90px;">STUDENT NUMBER</th>
                            <th>FULL NAME</th>
                            <th>COURSE</th>
                            <th>DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($data['dropped']) > 0)
                            @foreach ($data['dropped'] as $key => $enrollee)
                                @php
                                    $contentNumber += 1;
                                @endphp
                                <tr class="{{ $contentNumber >= 50 ? 'page-break' : '' }}">
                                    <th>
                                        {{ $key + 1 }}
                                    </th>
                                    <td class="text-center">
                                        {{ $enrollee->account ? $enrollee->account->student_number : '' }}
                                    </td>
                                    <td>
                                        {{ strtoupper($enrollee->last_name) }},
                                        {{ strtoupper($enrollee->first_name) }}
                                        @if (trim(strtoupper($enrollee->middle_name)) !== 'N/A')
                                            {{ strtoupper($enrollee->middle_name) }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $enrollee->enrollment_assessment->course->course_code }}

                                    </td>
                                    <td>
                                        {{ date('F d,Y', strtotime($enrollee->enrollment_assessment->enrollment_cancellation->date_of_cancellation)) }}
                                    </td>
                                </tr>
                                @if ($contentNumber >= 50)
                                    @php
                                        $contentNumber = 0;
                                    @endphp
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <th colspan="7">NO STUDENT</th>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <br>
                <div class="signatories">
                    <table class="table-content" style="font-size: 10px">
                        <tbody>

                            <tr>
                                <td>
                                    PREPARED BY:
                                </td>
                                <td>
                                    CHECKED & VALIDATED BY:
                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <u>
                                        <b>{{ strtoupper(Auth::user()->name) }}</b>
                                    </u>

                                </td>
                                <td>
                                    <u>
                                        <b>{{ strtoupper('marilen h. navarro') }}</b>
                                    </u>

                                </td>
                            </tr>
                            <tr>
                                <td><small>Registrar's Staff</small> </td>
                                <td><small>Registrar Department Head</small> </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
