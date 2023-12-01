@extends('widgets.report.app_report_template')
@section('title-report', 'BMA OBT-11: ' . strtoupper($_data->first_name . ' ' . $_data->last_name))
@section('form-code', 'OBT - 11')

@section('content')
    <main class="content">
        <div class="narative-report-summart">
            <h5 class="text-center mt-3">MONTHLY OBT PERFORMANCE MONITORING REPORT (MOPM)</h5>
            <div class="mt-3 mb-5">
                <table class="table-content">
                    <tbody>
                        <tr>
                            <td width="25%">
                                <small>NAME OF MIDSHIPMAN:</small>
                            </td>
                            <td class="text-fill-in" width="40%" colspan="2">
                                <small>
                                    <b>
                                        {{ strtoupper($_data->last_name) }},
                                        {{ strtoupper($_data->first_name) }}
                                        {{ strtoupper($_data->middle_name[0]) }}
                                    </b>
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <td><small>EMAIL ADDRESS:</small></td>
                            <td class="text-fill-in"><small><b>{{ $_data->account->personal_email }}</b></small></td>
                            <td width="120px"><small>CONTACT NUMBER:</small></td>
                            <td class="text-fill-in"><small><b>{{ strtoupper($_data->contact_number) }}</b></small></td>
                        </tr>
                        <tr>
                            <td><small>COURSE: </small></td>
                            <td class="text-fill-in">
                                <small><b>{{ $_data->enrollment_assessment->course->course_name }}</b></small>
                            </td>
                            <td width="120px"><small>OBT BATCH:</small></td>
                            <td class="text-fill-in">
                                <small><b>{{ strtoupper($_data->shipboard_training->sbt_batch) }}</b></small>
                            </td>
                        </tr>
                        <tr>
                            <td><small>COMPANY NAME: </small></td>
                            <td class="text-fill-in">
                                <small><b>{{ strtoupper($_data->shipboard_training->company_name) }}</b></small>
                            </td>
                            <td width="120px"><small>NAME OF VESSEL</small></td>
                            <td class="text-fill-in">
                                <small><b>{{ strtoupper($_data->shipboard_training->vessel_name) }}</b></small>
                            </td>
                        </tr>
                        <tr>
                            <td><small>VESSEL'S EMAIL ADDRESS: </small></td>
                            <td class="text-fill-in">
                                <small><b>-</b></small>
                            </td>
                            <td width="120px"><small>MASTER'S NAME:</small></td>
                            <td class="text-fill-in">
                                <small><b>-</b></small>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
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

            <table class="table-content">
                <tbody>
                    <tr>
                        <td width="250px;"><small>NARRATIVE REPORT RERIOD COVERED: </small></td>
                        <td class="text-fill-in">
                            <h6><b>{{ strtoupper(date('F - Y', strtotime(request()->input('_month')))) }}</b></h6>
                        </td>

                    </tr>

                </tbody>

            </table>
            <div class="document-content">
                <h6 class="mt-3">
                    NARRATIVE CONTENT
                </h6>

                @foreach ($_documents as $document)
                    <div class=" mt-3">
                        <span class="h6"> {{ strtoupper($document->journal_type) }}</span>
                        <p>
                            @if ($document->remark)
                                {{ $document->remark }}
                            @endif
                        </p>
                        @foreach (json_decode($document->file_links) as $links)
                            @php
                                $links = str_replace(':1000', '', $links);
                                $myFile = pathinfo($links);
                                $_ext = $myFile['extension'];
                            @endphp
                            @if ($_ext != 'pdf')
                                <img src="{{ $links }}" style="width:40%;">
                            @else
                                <a href="{{ $links }}">{{ $links }}</a>
                                <embed src="{{ $links }}" type="pdf" style="width:40%;">
                            @endif
                        @endforeach

                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
