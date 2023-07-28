@extends('widgets.report.main-report-template')
@section('title-report', 'OFFICAL LIST OF ENROLLED MIDSHIPMEN : ' . Auth::user()->staff->current_academic()->semester .
    '-' . Auth::user()->staff->current_academic()->school_year)
@section('content')
    @foreach ($courses as $course)
        @php
            $year_level = [4, 3, 2, 1];
            $year_level = $course->id == 3 ? [11, 12] : $year_level;
        @endphp
        <div class="content">
            @foreach ($year_level as $level)
                <div class="page-content">
                    <div class="summary-grade-header">
                        <h3 class="text-center" style="margin:0px;">
                            <b>{{ str_replace('BS', 'BACHELOR OF SCIENCE IN', $course->course_name) }}</b>
                        </h3>
                        <h3 class="text-center" style="margin:0px;"><b>OFFICAL LIST OF ENROLLED MIDSHIPMEN</b></h3>
                        <h3 class="text-center" style="margin:0px;">
                            <b>{{ strtoupper(Auth::user()->staff->current_academic()->semester . ', AY ' . Auth::user()->staff->current_academic()->school_year) }}</b>
                        </h3>
                        <h3 class="text-center" style="margin:0px;">
                            <b>{{ strtoupper(Auth::user()->staff->convert_year_level($level)) }}</b>
                        </h3>
                        <br>

                    </div>
                    <div class="table-content">
                        @php
                            $contentNumber = 0;
                        @endphp
                        <table class="table-student-content">
                            <thead>
                                <tr>
                                    <th width="10px">NO.</th>
                                    <th style="width: 150px;">STUDENT NUMBER</th>
                                    <th>LAST NAME</th>
                                    <th>FIRST NAME</th>
                                    <th>MIDDLE NAME</th>
                                    <!--   <th style="width: 50px;">EXTENSION NAME</th>
                                                <th style="width: 50px;">MIDDLE INITIAL</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($course->student_officially_enrolled_per_year($level)->get()) > 0)
                                    @foreach ($course->student_officially_enrolled_per_year($level)->get() as $key => $enrollee)
                                        @php
                                            $contentNumber += 1;
                                        @endphp
                                        <tr class="{{ $contentNumber >= 50 ? 'page-break' : '' }}">
                                            <th>
                                                {{ $key + 1 }}
                                            </th>
                                            <td class="text-center">
                                                {{ $enrollee->student->account ? $enrollee->student->account->student_number : '' }}
                                            </td>
                                            <td>{{ strtoupper($enrollee->student->last_name) }}
                                                @if (trim(strtoupper($enrollee->student->extention_name)) !== 'N/A')
                                                    {{ strtoupper($enrollee->student->extention_name) }}
                                                @endif
                                            </td>
                                            <td>{{ strtoupper($enrollee->student->first_name) }}</td>
                                            <td>
                                                @if (trim(strtoupper($enrollee->student->middle_name)) !== 'N/A')
                                                    {{ strtoupper($enrollee->student->middle_name) }}
                                                @endif

                                            </td>
                                            {{--  <td>
                                                @if (trim(strtoupper($enrollee->student->extention_name)) !== 'N/A')
                                                    {{ strtoupper($enrollee->student->extention_name) }}
                                                @endif

                                            </td>
                                            <td>
                                                @if (trim(strtoupper($enrollee->student->middle_name)) !== 'N/A')
                                                    {{ strtoupper($enrollee->student->middle_initial) }}
                                                @endif
                                            </td> --}}
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
                <div class="page-break"></div>
                @if ($course->id != 3)
                    @foreach (Auth::user()->staff->curriculum_list() as $curriculum)
                        @php
                            $yearLevelName = $curriculum->id === 1 && $level === 2 ? ' 1ST CLASS ONBOARD TRAINING 2-1-1' : '';
                            $yearLevelName = $curriculum->id === 7 && $level === 1 ? ' 2ND CLASS ONBOARD TRAINING 3-1' : $yearLevelName;
                        @endphp
                        @if (($curriculum->id === 1 && $level === 2) || ($curriculum->id === 7 && $level === 1))
                            <div class="page-content">
                                <div class="summary-grade-header">
                                    <h3 class="text-center" style="margin:0px;">
                                        <b>{{ str_replace('BS', 'BACHELOR OF SCIENCE IN', $course->course_name) }}</b>
                                    </h3>
                                    <h3 class="text-center" style="margin:0px;"><b>OFFICIALLY ENROLLED</b></h3>
                                    <h3 class="text-center" style="margin:0px;">
                                        <b>{{ strtoupper($yearLevelName) }}</b>
                                    </h3>

                                </div>
                                <div class="table-content">
                                    <table class="table-student-content">
                                        <thead>
                                            <tr>
                                                <th width="15px">NO.</th>
                                                <th style="width: 150px;">STUDENT NUMBER</th>
                                                <th>LAST NAME</th>
                                                <th>FIRST NAME</th>
                                                <th>MIDDLE NAME</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($course->student_officially_enrolled_per_year_and_curriculum($level, $curriculum->id)->get()) > 0)
                                                @foreach ($course->student_officially_enrolled_per_year_and_curriculum($level, $curriculum->id)->get() as $key => $enrollee)
                                                    <tr>
                                                        <th>
                                                            {{ $key + 1 }}
                                                        </th>
                                                        <td class="text-center">
                                                            {{ $enrollee->student->account ? $enrollee->student->account->student_number : '' }}
                                                        </td>
                                                        <td>{{ strtoupper($enrollee->student->last_name) }}
                                                            @if (trim(strtoupper($enrollee->student->extention_name)) !== 'N/A')
                                                                {{ strtoupper($enrollee->student->extention_name) }}
                                                            @endif
                                                        </td>
                                                        <td>{{ strtoupper($enrollee->student->first_name) }}</td>
                                                        <td>
                                                            @if (trim(strtoupper($enrollee->student->middle_name)) !== 'N/A')
                                                                {{ strtoupper($enrollee->student->middle_name) }}
                                                            @endif
                                                        </td>
                                                    </tr>
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
                            <div class="page-break"></div>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
        <div class="page-break"></div>
    @endforeach
@endsection
