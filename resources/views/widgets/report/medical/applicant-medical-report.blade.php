@extends('widgets.report.main-report-template')
@section('title-report', 'APPLICANT MEDICAL REPORT - ' . date('Ymd'))
@section('form-code', '')
@section('content')
    <div class="page-content">

        @foreach ($data as $item)
            @if (count($item['value']) > 0)
                <div class="data-content">
                    <h3 class="text-center" style="margin:0px;"><b>APPLICANT MEDICAL REPORT </b></h3>
                    <h5 class="text-center" style="margin:0px;"><b> {{ str_replace('_', ' ', strtoupper($item['name'])) }}</b>
                    </h5>
                    <table class="table-student-content" style="margin-top: 20px;">
                        <thead>
                            <tr>
                                <th>NO.</th>
                                <th>APPLICANT NAME</th>
                                <th>CONTACT NUMBER</th>
                                <th>COURSE</th>
                                <th>DATE OF SCHEDULE</th>
                                <th>MEDICAL RESULT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $breakNumber = 40;
                                $contentNumber = 0;
                            @endphp
                            @if (count($item['value']) > 0)
                                @foreach ($item['value'] as $key => $value)
                                    @php
                                        $contentNumber += 1;
                                    @endphp
                                    <tr class="{{ $contentNumber >= $breakNumber ? 'page-break' : '' }}">
                                        <th>
                                            {{ $key + 1 }}
                                        </th>
                                        <td>
                                            @if ($value->applicant)
                                                {{ strtoupper($value->applicant->last_name . ', ' . $value->applicant->first_name . ' ' . $value->applicant->middle_name) }}
                                            @else
                                                {{ $value->name }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $value->contact_number }}
                                        </td>
                                        <td>{{ $value->course->course_code }}</td>
                                        <td>
                                            @if ($value->medical_appointment)
                                                @if ($value->medical_appointment)
                                                    {{ $value->medical_appointment->appointment_date }}-APPROVED
                                                @else
                                                    {{ $value->medical_appointment->appointment_date }}-PENDING
                                                @endif
                                            @else
                                                NO MEDICAL APPOINTMENT
                                            @endif
                                        </td>
                                        <td style="width: 250px;" class="text-center">
                                            @if ($value->medical_result)
                                                @if ($value->medical_result->is_fit)
                                                    @if ($value->medical_result->is_fit == 1)
                                                        QUALIFIED TO ENROLL
                                                    @else
                                                        NOT QUALIFIED
                                                    @endif
                                                    {{ $value->medical_result->created_at->format('F d,y') }}
                                                @else
                                                    @if ($value->medical_result->is_pending == 0)
                                                        {{ $value->medical_result->remarks }}
                                                        {{ $value->medical_result->created_at->format('F d,y') }}
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($contentNumber >= $breakNumber)
                                        @php
                                            $contentNumber = 0;
                                        @endphp
                                    @endif
                                @endforeach
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
                                            <b>{{ strtoupper('Robert Evangelista') }}</b>
                                        </u>

                                    </td>
                                </tr>
                                <tr>
                                    <td><small>Medical Officer</small> </td>
                                    <td><small>Senior Tactic Officer</small> </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>
@endsection
