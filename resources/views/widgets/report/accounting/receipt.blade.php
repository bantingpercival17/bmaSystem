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
            width: 30%;
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .column-b {
            float: left;
            padding-left: 10px;

            width: 70%;
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
            border: 1px solid;
            margin: 0px;
            padding: 0px;
        }

        td {
            padding: 0px 10px 0px 5px;
        }

        .table {
            width: 100%;
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
            text-decoration: underline;
            /* border-bottom:1px solid #000000; width:100%; padding-bottom:5px; */
        }

        .header {
            padding: 0px;
            text-align: center;
            font-size: 12px;
        }

        .content-receipt {
            padding: 0px;
            text-indent: 10%;
            font-size: 14px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sub-header {
            padding: 20px 0px 0px 0px;
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
    </style>
</head>

<body>
    @php
        $_particular = ['Tuition Fee', 'Uniform', 'Books', 'Forms', 'Others'];
        $_rows = 10;
        $_whole_1 = 0;
        $_decimal_1 = 0;
    @endphp
    <main class="content">
        <div class="row" style="padding-bottom:0px;">
            <div class="column-a">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th colspan="3" class="text-center fw-bolder">PAYMENT FOR:</th>
                        </tr>
                        <tr>
                            <th style="width: 40%">PARTICULAR</th>
                            <th colspan="2">AMOUNT</th>
                        </tr>
                        @for ($i = 0; $i < $_rows; $i++)
                            @php
                                $_amount = count($_particular) > $i ? (strtolower($_particular[$i]) == strtolower($_data->payment_transaction) ? explode('.', $_data->payment_amount) : '-') : '-';
                                $_amount = $_amount;
                                if (count($_particular) > $i) {
                                    if (strtolower($_particular[$i]) == strtolower($_data->payment_transaction)) {
                                        $_amount = explode('.', $_data->payment_amount);
                                        if (count($_amount) > 1) {
                                            $_whole = $_whole_1 = $_amount[0];
                                            $_decimal = $_decimal_1 = $_amount[1];
                                        } else {
                                            $_whole = $_whole_1 = $_amount[0];
                                            $_decimal = $_decimal_1 = '00';
                                        }
                                    } else {
                                        $_whole = '-';
                                        $_decimal = '-';
                                    }
                                } else {
                                    $_whole = '-';
                                    $_decimal = '-';
                                }
                                
                            @endphp
                            <tr>
                                <td>{{ count($_particular) > $i ? $_particular[$i] : '' }}</td>
                                <td style="text-align: right;">{{ $_whole }}</td>
                                <td style="width: 15%; text-align: right;">{{ $_decimal }}</td>
                            </tr>
                        @endfor
                        <tr>
                            <td></td>
                            <td style="text-align: right;">{{ $_whole_1 }}</td>
                            <td style="width: 15%; text-align: right;">{{ $_decimal_1 }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center fw-bolder">Form of Payment:</th>
                        </tr>
                        <tr style="padding: 5px 0px 0px 0px;">
                            <td colspan="3" style="padding: 5px 0px 0px 0px;">
                                <ul class="ul-details" style=" padding: 10px;">
                                    <li style="width:30px; padding: 0px;">
                                        <div class="checkbox-container" style="padding: 0px; margin:0px;">
                                            <label style="padding: 0px; margin:0px;">
                                                Cash <br>
                                                <input class="checkbox-input"type="checkbox"
                                                    style="padding: 0px; margin:0px; width=50px;" />
                                            </label>
                                        </div>
                                    </li>
                                    <li style="width:30px;  padding: 0px 10px 0px 0px;">
                                        <div class="checkbox-container" style="padding: 0px; margin:0px;">
                                            <label style="padding: 0px; margin:0px;">
                                                Check<br>
                                                <input class="checkbox-input"type="checkbox"
                                                    style="padding: 0px; margin:0px; width:50px;" />
                                            </label>
                                        </div>
                                    </li>
                                    <li style="width:90px;">
                                        <p style="padding: 0px;">Bank:_____________</p>
                                        <p style="padding: 0px;">Date:_____________</p>
                                        <p style="padding: 0px;">Check No:_____</p>
                                    </li>
                                </ul>


                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="column-b">
                <img src="{{ public_path() . '/assets/image/bma-logo-1.png' }}" class="img-header ">
                <div class="header">

                    <p style="padding:0px; font-size:20px; font-weigth:bolder;">
                        <b> BALIWAG MARITIME ACADEMY, INC</b>
                    </p>
                    <p style="padding:0px;">
                        Cagayan Valley Road, Sampaloc, San Rafael, Bulacan <br>
                        Non - Vat Reg TIN: 003-920-747-00000 <br>
                        Tel. No. (044) 766-1263 * 766-0316
                    </p>

                </div>
                <label style="float:right"><b>TR # {{ $_data->or_number }}</b></label>
                <br>
                <div class="sub-header">
                    <label><b>PROVISIONAL RECEIPT</b></label>
                    <label style="padding-left: 200px;">DATE<span>{{ $_data->transaction_date }}</span></label>
                </div>
                <div class="content-receipt">
                    <p style="padding:2px 5px 2px 15px;"> Received from
                        <span>
                            {{ strtoupper($_data->payment_assessment->enrollment_assessment->student->last_name . ', ' . $_data->payment_assessment->enrollment_assessment->student->first_name) }}
                        </span>
                        Student #
                        <span>
                            {{ $_data->payment_assessment->enrollment_assessment->student->account ? $_data->payment_assessment->enrollment_assessment->student->account->student_number : '-' }}
                        </span>
                        with address at <span
                            class="client-address">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </p>
                    <p style="padding:2px 5px 2px 15px; text-indent: 0%;">
                        The sum of pesos <span
                            class="amount-word">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ Auth::user()->staff->amount_to_words($_data->payment_amount) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        (P <span class="payment-amount">
                            {{ /* number_format($_data->payment_amount, 2, '.', ',') */ $_data->payment_amount }}
                        </span>)

                    </p>
                    <p style="padding: 2px 5px 0px 15px; text-indent: 0%;">
                        In partial/full payment of
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ strtoupper($_data->remarks) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </p>
                </div>
                <div class="footer" style="padding:0px; font-size:14px;">
                    <ul class="ul-details" style="padding:10px 10px 0px 10px;">
                        <li>
                            <table class="table table-borderd">
                                <tbody>
                                    <tr>
                                        <td colspan="2"><b>Sr. Citizen TIN</b></td>
                                    </tr>
                                    <tr>
                                        <td><b>OSCA/PWD ID No.</b></td>
                                        <td><b>Signature</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>
                        <li style="">
                            <div style="padding: 15xp 15px 0px 0px; text-align: center;">
                                By: <span
                                    style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <br>
                                Authorized Representative
                            </div>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <p style="padding: 0px 0px 0px 0px; background-color:rgb(0, 153, 255); width:auto;">
            <b> NOTE:</b> This is only a temporary receipt. BMA will issue an Official Receipt
            Once it is available
        </p>
    </main>

</body>

</html>
