@extends('widgets.report.grade.report_layout_1')
@section('title-report', 'FORM : STUDENT SECTION LIST')
@section('form-code', '')
@section('content')
    <div class="content">
        @foreach ($_sections as $_section)
            @if ($_section->count() > 0)
                <h3 class="text-center"><b>STUDENT SECTION LIST</b></h3>
                <h4 class="text-center"><b>{{ $_academic->semester . ' ' . $_academic->school_year }} |
                        <b>{{ $_section->section_name }}</b></b></h4>
                <table class="table-content ">
                    <thead>
                        <tr>
                            <th rowspan="2">STUDENT NUMBER</th>
                            <th colspan="3">NAME OF MIDSHIPMAN</th>
                        </tr>
                        <tr>
                            <th>LAST NAME</th>
                            <th>FIRST NAME</th>
                            <th>MIDDLE NAME</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if ($_section->student_section)
                            @foreach ($_section->student_section as $_student)
                                <tr>
                                    <td class="text-center">
                                        <img src="data:image/png;base64, {!! base64_encode(
                                            QrCode::style('round', 0.5)->eye('square')->size(200)->generate(
                                                    $_student->student->account->student_number .
                                                        '.' .
                                                        mb_strtolower(str_replace(' ', '', $_student->student->last_name)),
                                                ),
                                        ) !!} "> <br>
                                        {{ $_student->student->account->student_number . '.' . mb_strtolower(str_replace(' ', '', $_student->student->last_name)) }}
                                    </td>
                                    <td>{{ strtoupper($_student->student->last_name) }}</td>
                                    <td>{{ strtoupper($_student->student->first_name) }}</td>
                                    <td>{{ strtoupper($_student->student->middle_name) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <table class="table">
                    <thead>
                        <tr>
                            <td>TOTAL NUMBER OF CADETS : <b>{{ $_section->student_section->count() }}</b></td>
                        </tr>
                    </thead>
                </table>
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>
@endsection
