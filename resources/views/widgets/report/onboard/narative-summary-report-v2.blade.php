@extends('widgets.report.main-report-template')
@section('title-report', 'Summary Report : ' . strtoupper($_data->first_name . ' ' . $_data->last_name))
@section('form-code', 'OBTO')

@section('content')
    <main class="page-content">
        <div class="narative-report-summart">
            <h3 class="text-center">MONTHLY OBT PERFORMANCE MONITORING REPORT (MOPM)</h3>
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
                <table class="table table-onboard">
                    <thead class="text-center">
                        @php
                            $_narative_details = ['Training Record Book', 'Daily Journal', 'Crew List', "Master's Declaration of Safe Departure", 'Picture while at work'];
                            $narativeDetails = ['PAGE OF TRAINING RECORD BOOK', 'PAGE OF SHIPS LOGBOOK', 'ON THE JOB PHOTOS', 'CREWLIST OF MONTH', 'MDSD FOR THE MONTH', 'COPY THE DAILY JOURNAL'];
                        @endphp
                        <tr>
                            <th><small><b>NARRATIVE REPORT</b></small></th>
                            @foreach ($narativeDetails as $details)
                                <th class="fw-bolder"><small><b>{{ strtoupper($details) }}</b></small></th>
                            @endforeach
                            <th>REMARKS</th>
                        </tr>

                    </thead>
                    <tbody>
                        @forelse ($_data->shipboard_training->performance_report as $item)
                            <tr>
                                <td>{{ $item->month }}
                                </td>
                                @foreach ($narativeDetails as $details)
                                    <td class="text-center">
                                        @forelse ($item->document_attachments  as $documents)
                                            @if (strtoupper($documents->journal_type) === strtoupper($details))
                                                <input type="checkbox" {{ $documents->is_approved == 1 ? 'checked' : '' }}>
                                            @endif
                                        @empty
                                            NO DATA
                                        @endforelse
                                    </td>
                                @endforeach
                                <td>
                                    @php
                                        $totalDocuments = $item->document_attachments->count();
                                        $totalApprovedDocuments = $item->approved_document_attachments->count();
                                        echo $totalDocuments === $totalApprovedDocuments ? 'Completed' : 'Ongoing Checking';
                                    @endphp
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($_narative_details) + 1 }}">No Journal</td>
                                </tr>
                            @endforelse
                            {{--  @if (count($_data->narative_report) > 0)
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
                        @endif --}}

                        </tbody>
                    </table>

                </div>
            </div>
        </main>
    @endsection
