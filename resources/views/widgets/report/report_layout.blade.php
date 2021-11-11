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
            font-family: "Times New Roman", Times, serif;
        }

        header {
            position: fixed;
            top: 10px;
            left: 0px;
            right: 0px;
            text-align: left;
        }

        b {
            font-family: Helvetica, sans-serif;
        }

        h4>b {
            font-family: "Times New Roman", Times, serif;
        }

        footer {
            position: absolute;
            bottom: 15px;
            left: 0px;
            right: 0px;
            text-align: center
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

        .form-code {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            padding-left: 10px;
            margin-top: -10px
        }

    </style>
</head>

<body>

    <header>
        <span class="form-code">BMA FORM @yield('form-code')<span>
        <img src="{{ public_path() . '/assets/image/report-header.png' }}" alt="page-header">
    </header>
    @yield('content')
    <footer>
        <label><i>This is a system generated report
                @php
                    date_default_timezone_set('Asia/Manila');
                    echo date('m/d/Y h:m:s');
                @endphp @ @yield('department')</i></label>
    </footer>

</body>

</html>
