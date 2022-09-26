<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title-report')</title>
    <style>
        @page {
            margin: 0.1in 0.1in 0.9in 0.1in;
        }

        * {
            padding: 0;
            font-family: "Times New Roman", Times, serif;
        }

        header {
            position: fixed;
            top: 10px;
            left: 0px;
            right: 0px;
            text-align: left;
            justify-content: center;
            /* text-align: left; */
        }

        b {
            font-family: Helvetica, sans-serif;
        }

        h4>b {
            font-family: "Times New Roman", Times, serif;
        }

        footer {
            position: fixed;
            bottom: 30px;
            left: 20px;
            right: 0px;
            justify-content: center;
        }

        .content-1 {
            position: relative;
            top: 1.3in;
            margin-right: 25px;
            margin-left: 25px;

        }

        .content {
            position: relative;
            top: 1in;
            margin-right: 25px;
            margin-left: 25px;

        }

        .align {
            text-align: right;
            width: 20px;
            padding-right: 10px;
        }

        .page-break {
            page-break-after: always;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .col-2 {
            width: 50%
        }

        .table {
            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
            width: 100%;
            /*  border: 1px solid #ddd;
            border: 1px solid #ddd; */
        }

        .table td,
        .table th {
            padding-top: 5px;
            padding-bottom: 5px;
            /* border: 1px solid #ddd; */
            font-size: 14px;

        }

        .table-2 {
            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
            border: 1px solid #ddd;
            margin-top: 20px
        }

        .table-2 td,
        .table-2 th {
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 10px;
            border: 1px solid #ddd;
            font-size: 12px;

        }

        .table-2 th {
            padding-top: 5px;
            padding-bottom: 5px;

            text-align: center;
        }

        .note {
            font-size: 12px;
        }

        .attendance-table {
            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid rgb(0, 0, 0);
            margin-top: 10px
        }

        .attendance-table td,
        .attendance-table th {
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 10px;
            border: 1px solid rgb(0, 0, 0);
            font-size: 12px;

        }

        .attendance-table th {
            padding-top: 5px;
            padding-bottom: 5px;
            text-align: center;
        }

        .form-code {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            padding-left: 10px;
            margin-top: -10px
        }

        .table-content {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid rgb(0, 0, 0);
            margin-top: 20px;
        }

        .table-content td,
        .table-content th {
            padding: 5px;
            border: 1px solid rgb(0, 0, 0);

        }

        .table-content th {
            font-size: 12px;
        }

        .table-content td {
            font-size: 12px;
        }
    </style>
</head>

<body>

    <header>

        <img src="{{ public_path() . '/assets/image/report-header.png' }}" alt="page-header">
    </header>
    @yield('content')
    <footer>
        <table class="table">
            <tbody>
                <tr>
                    <td>
                        <small>
                            GENERATED DATE: @php
                                date_default_timezone_set('Asia/Manila');
                                echo date('m/d/Y h:m:s');
                            @endphp
                        </small>
                    </td>
                    <td><small>GENERATE BY: {{ strtoupper(Auth::user()->name) }}</small></td>

                </tr>
            </tbody>
        </table>
    </footer>
</body>

</html>
