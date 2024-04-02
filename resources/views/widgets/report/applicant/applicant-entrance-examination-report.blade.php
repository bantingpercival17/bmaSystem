@extends('widgets.report.app_report_template_v2')
@section('title-report', 'Entrance Examination Summary Report')
@section('form-code', '')


@section('content')
    <div class="page-content">
        <h3 class="text-center"><b>Entrance Examination Summary Report</b></h3>
        <table class="table-content table-subject-grade">
            <thead>
                <tr>
                    <th>APPLICANT'S NAME</th>
                    <th>COURSE</th>
                    <th>EXAMINATION DATE</th>
                    <th>SCORE</th>
                    {{-- <th>EXAM TAKE</th> --}}

                </tr>
            </thead>
            <tbody>
                @foreach ($totalExaminees as $item)
                    <tr>
                        <td>
                            <b> {{ $item->applicant->first_name . ' ' . $item->applicant->last_name }} </b>
                        </td>
                        <td><b>{{ $item->course->course_name }}</b></td>
                        <td>
                            @foreach ($item->examination_list as $item2)
                                @if ($item2->examination_start)
                                    @php
                                        $dateString = $item2->examination_start;
                                        $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
                                        $formattedDate = $date->format('M d,Y h:i A');
                                    @endphp
                                    <p>{{ $formattedDate }}</p>
                                @else
                                    {{ $item2->examination_start }}
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($item->examination_list as $item2)
                                @php
                                    $result = $item2->examination_result();
                                @endphp
                                @if ($result)
                                    <p>
                                        <span>TOTAL SCORE: <b>{{ $result[0] }}</b></span> |
                                        {{--  <span>REMARKS: <b>{{ $result[2] ? 'PASSED' : 'FAILED' }}</b></span> --}}
                                    </p>
                                @endif
                                {{-- {{ json_encode($result) }} <br> --}}
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
