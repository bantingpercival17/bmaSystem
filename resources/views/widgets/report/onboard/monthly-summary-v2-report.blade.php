@extends('widgets.report.main-report-template')
@section('title-report', 'BMA OBT-11: ' . strtoupper($data->first_name . ' ' . $data->last_name))
@section('form-code', 'OBT - 11')

@section('content')
    <div class="page-content">
        <div class="narative-report-summart">
            <h5 class="text-center mt-3">MONTHLY OBT PERFORMANCE MONITORING REPORT (MOPM)</h5>

            <small for="" class="" style="font-size: 12px;">DATE: <b>{{ $narrative->month }}</b></small>
            <table class="table-content table-header" style="font-size: 12px">
                <tbody>
                    <tr>
                        <td width="25%">
                            <small>NAME OF MIDSHIPMAN:</small>
                        </td>
                        <td class="text-fill-in" colspan="3">
                            <small>
                                <b>
                                    {{ strtoupper($data->last_name) }},
                                    {{ strtoupper($data->first_name) }}
                                    {{ strtoupper($data->middle_initial) }}
                                </b>
                            </small>
                        </td>
                    </tr>
                    <tr>
                        <td><small>EMAIL ADDRESS:</small></td>
                        <td class="text-fill-in"><small><b>{{ $data->account->personal_email }}</b></small></td>
                        <td><small>CONTACT NUMBER:</small></td>
                        <td class="text-fill-in"><small><b>{{ strtoupper($data->contact_number) }}</b></small></td>
                    </tr>
                    <tr>
                        <td><small>COURSE: </small></td>
                        <td class="text-fill-in" style="width: 200px">
                            <small><b>{{ $data->enrollment_assessment->course->course_name }}</b></small>
                        </td>
                        <td><small>OBT BATCH:</small></td>
                        <td class="text-fill-in">
                            <small><b>{{ strtoupper($data->shipboard_training->sbt_batch) }}</b></small>
                        </td>
                    </tr>
                    <tr>
                        <td><small>COMPANY NAME: </small></td>
                        <td class="text-fill-in">
                            <small><b>{{ strtoupper($data->shipboard_training->company_name) }}</b></small>
                        </td>
                        <td><small>NAME OF VESSEL:</small></td>
                        <td class="text-fill-in">
                            <small><b>{{ strtoupper($data->shipboard_training->vessel_name) }}</b></small>
                        </td>
                    </tr>
                    <tr>
                        <td><small>VESSEL'S EMAIL ADDRESS: </small></td>
                        <td class="text-fill-in">
                            <small><b>-</b></small>
                        </td>
                        <td><small>MASTER'S NAME:</small></td>
                        <td class="text-fill-in">
                            <small><b>-</b></small>
                        </td>
                    </tr>
                </tbody>

            </table>
            <div class="details">
                <p><small><i>Guidelines</i></small></p>
                <ol>

                    <small>
                        <li>
                            <i>
                                Accomplished Daily Journal for the month covered including front page and summary checklist
                                make sure never missed any single specified on it.
                            </i>
                        </li>
                        <li>

                            <i>
                                Narrative Report must have content of the daily task onboard including your job stated at
                                TRB
                                and Daily Journal Watchkeeping. (Refer to OPT for guidance)
                            </i>
                        </li>
                        <li>
                            <i>Accomplished data on TRB for the month covered including front page and summary checklist.
                                (Make sure never missed any single specified on it.)</i>
                        </li>
                        <li>
                            <i>Picture while working & condition onboard and during meal time.
                            </i>
                        </li>
                        {{--  <li>
                        <i>
                            Accomplished data on Daily Journal for the month covered including front page and summary
                            checklist. (Make sure never missed any single specified on it.)
                        </i>
                    </li> --}}
                        <li>
                            <i>
                                Crewlist and MDSD for the month covered with signature.
                            </i>
                        </li>
                        <li>
                            <i>MDSD for the month covered with signature (Domestic Only)</i>
                        </li>
                    </small>


                </ol>
            </div>

            <table class="table-content table-header">
                <tbody>
                    <tr>
                        <td width="320px"><small>NARRATIVE REPORT RERIOD COVERED: </small></td>
                        <td class="text-fill-in" style="text-align: center">
                            <b>{{ strtoupper($narrative->month) }}</b>
                        </td>

                    </tr>
                    <tr>
                        <td width="350px"> CONTENT: Summary tasks preferred for the Month of </td>
                        <td class="text-fill-in" style="text-align: center">
                            <b>{{ strtoupper($narrative->month) }}</b>
                        </td>

                    </tr>
                </tbody>

            </table>
            <div class="document-content">
                <table class="table table-summary-grade">
                    <thead>
                        <tr>
                            <th> Tasks as per TRB</th>
                            <th>CODE</th>
                            <th>Date Preferred</th>
                            <th>Inputted to Daily Journal</th>
                            <th>Signed by Officer/Master</th>
                            <th>Remarks is learning acquired</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th> {{ $narrative->task_trb }}</th>
                            <th>{{ $narrative->trb_code }}</th>
                            <th>{{ $narrative->date_preferred }}</th>
                            <th>{{ $narrative->daily_journal == 1 ? 'YES' : 'NO' }}</th>
                            <th>{{ $narrative->have_signature == 1 ? 'YES' : 'NO' }}</th>
                            <th>{{ $narrative->remarks }}</th>
                        </tr>
                        <tr>
                            <th colspan="6">Photos/Objectives Evidence (Attachment to Report)</th>
                        </tr>
                        @if (count($narrative->document_attachments))
                            @foreach ($narrative->document_attachments as $key => $documents)
                                <tr>
                                    <td colspan="6">
                                        {{ $key + 1 }}. {{ $documents->journal_type }}
                                        @php
                                            $files = json_decode($documents->file_links);
                                            $fileWidth = 90;
                                        @endphp
                                        <table class="table-remove-border">
                                            <tbody>
                                                @if (count($files) > 1)
                                                    @php
                                                        $count = 0;
                                                    @endphp
                                                    @foreach (json_decode($documents->file_links) as $links)
                                                        @php
                                                            $modValue = $count % 3;
                                                            if ($modValue === 0) {
                                                                echo '<tr>';
                                                            }
                                                            if ($modValue === 3) {
                                                                echo '</tr>';
                                                            }
                                                            $count += 1;
                                                        @endphp
                                                        <td>
                                                            @php
                                                                $myFile = pathinfo($links);
                                                                $_ext = $myFile['extension'];
                                                            @endphp
                                                            @if ($_ext != 'pdf')
                                                                <img style="position:inherit;" src="{{ $links }}"
                                                                    width="{{ $fileWidth }}%">
                                                            @else
                                                                <object data="{{ $links }}" type="application/pdf"
                                                                    width="100%" height="500px">
                                                                    <p>Your browser does not support PDFs. <a
                                                                            href="{{ $links }}">Download the
                                                                            PDF</a>
                                                                        instead.
                                                                    </p>
                                                                </object>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>
                                                            @foreach (json_decode($documents->file_links) as $links)
                                                                @php
                                                                    $myFile = pathinfo($links);
                                                                    $_ext = $myFile['extension'];
                                                                @endphp
                                                                @if ($_ext != 'pdf')
                                                                    <img style="position:inherit;"
                                                                        src="{{ $links }}"
                                                                        width="{{ $fileWidth }}%">
                                                                @else
                                                                    <object data="{{ $links }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px">
                                                                        <p>Your browser does not support PDFs. <a
                                                                                href="{{ $links }}">Download the
                                                                                PDF</a>
                                                                            instead.
                                                                        </p>
                                                                    </object>
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>

                                        </table>
                                        {{--  <div>
                                            @foreach (json_decode($documents->file_links) as $links)
                                                @php
                                                    $myFile = pathinfo($links);
                                                    $_ext = $myFile['extension'];
                                                @endphp
                                                @if ($_ext != 'pdf')
                                                    <img style="position:inherit;" src="{{ $links }}"
                                                        width="{{ $fileWidth }}%">
                                                @else
                                                    <object data="{{ $links }}" type="application/pdf"
                                                        width="100%" height="500px">
                                                        <p>Your browser does not support PDFs. <a
                                                                href="{{ $links }}">Download the PDF</a>
                                                            instead.
                                                        </p>
                                                    </object>
                                                @endif
                                            @endforeach
                                        </div> --}}

                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <th colspan="6">No Documents</th>
                            </tr>
                        @endif
                    </tbody>
                </table>
                @if (count($narrative->document_attachments))
                    @foreach ($narrative->document_attachments as $documents)
                    @endforeach
                @else
                @endif
            </div>
        </div>
    </div>
@endsection
