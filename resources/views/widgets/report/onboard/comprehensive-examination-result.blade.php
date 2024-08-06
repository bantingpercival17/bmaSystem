@extends('widgets.report.main-report-template')
@section('title-report', 'COMPRE-RESULT: ' . strtoupper($course->course_name) . '-' . $date->format('F d,Y'))
@section('form-code', '')

@section('content')
    <div class="page-content">
        <div class="narative-report-summary">
            <h2 class="text-center content-header mt-2">COMPREHENSIVE WRITTEN ASSESSMENT RESULTS</h2>
            <h3 class="text-center content-header">COURSE: <i>{{ $course->course_name }}</i></h3>
            <h4 class="text-center content-header">DATE: <i>{{ $date->format('F d,Y') }}</i></h4>
        </div>
        <div class="result-table">
            <table class="table-2">
                <thead>
                    <tr>
                        <th>SURNAME</th>
                        <th>FIRST NAME</th>
                        @foreach ($comprehensive as $item)
                            <th>{{ $item->competence_code }}</th>
                        @endforeach
                        <th>REMARKS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($examinee as $item)
                        @php
                            $resultRemarks = 0;
                        @endphp
                        <tr>
                            <th>
                                <b>{{ strtoupper($item->compre_examinee->student->last_name) }}</b>
                            </th>
                            <th>
                                <b>{{ strtoupper($item->compre_examinee->student->first_name) }}</b>
                            </th>
                            @foreach ($comprehensive as $item1)
                                @php
                                    $borderStyle = '';
                                    $result = 0;
                                    if ($item->compre_examinee->competence_result($item1->id)) {
                                        $result = $item->compre_examinee->competence_result($item1->id)->result;
                                        $result = (int) str_replace('%', '', $result);
                                        if ($result < 60) {
                                            $borderStyle = 'failed-color';
                                            $resultRemarks += 1;
                                        }
                                    }
                                @endphp
                                <th class="{{ $borderStyle }}">
                                    {{--  {{ $result }} --}}
                                    {{ $item->compre_examinee->competence_result($item1->id) ? $item->compre_examinee->competence_result($item1->id)->result : '-' }}
                                </th>
                            @endforeach
                            <th class="{{ $resultRemarks > 0 ? 'failed-color' : '' }}">
                                {{ $resultRemarks > 0 ? 'FAILED' : 'PASSED' }}
                            </th>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">NO DATA</td>
                            @foreach ($comprehensive as $item1)
                                <th>
                                </th>
                            @endforeach
                            <th>

                            </th>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('style')
    <style>
        .content-header {
            margin: 5px;
        }

        .failed-color {
            background-color: rgb(255, 73, 7);
            color: white;
        }
    </style>
@endsection
