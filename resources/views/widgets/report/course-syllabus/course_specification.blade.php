@extends('widgets.report.app_report_template_v2')
@section('title-report', 'PART A: COURSE SPECIFICATION - ' . $_data->subject->subject_code)
@section('form-code', '')

@section('content')
    <div class="card-body">
        <p><b>A. COURSE SPECIFICATION</b></p>
        <div class="table-responsive-lg">
            <table class="table-content table-outline ">
                <tbody>
                    <tr>
                        <th>NAME OF PROGRAM</th>
                        <td width="10px;">:</td>
                        <td colspan="7">Bachelor of Science in Marine Transportation (BSMT)</td>
                    </tr>
                    <tr>
                        <th>COURSE CODE</th>
                        <td>:</td>
                        <td colspan="7">{{ $_data->subject->subject_code }}</td>
                    </tr>
                    <tr>
                        <th rowspan="3">COURSE DESCRIPTIVE TITLE</th>
                        <td rowspan="3">:</td>
                        <td rowspan="3" colspan="4">{{ $_data->subject->subject_name }}
                        </td>
                        <th>PREREQUISITE</th>
                        <td width="10px;">:</td>
                        <td>{{ $_data->prerequisite }}</td>
                    </tr>

                    <tr>
                        <th>CO-REQUISITE</th>
                        <td>:</td>
                        <td>{{ $_data->co_requisite }}</td>
                    </tr>
                    <tr>
                        <th>SEMESTER OFFERED</th>
                        <td>:</td>
                        <td>{{ $_data->semester }}</td>
                    </tr>
                    <tr>
                        <th>COURSE DESCRIPTION</th>
                        <td>:</td>
                        <td colspan="7">{{ $_data->description }}</td>
                    </tr>
                    <tr>
                        <th style="width: 250px";>COURSE CREDITS</th>
                        <td width="10px">:</td>
                        <td style="width: 150px;">2 units</td>
                        <th style="width: 200px;">LECTURE CONTACT HOURS PER WEEK</th>
                        <td style="width: 5px;">:</td>
                        <td style="width: 150px;">{{ $_data->subject->lecture_hours }} hour/s</td>
                        <th style="width: 250px;">LABORATORY CONTACT HOURS PER WEEK</th>
                        <td style="width: 5px;">:</td>
                        <td style="width: 150px;">{{ $_data->subject->lecture_hours }} hour/s</td>
                    </tr>
                    <tr>
                        <th rowspan="{{ $_data->stcw_reference ? count($_data->stcw_reference) + 1 : 2 }}">STCW REFERENCE
                        </th>
                        <td rowspan="{{ $_data->stcw_reference ? count($_data->stcw_reference) + 1 : 2 }}" width="5px">:
                        </td>
                        <th class="text-center" style="text-align: center">STCW Table</th>
                        <th colspan="1" class="text-center" style="text-align: center">Function</th>
                        <th colspan="2" class="text-center" style="text-align: center">Competence</th>
                        <th colspan="3" class="text-center" style="text-align: center">Knowledge, Understanding and
                            Proficiency</th>

                    </tr>
                    @if ($_data->stcw_reference)
                        @foreach ($_data->stcw_reference as $reference)
                            <tr>
                                <td class="text-center">{{ $reference->stcw_table }}</td>
                                <td colspan="1" class="text-center">
                                    @if (count($reference->function_content) > 0)
                                        @foreach ($reference->function_content as $function)
                                            <p>
                                                @php
                                                    echo $function->function_content;
                                                @endphp
                                            </p>
                                        @endforeach
                                    @endif
                                </td>
                                <td colspan="2" class="text-center">
                                    @if (count($reference->function_content) > 0)
                                        @foreach ($reference->function_content as $function)
                                            @if (count($function->competence_content) > 0)
                                                @foreach ($function->competence_content as $competence)
                                                    <p>
                                                        @php
                                                            echo $competence->competence_content;
                                                        @endphp
                                                    </p>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td colspan="3" class="text-center">
                                    @if (count($reference->function_content) > 0)
                                        @foreach ($reference->function_content as $function)
                                            @if (count($function->competence_content) > 0)
                                                @foreach ($function->competence_content as $competence)
                                                    @if (count($competence->kup_content) > 0)
                                                        @foreach ($competence->kup_content as $kup)
                                                            <p>
                                                                @php
                                                                    echo $kup->kup_content;
                                                                @endphp
                                                            </p>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center">N/A</td>
                            <td colspan="2" class="text-center">N/A</td>
                            <td colspan="2" class="text-center">N/A</td>
                            <td colspan="2" class="text-center">N/A</td>
                        </tr>
                    @endif


                    @if ($_data->course_outcome)
                        <tr>
                            <th rowspan="{{ $_data->course_outcome ? count($_data->course_outcome) : 0 }}">COURSE OUTCOME
                            </th>
                            <td rowspan="{{ $_data->course_outcome ? count($_data->course_outcome) : 0 }}"width="5px">:
                            </td>
                            <td colspan="2">
                                {{ $_data->course_outcome[0]->program_outcome }}
                            </td>
                            <td colspan="5">
                                {{ $_data->course_outcome[0]->course_outcome }}
                            </td>
                        </tr>

                        @foreach ($_data->course_outcome as $key => $item)
                            @if ($key != 0)
                                <tr>

                                    <td colspan="2">
                                        {{ $item->program_outcome }}
                                    </td>
                                    <td colspan="5">
                                        {{ $item->course_outcome }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <th rowspan="{{ $_data->course_outcome ? count($_data->course_outcome) : 0 }}">COURSE OUTCOME
                            </th>
                            <td rowspan="{{ $_data->course_outcome ? count($_data->course_outcome) : 0 }}"width="5px">:
                            </td>
                            <td colspan="2">N/A</td>
                            <td colspan="5">N/A</td>
                        </tr>
                    @endif


                    <tr>
                        <th>COURSE INTAKE LIMITATIONS</th>
                        <td width="5px">:</td>
                        <td colspan="7">
                            @if ($_data->details)
                                @php
                                    echo $_data->details->course_intake_limitations;
                                @endphp
                            @else
                                NO CONTENT
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>FACULTY REQUIREMENTS</th>
                        <td width="5px">:</td>
                        <td colspan="7">
                            @if ($_data->details)
                                @php
                                    echo $_data->details->faculty_requirements;
                                @endphp
                            @else
                                NO CONTENT
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>TEACHING FACILITIES & EQUIPMENT</th>
                        <td width="5px">:</td>
                        <td colspan="7">
                            @if ($_data->details)
                                @php
                                    echo $_data->details->teaching_facilities_and_equipment;
                                @endphp
                            @else
                                NO CONTENT
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>TEACHING AIDS</th>
                        <td width="5px">:</td>
                        <td colspan="7">
                            @if ($_data->details)
                                @if ($_data->details->teaching_aids && $_data->details->teaching_aids != 'N/A')
                                    @foreach (json_decode($_data->details->teaching_aids) as $item)
                                        <p>
                                            {{ trim($item) }}
                                        </p>
                                    @endforeach
                                @else
                                    {{ $_data->details->references }}
                                @endif
                            @else
                                NO CONTENT
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>REFERENCE/S</th>
                        <td width="5px">:</td>
                        <td colspan="7">
                            @if ($_data->details)
                                @if ($_data->details->references && $_data->details->references != 'N/A')
                                    @foreach (json_decode($_data->details->references) as $item)
                                        <p>
                                            {{ trim($item) }}
                                        </p>
                                    @endforeach
                                @else
                                    {{ $_data->details->references }}
                                @endif
                            @else
                                NO CONTENT
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


    </div>
    {{-- <div class="page-break"></div>
    <div class="page-break"></div> --}}
@endsection
