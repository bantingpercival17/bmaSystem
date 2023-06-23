@extends('widgets.report.layout_report')
@section('title-report', 'FORM : STUDENT SECTION LIST')
@section('form-code', '')
@section('content')
    @foreach ($_sections as $_section)
        <header>
            <label for="" class="form-code">BMA FORM </label>
            <div class="text-center">
                <img src="{{ public_path() . '/assets/image/report-header.png' }}" alt="page-header">
            </div>

        </header>

        <div class="page-content">
            <div class="content">

                @if ($_section->count() > 0)
                    <br>
                    <h3 class="text-center" style="margin:0px;"><b>OFFICIAL LIST OF ENROLLED MIDSHIPMEN</b></h3>
                    <h4 class="text-center" style="margin:0px;">
                        <b>{{ strtoupper($_academic->semester . ', AY ' . $_academic->school_year) }}</b>
                    </h4>
                    <h5 class="text-center" style="margin:0px;">
                        {{ strtoupper(Auth::user()->staff->convert_year_level(str_replace('/C', '', $_section->year_level))) }}
                    </h5>
                    <br>
                    <table class="table ">
                        <tbody>
                            <tr>
                                <td><b>{{ $_section->course->course_name }}</b></td>
                            </tr>
                            <tr>
                                <td><b>{{ $_section->section_name }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table-student-content">
                        <thead>
                            <tr>
                                <th width="10px">NO.</th>
                                <th style="width: 90px;">STUDENT NUMBER</th>
                                <th>LAST NAME</th>
                                <th>FIRST NAME</th>
                                <th>MIDDLE NAME</th>
                                <th style="width: 90px;">EXTENSION NAME</th>
                                <th style="width: 50px;">MIDDLE INITIAL</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($_section->student_sections)
                                @foreach ($_section->student_sections as $key => $_student)
                                    <tr>
                                        <th>
                                            {{ $key + 1 }}
                                        </th>
                                        <td class="text-center">
                                            {{ $_student->student->account->student_number }}
                                        </td>
                                        <td>{{ strtoupper($_student->student->last_name) }}</td>
                                        <td>{{ strtoupper($_student->student->first_name) }}</td>
                                        <td>
                                            @if (trim(strtoupper($_student->student->middle_name)) !== 'N/A')
                                                {{ strtoupper($_student->student->middle_name) }}
                                            @endif

                                        </td>
                                        <td>
                                            @if (trim(strtoupper($_student->student->extention_name)) !== 'N/A')
                                                {{ strtoupper($_student->student->extention_name) }}
                                            @endif

                                        </td>
                                        <td>
                                            @if (trim(strtoupper($_student->student->middle_name)) !== 'N/A')
                                                {{ strtoupper($_student->student->middle_initial) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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
                @endif
            </div>
        </div>
        <div class="page-break"></div>
    @endforeach
@endsection
