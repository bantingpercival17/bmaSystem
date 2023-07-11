@extends('widgets.report.main-report-template')
@section('title-report', 'APPLICANT LIST [VERIFIED]')
@section('content')
    <div class="withdrawn">
        @foreach ($courses as $course)
            <div class="page-content">
                <div class="summary-grade-header">
                    <h3 class="text-center" style="margin:0px;"><b>APPLICANT LIST [VERIFIED]</b></h3>
                    <h3 class="text-center" style="margin:0px;"><b>{{ $course->course_name }}</b></h3>
                </div>
                <div class="table-content">
                    {{--   {{ $course->verified_applicants }} --}}
                    @php
                        $contentNumber = 0;
                    @endphp
                    <table class="table-student-content">
                        <thead>
                            <tr>
                                <th width="10px">NO.</th>
                                <th>APPLICATION DATE</th>
                                <th>FULL NAME</th>
                                <th>CONTACT NUMBER</th>
                                <th>STATUS</th>

                            </tr>
                        </thead>
                        <tbody>

                            @if (count($course->verified_applicants_v2) > 0)
                                @foreach ($course->verified_applicants as $key => $enrollee)
                                    @if (!$enrollee->is_alumnia)
                                        @if ($enrollee->payment)
                                            @if ($enrollee->payment->is_approved)
                                                @if (!$enrollee->applicant_examination->is_finish)
                                                    @php
                                                        $contentNumber += 1;
                                                    @endphp
                                                    <tr class="{{ $contentNumber >= 50 ? 'page-break' : '' }}">
                                                        <th>
                                                            {{ $contentNumber }}
                                                        </th>
                                                        <td>{{ $enrollee->created_at->format('F d,y') }}</td>
                                                        <td>
                                                            {{ strtoupper($enrollee->applicant->last_name) }},
                                                            {{ strtoupper($enrollee->applicant->first_name) }}
                                                            @if (trim(strtoupper($enrollee->applicant->middle_name)) !== 'N/A')
                                                                {{ strtoupper($enrollee->applicant->middle_name) }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $enrollee->contact_number }}</td>
                                                        <td>
                                                            @if ($enrollee->is_alumnia)
                                                                {{ strtoupper('BMA-Alumnia') }}
                                                            @else
                                                                @if ($enrollee->payment)
                                                                    @if ($enrollee->payment->is_approved)
                                                                        @if ($enrollee->applicant_examination->is_finish)
                                                                            ExaminationResult
                                                                        @else
                                                                            READY FOR EXAMINATION
                                                                        @endif
                                                                    @else
                                                                        FOR PAYMENT VERIFICATION
                                                                    @endif
                                                                @else
                                                                    WFP
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @else
                                                @php
                                                    $contentNumber += 1;
                                                @endphp
                                                <tr class="{{ $contentNumber >= 50 ? 'page-break' : '' }}">
                                                    <th>
                                                        {{ $contentNumber }}
                                                    </th>
                                                    <td>{{ $enrollee->created_at->format('F d,y') }}</td>
                                                    <td>
                                                        {{ strtoupper($enrollee->applicant->last_name) }},
                                                        {{ strtoupper($enrollee->applicant->first_name) }}
                                                        @if (trim(strtoupper($enrollee->applicant->middle_name)) !== 'N/A')
                                                            {{ strtoupper($enrollee->applicant->middle_name) }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $enrollee->contact_number }}</td>
                                                    <td>
                                                        @if ($enrollee->is_alumnia)
                                                            {{ strtoupper('BMA-Alumnia') }}
                                                        @else
                                                            @if ($enrollee->payment)
                                                                @if ($enrollee->payment->is_approved)
                                                                    @if ($enrollee->applicant_examination->is_finish)
                                                                        ExaminationResult
                                                                    @else
                                                                        READY FOR EXAMINATION
                                                                    @endif
                                                                @else
                                                                    FOR PAYMENT VERIFICATION
                                                                @endif
                                                            @else
                                                                WFP
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @else
                                            @php
                                                $contentNumber += 1;
                                            @endphp
                                            <tr class="{{ $contentNumber >= 50 ? 'page-break' : '' }}">
                                                <th>
                                                    {{ $contentNumber }}
                                                </th>
                                                <td>{{ $enrollee->created_at->format('F d,y') }}</td>
                                                <td>
                                                    {{ strtoupper($enrollee->applicant->last_name) }},
                                                    {{ strtoupper($enrollee->applicant->first_name) }}
                                                    @if (trim(strtoupper($enrollee->applicant->middle_name)) !== 'N/A')
                                                        {{ strtoupper($enrollee->applicant->middle_name) }}
                                                    @endif
                                                </td>
                                                <td>{{ $enrollee->contact_number }}</td>
                                                <td>
                                                    @if ($enrollee->is_alumnia)
                                                        {{ strtoupper('BMA-Alumnia') }}
                                                    @else
                                                        @if ($enrollee->payment)
                                                            @if ($enrollee->payment->is_approved)
                                                                @if ($enrollee->applicant_examination->is_finish)
                                                                    ExaminationResult
                                                                @else
                                                                    READY FOR EXAMINATION
                                                                @endif
                                                            @else
                                                                FOR PAYMENT VERIFICATION
                                                            @endif
                                                        @else
                                                            WFP
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($contentNumber >= 50)
                                            @php
                                                $contentNumber = 0;
                                            @endphp
                                        @endif
                                    @endif
                                    {{--   @php
                                        $contentNumber += 1;
                                    @endphp
                                    <tr class="{{ $contentNumber >= 50 ? 'page-break' : '' }}">
                                        <th>
                                            {{ $key + 1 }}
                                        </th>
                                        <td>{{ $enrollee->created_at->format('F d,y') }}</td>
                                        <td>
                                            {{ strtoupper($enrollee->applicant->last_name) }},
                                            {{ strtoupper($enrollee->applicant->first_name) }}
                                            @if (trim(strtoupper($enrollee->applicant->middle_name)) !== 'N/A')
                                                {{ strtoupper($enrollee->applicant->middle_name) }}
                                            @endif
                                        </td>
                                        <td>{{ $enrollee->contact_number }}</td>
                                        <td>
                                            @if ($enrollee->is_alumnia)
                                                {{ strtoupper('BMA-Alumnia') }}
                                            @else
                                                @if ($enrollee->payment)
                                                    @if ($enrollee->payment->is_approved)
                                                        @if ($enrollee->applicant_examination->is_finish)
                                                            ExaminationResult
                                                        @else
                                                            READY FOR EXAMINATION
                                                        @endif
                                                    @else
                                                        FOR PAYMENT VERIFICATION
                                                    @endif
                                                @else
                                                   WFP
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($contentNumber >= 50)
                                        @php
                                            $contentNumber = 0;
                                        @endphp
                                    @endif --}}
                                @endforeach
                            @else
                                <tr>
                                    <th colspan="7">NO STUDENT</th>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <br>
                    {{-- <div class="signatories">
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
                    </div> --}}
                </div>
            </div>
            <div class="page-break"></div>
        @endforeach

    </div>

@endsection
