@extends('widgets.report.grade.report_layout_1')
@section('title-report', ' - SEMESTRAL CLEARANCE OVERVIEW : ' . $_section->section_name)
@section('form-code', '')
@section('content')
    <div class="content">
        <h3 class="text-center">SEMESTRAL CLEARANCE OVERVIEW</h3>
        <h4 class="text-center">A.Y.
            {{ strtoupper(Auth::user()->staff->current_academic()->school_year . ' | ' . Auth::user()->staff->current_academic()->semester) }}
        </h4>

        <table class="table">
            <tbody>
                <tr>
                    <td><small>SECTION :</small>
                        <span><b>{{ $_section->section_name }}</b></span>
                    </td>
                    <td style="width: 55%"></td>
                </tr>
            </tbody>
        </table>
        <table class="table-2">
            <thead>
                <tr>
                    <th>Student Number</th>
                    <th>Midshipman Name</th>
                    @foreach ($_section->subject_class as $_class)
                        <th>{{ $_class->curriculum_subject->subject->subject_code }}</th>
                    @endforeach
                    @php
                        $_department = ['Department Head', 'Laboratory', 'Dean', 'Library', 'Exo', 'Accounting', 'ICT', 'Registrar'];
                    @endphp
                    @foreach ($_department as $item)
                        <th>{{ $item == 'Department Head' ? 'Academic Department' : $item }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if (count($_section->student_section) > 0)
                    @foreach ($_section->student_section as $_key => $_data)
                        <tr class="text-center">
                            <td>{{ $_data->student->account ? $_data->student->account->student_number : '-' }}
                            </td>
                            <td>{{ strtoupper($_data->student->last_name . ', ' . $_data->student->first_name) }}
                            </td>
                            @foreach ($_section->subject_class as $_class)
                                <td>
                                    <div class="form-check d-block ">
                                        @if ($_data->student->clearance($_class->id))
                                            @if ($_data->student->clearance($_class->id)->is_approved == 1)
                                                CLEARED
                                            @else
                                                <span style="color:red"><b> NOT CLEARED</b></span>
                                            @endif
                                        @else
                                            <span class="text-danger"><b>-</b></span>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                            @foreach ($_department as $item)
                                <td>
                                    @if ($_data->student->non_academic_clearance($item))
                                        @if ($_data->student->non_academic_clearance($item)->is_approved == 1)
                                            CLEARED
                                        @else
                                            <span style="color:red"><b> NOT CLEARED</b></span>
                                        @endif
                                    @else
                                        <span class="text-danger"><b>-</b></span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <th colspan="3">No Data</th>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

@endsection
