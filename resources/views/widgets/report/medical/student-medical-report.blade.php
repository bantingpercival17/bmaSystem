@extends('widgets.report.main-report-template')
@section('title-report', 'STUDENT MEDICAL REPORT - ' . date('Ymd'))
@section('form-code', '')
@section('content')
    <div class="page-content">

        @foreach ($courses as $course)
            @php
                $levels = [11, 12];
                $levels = $course->id == 3 ? $levels : [4, 3];
            @endphp
            @foreach ($levels as $item)
                <div class="data-content">
                    <h3 class="text-center" style="margin:0px;"><b>STUDENT MEDICAL REPORT </b></h3>
                    <h5 class="text-center" style="margin:0px;"><b> PENDING MEDICAL</b>
                    </h5>
                    <h5 class="text-center" style="margin:0px;"><b>
                            {{ Auth::user()->staff->convert_year_level($item) . ' | ' . $course->course_name }}</b>
                    </h5>
                    <table class="table-student-content" style="margin-top: 20px;">
                        <thead>
                            <tr>
                                <th>NO.</th>
                                <th>STUDENT NUMBER</th>
                                <th>STUDENT NAME</th>
                                <th>COURSE</th>
                                <th>REMARKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $breakNumber = 40;
                                $contentNumber = 0;
                            @endphp
                            @if (count($course->student_medical_result($item)->get()) > 0)
                                @foreach ($course->student_medical_result($item)->get() as $key => $value)
                                    @php
                                        $contentNumber += 1;
                                    @endphp
                                    <tr class="{{ $contentNumber >= $breakNumber ? 'page-break' : '' }}">
                                        <th>
                                            {{ $key + 1 }}
                                        </th>
                                        <td>{{ $value->student->account ? $value->student->account->student_number : '-' }}
                                        </td>
                                        <td>{{ strtoupper($value->student->last_name . ', ' . $value->student->first_name . ' ' . $value->student->middle_name) }}
                                        </td>
                                        <td>{{ $value->course->course_name }}</td>
                                        <td>
                                            {{ base64_decode($value->student->enrollment_assessment->medical_result->remarks) }}
                                        </td>
                                        {{--   <td>{{ $value->course->course_code }}</td>
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
                                        </td> --}}
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
            @endforeach
        @endforeach
        {{--   @foreach ($data as $item)
            @if (count($item['value']) > 0)
                <div class="data-content">
                    <h3 class="text-center" style="margin:0px;"><b>STUDENT MEDICAL REPORT </b></h3>
                    <h5 class="text-center" style="margin:0px;"><b> {{ str_replace('_', ' ', strtoupper($item['name'])) }}</b>
                    </h5>
                    <table class="table-student-content" style="margin-top: 20px;">
                        <thead>
                            <tr>
                                <th>NO.</th>
                                <th>STUDENT NUMBER</th>
                                <th>STUDENT NAME</th>
                                <th>COURSE</th>
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
                                        <td>{{ strtoupper($value->applicant->last_name . ', ' . $value->applicant->first_name . ' ' . $value->applicant->middle_name) }}
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
        @endforeach --}}
    </div>
@endsection
