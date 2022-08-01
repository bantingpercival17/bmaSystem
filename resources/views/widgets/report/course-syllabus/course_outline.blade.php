@extends('widgets.report.app_report_template_v2')
@section('title-report', 'PART B: COURSE OUTLINE AND TIMETABLE - ' . $_data->subject->subject_code)
@section('form-code', '')

@section('content')
    <div class="card-body">
        <p><b>B: COURSE OUTLINE AND TIMETABLE</b></p>
        <div class="table-responsive-lg">
            @php
                $_theoretical = 0;
                $_demonstration = 0;
            @endphp
            <table class="table-content table-outline ">
                <thead class="table-center">
                    <tr>
                        <th rowspan="2" style="width: 10em">TERM</th>
                        <th rowspan="2" style="width: 10em">WEEK</th>
                        <th rowspan="2" style="width: 50em">TOPIC</th>
                        <th colspan="2">Time allotment (in hours)</th>

                    </tr>
                    <tr>
                        <th style="width: 3em">Theoretical</th>
                        <th style="width: 3em">Demonstration /Practical Work</th>
                    </tr>
                </thead>
                <tbody>

                    @if ($_data->learning_outcomes)
                        @if (count($_data->learning_outcomes) > 0)
                            @foreach ($_data->learning_outcomes as $keyTopic => $topic)
                                @php
                                    $_theoretical += $topic->theoretical;
                                    $_demonstration += $topic->demonstration;
                                @endphp
                                <tr class="table-center">
                                    <td>{{ strtoupper($topic->term) }}</td>
                                    <td>
                                        @if ($topic->weeks)
                                            @foreach (json_decode($topic->weeks) as $key => $item)
                                                {{ strtoupper(str_replace('-', ' ', $item)) }}
                                                {{ count(json_decode($topic->weeks)) > $key + 1 ? ' - ' : '' }}
                                            @endforeach
                                        @endif
                                    </td>
                                    <td style="text-align: left"><b>{{ $keyTopic + 1 . '. ' . $topic->learning_outcomes }}</b></td>
                                    <td>{{ $topic->theoretical }}</td>
                                    <td>{{ $topic->demonstration }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">No Content</td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td colspan="5">No Content</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="table-center">
                        <th colspan="3" style="text-align: right">SUB-TOTAL (Contact Hours)</th>
                        <td>{{ $_theoretical }}</td>
                        <td>{{ $_demonstration }}</td>
                    </tr>
                    <tr class="table-center">
                        <th colspan="3" style="text-align: right">TOTAL CONTACT HOURS</th>
                        <td colspan="2">{{ $_theoretical + $_demonstration }}</td>
                    </tr>
                    <tr class="table-center">
                        <th colspan="3" style="text-align: right">EXAMINATION AND ASSESSMENT:</th>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>


    </div>

@endsection
