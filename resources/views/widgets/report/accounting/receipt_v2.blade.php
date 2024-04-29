<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ 'PAYMENT RECEIPT - OR NUMBER:' . $_data->or_number }}</title>
    {{-- <link rel="stylesheet" href="{{ public_path('css/app-1.css') }}"> --}}
    <style>
        * {

            padding: 15px 10px 15px 10px;

            margin: 0;
        }

        .content {
            box-sizing: border-box;
        }

        .column-a,
        .column-b {
            margin: 0px;
            padding: 0px;
            height: 300px;
        }

        .column-a {
            float: left;
            width: 35%;
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .column-b {
            float: left;
            padding-left: 10px;
            width: 65%;
        }

        .col-equal {
            float: left;
            width: 50%;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .table,
        td,
        th {
            /* border: 1px solid; */
            margin: 0px;
            padding: 0px;
        }

        td {
            padding: 10px 0px 10px 0px;
        }

        .table {
            width: 85%;
            border-collapse: collapse;
        }



        .check-box {
            margin: 0px;
            padding: 0px;
        }

        .checkbox-container {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        }

        .checkbox-input {
            margin: 0 12px 0 0;
            vertical-align: middle;
            position: relative;
            top: 5px;
        }

        span {
            text-overflow: initial;
            font-weight: bold;
            font-style: italic;
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            /* text-decoration: underline; */
            /* border-bottom:1px solid #000000; width:100%; padding-bottom:5px; */
        }

        .header {
            padding: 0px;
            text-align: center;
            font-size: 12px;
        }

        .content-receipt {
            padding: 0px;
            position: relative;
            text-indent: 10%;
            font-size: 14px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sub-header {
            padding: 20px 0px 0px 0px;
            position: relative;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
        }

        ul.ul-details li {
            padding: 0px;
            margin: 0px;
            list-style-type: none;
            display: inline-block;
        }

        .img-header {
            filter: grayscale(100%);
            width: 60px;
            float: left;
            padding: 0px;
        }

        label {
            font-weight: bold;
            font-style: italic;
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
        }

        p {
            font-weight: bold;
            font-style: italic;
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
        }
    </style>
</head>

<body>
    @php
        $_particular = ['Tuition Fee', 'Uniform', 'Books', 'Forms', 'Others'];
        $_rows = 9;
        $_whole = 0;
        $_decimal = 0;
        $_whole_1 = 0;
        $_decimal_1 = 0;
        $_total_amount = 0;
    @endphp
    <main class="content">
        <div class="row" style="padding-bottom:0px;">
            <div class="column-a">
                <table class="table table-bordered" style="margin-top: 16px;">
                    <tbody>
                        @for ($i = 0; $i < $_rows; $i++)
                            @php
                                if (count($_particular) > $i) {
                                    if (
                                        strtolower(trim($_data->payment_transaction)) ==
                                        strtolower(trim($_particular[$i]))
                                    ) {
                                        // The the Particular in Database and the Receipt is match we will get the amount
                                        $_amount = explode('.', $_data->payment_amount);
                                        $_total_amount += $_data->payment_amount;
                                        if (count($_amount) > 1) {
                                            $_whole = $_whole_1 = $_amount[0];
                                            $_decimal = $_decimal_1 = $_amount[1];
                                        } else {
                                            $_whole = $_whole_1 = $_amount[0];
                                            $_decimal = $_decimal_1 = '00';
                                        }
                                    } else {
                                        if (strtolower(trim($_data->remarks)) == strtolower(trim($_particular[$i]))) {
                                            // The the Particular in Database and the Receipt is match we will get the amount
                                            $_amount = explode('.', $_data->payment_amount);
                                            $_total_amount += $_data->payment_amount;
                                            if (count($_amount) > 1) {
                                                $_whole = $_whole_1 = $_amount[0];
                                                $_decimal = $_decimal_1 = $_amount[1];
                                            } else {
                                                $_whole = $_whole_1 = $_amount[0];
                                                $_decimal = $_decimal_1 = '00';
                                            }
                                        } else {
                                            $_whole = '';
                                            $_decimal = '';
                                        }
                                    }
                                } else {
                                    $_whole = '';
                                    $_decimal = '';
                                }

                            @endphp
                            <tr>

                                <td style="text-align: right;width:180px; "><label
                                        for="">{{ $_whole }}</label></td>
                                <td style="text-align: right;width:5px;"><label
                                        for="">{{ $_decimal }}</label></td>
                            </tr>
                        @endfor
                        <tr>
                            @php
                                $data = explode('.', $_total_amount);
                            @endphp
                            <td style="text-align: right;width:180px; "><label
                                    for="">{{ $data[0] }}</label></td>
                            <td style="text-align: right;width:5px;"><label
                                    for="">{{ count($data) > 0 ? $data[1] : '00' }}</label>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="column-b">
                <div style="margin-top:50px; margin-right:30px;">
                    <p style="margin-top:3px; text-align:right;">
                        {{ $_data->transaction_date }}
                    </p>
                    <div style="text-align:right;  padding:0px; margin:0px">
                        <label style="margin-right:90px;padding: 0px;">
                            {{ strtoupper($_data->payment_assessment->enrollment_assessment->student->last_name . ', ' . $_data->payment_assessment->enrollment_assessment->student->first_name) }}
                        </label>
                        <label style="padding: 0px; margin:0px">
                            {{ $_data->payment_assessment->enrollment_assessment->student->account ? $_data->payment_assessment->enrollment_assessment->student->account->student_number : '-' }}
                        </label>
                    </div>
                    <div style="margin-top:10px;position: relative; padding:0px;">
                        <label style="position:absolute; right:0; margin-left:90px; text-align:center;">
                            {{ Auth::user()->staff->amount_to_words($_data->payment_amount) }}
                        </label>
                        <label style="position:absolute; right:0; top:25px">
                            {{ number_format($_data->payment_amount, 2, '.', ',') }}
                        </label>
                    </div>
                    <br><br><br>
                    <p style="margin-left:120px; padding:0px;">
                        <span>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            {{ strtoupper($_data->remarks) }}
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </span>
                    </p>

                    <p style="margin-top:25px;margin-right:8%; text-align:right;">
                        {{ $_data->staff->first_name . ' ' . $_data->staff->last_name }}
                    </p>
                    {{--  <div class="footer" style="padding:0px; font-size:14px;">
                        <ul class="ul-details" style="padding:10px 10px 0px 10px;">
                            <li>

                            </li>
                            <li>

                                <div style="padding: 15xp 15px 0px 0px; text-align: center;">
                                    <span
                                        style="font-size:12px; width:70%">{{ $_data->staff->first_name . ' ' . $_data->staff->last_name }}</span>

                                </div>

                            </li>
                        </ul>
                    </div> --}}
                </div>
            </div>
        </div>
    </main>

</body>

</html>
