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
                    </tr>
                </thead>
                <tbody>
                    @forelse ($examinee as $item)
                        <tr>
                            <td>
                                <b>{{ strtoupper($item->compre_examinee->student->last_name) }}</b>
                            </td>
                            <td>
                                <b>{{ strtoupper($item->compre_examinee->student->first_name) }}</b>
                            </td>
                            @foreach ($comprehensive as $item)
                                <th>{{ $item->competence_code }}</th>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">NO DATA</td>
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
    </style>
@endsection
