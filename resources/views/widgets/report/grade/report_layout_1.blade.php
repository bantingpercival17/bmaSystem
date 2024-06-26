<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title-report')</title>
    <style>
        @page {
            margin: 100px 25px;
        }

        * {
            padding: 0;
            margin: 0;
            /* font-family: "Times New Roman", Times, serif */
        }
        header {
            position: fixed;
            top: 10px;
            left: 0px;
            right: 0px;
            text-align: left;
            justify-content: center;
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

        .content {
            position: relative;
            top: 1.5in;
            margin-right: 25px;
            margin-left: 25px;


        }

        .content: last-child {
            page-break-after: avoid;
        }

        .align {
            text-align: right;
            width: 20px;
            padding-right: 10px;
        }

        .page-break {
            page-break-before: always;
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
            page-break-before: auto;
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
            padding-left: 10px;
            /*  border: 1px solid rgb(126, 126, 126); */
            font-size: 14px;

        }

        .table-2 {
            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid rgb(0, 0, 0);
            margin-top: 10px;
            page-break-inside: always;
        }



        .table-2 td,
        .table-2 th {
            padding-top: 5px;
            padding-bottom: 5px;
            border: 1px solid rgb(0, 0, 0);
            font-size: 9px;

        }

        .table-2 th {
            padding-top: 5px;
            padding-bottom: 5px;
            text-align: center;
        }

        .breakNow {
            page-break-inside: avoid;
            page-break-after: always;
        }

        .th-1 {
            width: 150px;
        }

        .th-2 {
            width: 10%;
        }

        .th-1 {
            width: 100px;
        }

        .note {
            font-size: 12px;
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

        .form-code {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            padding-left: 10px;
            margin-top: -10px
        }

        .form-rg-table {
            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
            width: 100%;
        }

        .form-rg-table td,
        .form-rg-table th {
            padding-top: 5px;
            padding-bottom: 5px;
            font-size: 10px;

        }

        .text-header {
            font-weight: bold;
            font-size: 14px;
        }

        .text-fill-in {
            /* text-decoration: underline; */
            border-bottom: 1px solid rgb(0, 0, 0);
            text-align: center;
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

        .subject-list-table {
            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
            border: 1px solid #ddd;
            margin-top: 20px
        }

        .subject-list-table td,
        .subject-list-table th {
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 10px;
            border: 1px solid #ddd;
            font-size: 12px;

        }

        .subject-list-table th {
            padding-top: 5px;
            padding-bottom: 5px;

            text-align: center;
        }

        .image-signature {
            width: 30%;
        }
    </style>
</head>

<body>

    <header>
        <span class="form-code">BMA FORM @yield('form-code')<span>
                <center>
                    <img src="{{ public_path() . '/assets/image/report-header.png' }}" alt="page-header">
                </center>

    </header>
    @yield('content')
    <footer>
        <table class="table text-center">
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
                    <td><small>GENERATED BY: {{ strtoupper(Auth::user()->name) }}</small></td>

                </tr>
            </tbody>
        </table>
    </footer>

</body>

</html>
