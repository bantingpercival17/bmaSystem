<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ 'PAYMENT RECEIPT - OR NUMBER:' . $data['orNumber'] }}</title>
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
        <div class="row" style="padding-bottom:0px; margin-top: 5px;">
            <div class="column-a">
                <table class="table table-bordered" style="margin-top: 16px;">
                    <tbody>
                        @for ($i = 0; $i < $_rows; $i++)
                            @php
                                if (count($_particular) > $i) {
                                    $_whole = '';
                                    $_decimal = '';
                                    foreach ($data['transactions'] as $key => $value) {
                                        if ($_particular[$i] == $key) {
                                            if ($key == 'Others') {
                                                if (count($value) > 0) {
                                                    foreach ($value as $key => $amount) {
                                                        if ($amount != 0) {
                                                            $_amount = explode('.', $amount);
                                                            $_total_amount += $amount;
                                                            $_whole = $_whole_1 = $_amount[0] ?? '';
                                                            $_decimal = $_decimal_1 = $_amount[1] ?? '00';
                                                        }
                                                    }
                                                }
                                            } else {
                                                if ($value != 0) {
                                                    $_amount = explode('.', $value);
                                                    $_total_amount += $value;
                                                    $_whole = $_whole_1 = $_amount[0] ?? '';
                                                    $_decimal = $_decimal_1 = $_amount[1] ?? '00';
                                                }
                                            }
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
                                $valueData = explode('.', $_total_amount);
                            @endphp
                            <td style="text-align: right;width:180px; "><label
                                    for="">{{ count($valueData) > 1 ? $valueData[0] : $_total_amount }}</label>
                            </td>
                            <td style="text-align: right;width:5px;"><label
                                    for="">{{ count($valueData) > 1 ? $valueData[1] : '00' }}</label>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="column-b">
                <div style="margin-top:50px; margin-right:30px;">
                    <p style="margin-top:3px; text-align:right;">
                        {{ $data['transactionDate'] }}
                    </p>
                    <div style="text-align:right;  padding:0px; margin:0px">
                        <label style="margin-right:90px;padding: 0px;">
                            {{ $data['fullname'] }}
                        </label>
                        <label style="padding: 0px; margin:0px">
                            {{ $data['student_number'] }}
                        </label>
                    </div>
                    <div style="margin-top:10px;position: relative; padding:0px;">
                        <label style="position:absolute; right:0; margin-left:90px; text-align:center;">
                            {{ Auth::user()->staff->amount_to_words($data['totalAmount']) }}
                        </label>
                        <label style="position:absolute; right:0; top:25px">
                            {{ number_format($data['totalAmount'], 2, '.', ',') }}
                        </label>
                    </div>
                    <br><br><br>
                    <p style="margin-left:120px; padding:0px;">
                        <span>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            {{ strtoupper($data['remarks']) }}
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </span>
                    </p>

                    <p style="margin-top:15px;margin-right:8%; text-align:right;">
                        {{ $data['staff'] }}
                    </p>
                </div>
            </div>
        </div>
    </main>

</body>

</html>
