{{-- @extends('widgets.report.report_layout') --}}
@extends('widgets.report.app_report_template')
@section('title-report', 'LIST OF MIDSHIPMAN ABSENT - ' . date('Ymd'))
@section('form-code', '')
@section('content')
    @php
        $now = request()->input('week');
        $day = new DateTime($now);
        $week = date('l', strtotime($now));
        $modify = $week == 'Sunday' ? 'Sunday' : 'Last Sunday';
    @endphp
    <div class="content">
        <h3 class="text-center"><b>LIST OF MIDSHIPMAN ABSENT </b></h3>
        <table class="table-content">
            <thead>
                <tr>
                    <td>DATE RANGE:</td>
                    <td>
                        START DATE:
                        <b>
                            @php
                                $first_day = new DateTime(request()->input('week'));
                                $first_day->modify($modify);
                            @endphp
                            {{ strtoupper($first_day->format('F d, Y')) }}
                        </b>
                    </td>
                    <td>
                        END DATE:
                        <b>
                            @php
                                $first_day = new DateTime(request()->input('week'));
                                $first_day->modify('Next Saturday');
                            @endphp
                            {{ strtoupper($first_day->format('F d, Y')) }}
                        </b>
                    </td>
                </tr>
            </thead>
        </table>
        @php
            $_section_count = [];
        @endphp
        <table class="table-2">
            <thead>
                <tr>
                    <th>SECTION NAME</th>
                    <th>NAME OF MIDSHIPMAN</th>
                    <th>ADDRESS</th>
                    <th>REMARKS</th>
                </tr>
            </thead>
            <tbody>
                /* Checking if there is a section. */
                @if (count($_sections) > 0)
                    @foreach ($_sections as $key => $section)
                        @php
                            array_search($section->id, $_section_count) ?: array_push($_section_count, 0);
                        @endphp
                        @if ($section->count() > 0)
                            @if (count($section->student_sections) > 0)
                                @foreach ($section->student_sections as $item)
                                    @if (!$item->student->onboarding_attendance)
                                        @php
                                            $_section_count[$key] += 1;
                                        @endphp
                                        <tr>
                                            <td style="padding-left: 10px; width:20%">{{ $section->section_name }}</td>
                                            <td style="padding-left: 10px; width:40%">
                                                {{ strtoupper($item->student->last_name . ', ' . $item->student->first_name . ' ' . $item->student->middle_name) }}
                                            </td>
                                            <td class="text-center"style="padding-left: 10px; width:20%">
                                                {{ $item->student->municipality . ', ' . $item->student->province }}
                                            </td>
                                            <td style="padding-left: 10px; width:30%">

                                            </td>
                                        </tr>
                                    @else
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">No Section</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <h3 class="text-center"><b>SUMMARY OF MIDSHIPMAN ABSENT </b></h3>
        {{-- {{ dd($_section_count) }} --}}
        <table class="table-2">
            <thead>
                <tr>
                    <th>SECTION NAME</th>
                    <th>TOTAL ABSENT</th>
                    <th>REMARKS</th>
                    {{-- <th>ADDRESS</th>
                    <th>REMARKS</th> --}}
                </tr>
            </thead>
            <tbody>
                /* Checking if there is a section. */
                @if (count($_sections) > 0)
                    @foreach ($_sections as $key => $section)
                        @if ($section->count() > 0)
                            <tr>
                                <td style="padding-left: 10px; width:50%">{{ $section->section_name }}
                                </td>
                                <td class="text-center"style="padding-left: 10px; width:20%">{{ $_section_count[$key] }} /
                                    {{ $section->student_sections->count() }}</td>
                                <td></td>
                            </tr>
                            {{-- @if (count($section->student_sections) > 0)
                                @foreach ($section->student_sections as $item)
                                    @if (!$item->student->onboarding_attendance)
                                        <tr>
                                            <td style="padding-left: 10px; width:20%">{{ $section->section_name }}</td>
                                            <td style="padding-left: 10px; width:40%">
                                                {{ strtoupper($item->student->last_name . ', ' . $item->student->first_name . ' ' . $item->student->middle_name) }}
                                            </td>
                                            <td class="text-center"style="padding-left: 10px; width:20%">
                                                {{ $item->student->municipality . ', ' . $item->student->province }}
                                            </td>
                                            <td style="padding-left: 10px; width:30%">

                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif --}}
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">No Section</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
