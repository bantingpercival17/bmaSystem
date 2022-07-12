@extends('widgets.report.app_report_template')
@section('title-report', 'Summary Report : ' . strtoupper($_data->first_name . ' ' . $_data->last_name))
@section('form-code', 'OBTO')

@section('content')
    <main class="content">
        <div class="narative-report-summart">
            <h3 class="text-center">NARATIVE REPORT SUMMARY</h3>
            <br>
            <div class="mb-3">
                <table class="">
                    <tbody>
                        <tr>
                            <td>
                                STUDENT'S NAME:
                            </td>
                            <td class="text-fill-in" width="700px"><b>{{ strtoupper($_data->last_name) }},
                                    {{ strtoupper($_data->first_name) }}
                                    {{ strtoupper($_data->middle_name[0]) }}.</b></td>

                            <td width="50%">
                                COURSE:
                            </td>
                            <td class="text-fill-in">
                                <b>{{ $_data->enrollment_assessment->course->course_name }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>STUDENT NUMBER:</td>
                            <td class="text-fill-in"><b>{{ strtoupper($_data->account->student_number) }}</b></td>

                        </tr>

                    </tbody>

                </table>
            </div>
            <div class="report-table">

                <table class="table">
                    <thead class="text-center">
                        @php
                            $_narative_details = ['Training Record Book', 'Daily Journal', 'Crew List', "Master's Declaration of Safe Departure", 'Picture while at work'];
                            
                        @endphp
                        <tr>
                            <th><small><b>NARATIVE REPORT</b></small></th>
                            @foreach ($_narative_details as $details)
                                <th class="fw-bolder"><small><b>{{ strtoupper($details) }}</b></small></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($_data->narative_report) > 0)
                            @foreach ($_data->narative_report as $_journal)
                                <tr>
                                    <td>
                                        {{ date('F - Y', strtotime($_journal->month)) }}
                                    </td>
                                    @foreach ($_narative_details as $details)
                                        <td class="text-center">
                                            @if ($_data->single_narative_report($_journal->month, $details))
                                                <input type="checkbox"
                                                    {{ $_data->single_narative_report($_journal->month, $details)->is_approved == 1 ? 'checked' : '' }}>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No Journal</td>
                            </tr>
                        @endif

                    </tbody>
                </table>

            </div>
        </div>
    </main>
@endsection
