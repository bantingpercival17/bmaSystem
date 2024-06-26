@extends('widgets.report.app_report_template_v2')
@section('title-report', 'Entrance Examination Summary Report')
@section('form-code', '')


@section('content')
    <div class="page-content">
        <h3 class="text-center"><b>Entrance Examination Summary Report</b></h3>
        @php
            $contentNumber = 0;
            $contentCount = 10;
        @endphp
        <table class="table-content table-subject-grade">
            <thead>
                <tr>
                    <th>APPLICANT'S NAME</th>
                    <th>COURSE</th>
                    <th>EXAMINATION CODE</th>
                    <th>EXAMINATION DATE</th>
                    <th>SCORE</th>
                    <th>MODE TO TAKE THE EXAM</th>
                    {{-- <th>EXAM TAKE</th> --}}

                </tr>
            </thead>
            <tbody>
                @foreach ($totalExaminees as $item)
                    @php
                        $takeCount = count($item->examination_list);
                        $style = $takeCount > 1 ? 'background-color: #FFCCCC;' : '';
                        $contentNumber += 1;
                    @endphp
                    <tr style="{{ $style }}" class="{{ $contentNumber >= $contentCount ? 'page-break' : '' }}">
                        <td>
                            <p>
                                <a href="{{ route('applicant.profile-view') }}?_applicant={{ base64_encode($item->id) }}">
                                    <b>{{ strtoupper($item->applicant->last_name . ', ' . $item->applicant->first_name) }}</b>
                                </a>
                            </p>
                        </td>
                        <td><b>{{ $item->course->course_name }}</b></td>
                        <td>
                            @foreach ($item->examination_list as $item2)
                                <p>
                                    {{ $item2->examination_code }}
                                </p>
                            @endforeach
                        </td>
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
                                @if ($item2->is_finish == 1)
                                    @if ($result)
                                        <p>
                                            <span>TOTAL SCORE: <b>{{ $result[0] }}</b></span> |
                                            <span>REMARKS: <b>{{ $result[2] ? 'PASSED' : 'FAILED' }}</b></span>
                                        </p>
                                    @endif
                                @else
                                    @php
                                        $formattedDate = $item2->examination_scheduled;
                                        if ($item2->examination_scheduled) {
                                            $dateString = $item2->examination_scheduled->schedule_date;
                                            $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
                                            $formattedDate = $date->format('M d,Y h:i A');
                                        }
                                    @endphp
                                    <p>
                                        {{ $item2->examination_code }} | {{ $formattedDate }}
                                    </p>
                                @endif

                                {{-- {{ json_encode($result) }} <br> --}}
                            @endforeach
                        </td>
                        <td>
                            @foreach ($item->examination_list as $item2)
                                @if ($item2->examination_start)
                                    @php
                                        $dateString = $item2->examination_start;
                                        $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
                                        $formattedDate = $date->format('Y-m-d');
                                        $place = $item2->check_place($item->email, $formattedDate);
                                    @endphp
                                    <p>
                                        {{ $item2->examination_start }} | {{ $item->email }}
                                    </p>
                                    <p>
                                        {{ $place ? $place->ip_address : '' }}
                                    </p>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    @if ($contentNumber >= $contentCount)
                        @php
                            $contentNumber = 0;
                        @endphp
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
