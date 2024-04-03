@extends('widgets.report.app_report_template_v2')
@section('title-report', 'EXAMINATION SYSTEM LOGS - ' . $_data->applicant->applicant_number)
@section('form-code', '')

@section('content')
    <div class="page-content">
        <h3 style="text-align: center"><b>EXAMINATION LOGS</b></h3>
        <table class="table-content">
            <tbody>
                <tr>
                    <th style="width: 25%">APPLICANT NAME:</th>
                    <td style="width: 28%">
                        {{ strtoupper($_data->applicant->applicant->last_name . ' ' . $_data->applicant->applicant->first_name) }}
                    </td>
                    <th style="width: 25%"> EXAMINATION CODE:</th>
                    <td style="text-align: left"> {{ $_data->examination_code }}</td>
                </tr>
                <tr>
                    <th>EXAMINATION START:</th>
                    <td>{{ $_data->examination_start }}</td>
                    <th>EXAMINATION END:</th>
                    <td> {{ $_data->updated_at }}</td>
                </tr>
            </tbody>
        </table>

        @php
            $contentNumber = 0;
            $contentCount = 55;
        @endphp
        <table class="table-content table-subject-grade" style="margin-top: 20px; ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>EXAMINATION ID</th>
                    <th>QUESTION ID</th>
                    <th>CHOICES ID</th>
                    <th>IS_REMOVED</th>
                    <th>CREATED_AT</th>
                    <th>UPDATED_AT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($_data->examination_questioner as $item)
                    @php
                        $contentNumber += 1;
                    @endphp
                    <tr class="{{ $contentNumber >= $contentCount ? 'page-break' : '' }}">
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->examination_id }}</td>
                        <td>{{ $item->question_id }}</td>
                        <td>{{ $item->choices_id }}</td>
                        <td>{{ $item->is_removed }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                    </tr>
                    @if ($contentNumber >= $contentCount)
                        @php
                            $contentNumber = 0;
                        @endphp
                    @endif
                @endforeach

            </tbody>
        </table>
    </div>

@endsection
