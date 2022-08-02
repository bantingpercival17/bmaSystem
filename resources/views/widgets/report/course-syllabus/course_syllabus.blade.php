@extends('widgets.report.app_report_template_v2')
@section('title-report', 'PART C: COURSE SYLLABUS - ' . $_data->subject->subject_code)
@section('form-code', '')

@section('content')
    <div class="card-body">
        <p><b>PART C: COURSE SYLLABUS</b></p>
        <div class="table-responsive">
            <table class="table-content table-outline">
                <thead class="table-center">
                    <tr>
                        <th style="width: 10%">COURSE<br>OUTCOME</th>
                        <th>TOPIC <br> <small>Learning Outcomes</small></th>
                        <th style="width: 15%">
                            REFERENCES / BIBLIOGRAPHIES
                        </th>
                        <th style="width: 15%">TEACHING AIDS</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($_data->learning_outcomes) > 0)
                        @foreach ($_data->learning_outcomes as $count => $learning_outcome)
                            <tr>
                                <td style="text-align: center">
                                    {{ substr(strtoupper($learning_outcome->course_outcome->course_outcome), 0, 3) }}
                                </td>
                                <td>
                                    <b> {{ $count + 1 . '. ' . $learning_outcome->learning_outcomes }}
                                    </b>
                                </td>
                                <td>
                                    @if ($learning_outcome->reference && $learning_outcome->reference != 'null')
                                        @foreach (json_decode($learning_outcome->reference) as $key => $item)
                                            {{ substr($item, 0, 3) }}
                                            {{ count(json_decode($learning_outcome->reference)) > $key + 1 ? ' , ' : '' }}
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if ($learning_outcome->teaching_aids && $learning_outcome->teaching_aids != 'null')
                                        @foreach (json_decode($learning_outcome->teaching_aids) as $key => $item)
                                            {{ substr($item, 0, 3) }}
                                            {{ count(json_decode($learning_outcome->teaching_aids)) > $key + 1 ? ' , ' : '' }}
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2">NO DATA</td>

                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
