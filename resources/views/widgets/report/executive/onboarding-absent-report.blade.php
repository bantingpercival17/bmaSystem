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
        <table class="table-2">
            <thead>
                <tr>
                    <th>SECTION NAME</th>
                    <th>NAME OF MIDSHIPMAN</th>
                    <th>ADDRESS</th>
                </tr>
            </thead>
            <tbody>
                /* Checking if there is a section. */
                @if (count($_sections) > 0)
                    @foreach ($_sections as $section)
                        @if ($section->count() > 0)
                            @if (count($section->student_sections) > 0)
                                @foreach ($section->student_sections as $item)
                                    @if (!$item->student->onboarding_attendance)
                                        <tr>
                                            <td style="padding-left: 10px; width:20%">{{ $section->section_name }}</td>
                                            <td style="padding-left: 10px; width:40%">
                                                {{ strtoupper($item->student->last_name . ', ' . $item->student->first_name . ' ' . $item->student->middle_name) }}
                                            </td>
                                            <td class="text-center"style="padding-left: 10px; width:45%">
                                                {{ $item->student->municipality . ', ' . $item->student->province }}
                                            </td>
                                        </tr>
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

    </div>
@endsection
