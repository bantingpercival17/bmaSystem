{{-- @extends('widgets.report.grade.report_layout_1') --}}
@extends('widgets.report.app_report_template')
@section('title-report', 'MIDSHIPMAN QR-Code - ' . strtoupper($_student->last_name . ', ' . $_student->first_name))
@section('form-code', 'QR-CODE')
@section('style')
    <style>
        .account-card-title {
            text-align: center;
            font-weight: 700;
            width: 100%;
        }

        .account-table {
            font-family: Arial, Helvetica, sans-serif;
        }

        .account-table td,
        .account-table th {
            padding: 0px 0px 0px 0px;
            font-size: 10px;


        }

        .account-table th,
            {
            text-align: center;
        }

        .checkbox-container {
            padding-top: 15px;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        }

        .checkbox-input {
            margin: 0 14px 0 14px;
            vertical-align: middle;
            position: relative;
            top: 3px;
        }
    </style>
@endsection
@section('content')
    <div class="content">
        <br>
        <table class="table-content table-2">
            <thead>
                <tr>
                    <th>QR CODE</th>
                    <td>LAST NAME</td>
                    <td>FIRST NAME</td>
                    <td>MIDDLE NAME</td>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        <img src="data:image/png;base64, {!! base64_encode(
                            QrCode::style('round', 0.5)->eye('square')->size(200)->generate(
                                    $_student->account->student_number . '.' . mb_strtolower(str_replace(' ', '', $_student->last_name)),
                                ),
                        ) !!} "> <br>
                        {{ $_student->account->student_number . '.' . mb_strtolower(str_replace(' ', '', $_student->last_name)) }}
                    </td>
                    <td>{{ strtoupper($_student->last_name) }}</td>
                    <td>{{ strtoupper($_student->first_name) }}</td>
                    <td>{{ strtoupper($_student->middle_name) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
