@extends('widgets.report.app_report_template')
@section('title-report', 'BMA OBT-12: ' . strtoupper($_data->first_name . ' ' . $_data->last_name))
@section('form-code', 'OBT - 12')

@section('content')
    <main class="content">
        <h5 class="text-center ">ASSESSMENT FOR INCOMING 1/C -
            {{ $_data->enrollment_assessment->course_id == 2 ? 'DECK CADET' : ' ENGINE CADET' }}</h5>

        <div class="mt-3 mb-5">
            <table class="table-content">
                <tbody>
                    <tr>
                        <td width="150px">
                            <small>NAME OF MIDSHIPMAN:</small>
                        </td>
                        <td class="text-fill-in" width="40%">
                            <small>
                                <b>
                                    {{ strtoupper($_data->last_name) }},
                                    {{ strtoupper($_data->first_name) }}
                                    {{ strtoupper($_data->middle_name[0]) }}
                                </b>
                            </small>
                        </td>
                        <td width="120px"><small>OBT BATCH:</small></td>
                        <td class="text-fill-in">
                            <small><b>{{ strtoupper($_data->shipboard_training->sbt_batch) }}</b></small>
                        </td>
                    </tr>

                    <tr>
                        <td><small>COURSE: </small></td>
                        <td class="text-fill-in">
                            <small><b>{{ $_data->enrollment_assessment->course->course_name }}</b></small>
                        </td>
                        <td width="120px"><small>SCORE:</small></td>
                        <td class="text-fill-in">
                            <small><b>{{$_assessment['total_score']}}</b></small>
                        </td>
                    </tr>
                    <tr>
                        <td><small>DATE OF ASSESSMENT: </small></td>
                        <td class="text-fill-in">
                            <small><b>{{ strtoupper($_data->onboard_examination->examination_start) }}</b></small>
                        </td>
                        <td width="120px"><small>REMAKS:</small></td>
                        <td class="text-fill-in">
                            <small><b>{{ $_assessment['total_score'] >= 50 ? 'PASSED' : 'FAILED' }}</b></small>
                        </td>
                    </tr>

                </tbody>

            </table>
        </div>
        <div class="grading-formula">
            <label for="" class="fw-bolder"><i><b>NOTE: GRADING FORMULA</b></i></label>
            <table class="table-content">
                <tbody>
                    <tr>
                        <td><i><b>A. Written Exam: (30%)</b></i></td>
                        <td> <u><b>{{ $_assessment['written_score'] }}</b></u> / 40</td>
                        <td>x 100</i> </td>
                        <td>x 30%</td>
                        <td><u><b>{{ number_format($_assessment['written_final_score'], 1, '.', '') }}</b></u></td>
                    </tr>
                    <tr>
                        <td><i><b>B. Practical Assessment: (30%)</b></i></td>
                        <td> <u><b>{{ number_format($_assessment['practical_score'], 1, '.', '') }}</b></u> /
                            {{ $_assessment['practical_item'] }}</td>
                        <td>x 100</i> </td>
                        <td>x 30%</td>
                        <td><u><b>{{ number_format($_assessment['practical_final_score'], 1, '.', '') }}</b></u></td>
                    </tr>
                    <tr>
                        <td><i><b>C. Oral Interview: (40%)</b></i></td>
                        <td> <u><b>{{ $_assessment['oral_score'] }}</b></u> / {{ $_assessment['oral_item'] }}</td>
                        <td>x 100</i> </td>
                        <td>x 40%</td>
                        <td><u><b>{{ number_format($_assessment['oral_final_score'], 1, '.', '') }}</b></u></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right; padding-right:10px; "><b>FINAL GRADE</b> </td>
                        <td style="color:{{ $_assessment['total_score'] >= 50 ? '#0275d8' : '#d9534f' }};">
                            <b><u>{{ number_format($_assessment['total_score'], 1, '.', '') }}</u></b>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="remark">
            <label for="" class="fw-bolder"><i><b>REMARKS</b></i></label>
            <table class="table-content">
                <tbody>
                    <tr>
                        <td><i><b>Practical Assessment: </b></i></td>
                        <td> <u><b>{{-- {{ $_result['practical_remark'] }} --}}</b></u> </td>
                    </tr>
                    <tr>
                        <td><i><b>Oral Interview: </b></i></td>
                        <td> <u><b>{{-- {{ $_result['oral_remark'] }} --}}</b></u></td>
                    </tr>
                </tbody>
            </table>
            <div class="remark mt-3">
                <label for="" class="fw-bolder"><i><b>LEGEND</b></i></label>
                <table class="table-content">
                    <tbody>
                        <tr>
                            <td><i><b>PASSED 50-100% </b></i></td>
                            <td style="text-align: center">
                                <h6>{{ strtoupper(ucwords($_data->first_name . ' ' . $_data->last_name)) }}</h6>
                            </td>
                        </tr>
                        <tr>
                            <td><i><b>FAILED 0-49% </b></i></td>
                            <td style="text-align: center"><i>Signature of Midshipmen</i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel mt-3">
                <label for="" class="fw-bolder"><i><b>PANEL</b></i></label>
                <table class="table-content">
                    <tbody>
                        <tr style="text-align: center">
                            <td><i><b>2M Raymond A. Carlos</b></i></td>
                            <td> <i><b>{{ $_assessment['assesor'] }}</b></i></td>
                        </tr>
                        <tr style="text-align: center">
                            <td><i>OIC - On board Training Officer</i> </td>
                            <td><i>Name & Signature of Asssessor</i></td>
                        </tr>
                        <tr style="text-align: center">
                            <td colspan="2"><br><br><br> <b><i>Capt. Maximo M. Pestaño</i></b></td>
                        </tr>
                        <tr style="text-align: center">
                            <td colspan="2">School Director / Dean of Maritime Studies</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        {{-- <br><br>
    <div class="grading-formula">
        <h6><b><i>Note: Grading Formula</i></b></h6>
        <table class="table">
            <tbody>
                <tr>
                    <td><i><b>A. Written Exam: (30%)</b></i></td>
                    <td> <u><b>{{ $_result['written_score'] }}</b></u> / 40</td>
                    <td>x 100</i> </td>
                    <td>x 30%</td>
                    <td><u><b>{{ number_format($_result['written_final'], 1, '.', '') }}</b></u></td>
                </tr>
                <tr>
                    <td><i><b>B. Practical Assessment: (30%)</b></i></td>
                    <td> <u><b>{{ number_format($_result['practical_score'], 1, '.', '') }}</b></u> /
                        {{ $_result['practical_item'] }}</td>
                    <td>x 100</i> </td>
                    <td>x 30%</td>
                    <td><u><b>{{ number_format($_result['practical_final'], 1, '.', '') }}</b></u></td>
                </tr>
                <tr>
                    <td><i><b>C. Oral Interview: (40%)</b></i></td>
                    <td> <u><b>{{ $_result['oral_score'] }}</b></u> / {{ $_result['oral_item'] }}</td>
                    <td>x 100</i> </td>
                    <td>x 40%</td>
                    <td><u><b>{{ number_format($_result['oral_final'], 1, '.', '') }}</b></u></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right; padding-right:10px; "><b>FINAL GRADE</b> </td>
                    <td style="color:{{ $_result['total_score'] >= 50 ? '#0275d8' : '#d9534f' }};">
                        <b><u>{{ number_format($_result['total_score'], 1, '.', '') }}</u></b>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="remark">
        <h5><b><i>Remarks</i></b></h5>
        <table class="table">
            <tbody>
                <tr>
                    <td><i><b>Practical Assessment: </b></i></td>
                    <td> <u><b>{{ $_result['practical_remark'] }}</b></u> </td>
                </tr>
                <tr>
                    <td><i><b>Oral Interview: </b></i></td>
                    <td> <u><b>{{ $_result['oral_remark'] }}</b></u></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br><br>
    <div class="remark">
        <h5><b><i>Legend:</i></b></h5>
        <table class="table">
            <tbody>
                <tr>
                    <td><i><b>PASSED 50-100% </b></i></td>
                    <td style="text-align: center">
                        <h4>{{ strtoupper(ucwords($_student->first_name . ' ' . $_student->last_name)) }}</h4>
                    </td>
                </tr>
                <tr>
                    <td><i><b>FAILED 0-49% </b></i></td>
                    <td style="text-align: center"><i>Signature of Midshipmen</i></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br><br>
    <div class="panel">
        <h5><b><i>Panel</i></b></h5>
        <br><br><br>
        <table class="table">
            <tbody>
                <tr style="text-align: center">
                    <td><i><b>2/M Dominador P. Tunzon Jr.</b></i></td>
                    <td> <i><b>{{ $_result['assesor_name'] }}</b></i></td>
                </tr>
                <tr style="text-align: center">
                    <td><i>On board Training Officer</i> </td>
                    <td><i>Name & Signature of Asssessor</i></td>
                </tr>
                <tr style="text-align: center">
                    <td colspan="2"><br><br><br> <b><i>Capt. Maximo M. Pestaño</i></b></td>
                </tr>
                <tr style="text-align: center">
                    <td colspan="2">School Director / Dean of Maritime Studies</td>
                </tr>
            </tbody>
        </table>
    </div> --}}
    </main>
@endsection
