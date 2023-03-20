<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title-report')</title>
    <link rel="stylesheet" href="{{ public_path('css/page-layout.css') }}">
    <style>
        @page {
            margin: 5px;
            margin-top: 10px;
        }

        header {
            /*   position: fixed; */
            top: 10px;
            left: 0px;
            right: 0px;
            /* height: 20%; */
            text-align: left;
            justify-content: center;
        }

        footer {
            /*  position: fixed; */
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 10%;

            /** Extra personal styles **/
            background-color: #03a9f4;
            color: white;
            text-align: center;
            line-height: 35px;

        }

        .content {
            /*  position: relative; */
            top: 1.3in;
            bottom: 60px;
            max-height: 50%;
            /*  overflow: auto; */
            margin-right: 25px;
            margin-left: 25px;

        }

        .table-2 {

            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid rgb(0, 0, 0);
            margin-top: 10px;
        }

        .table-2 td,
        .table-2 th {
            padding-top: 5px;
            padding-bottom: 5px;
            border: 1px solid rgb(0, 0, 0);
            font-size: 11px;

        }

        .table-2 th {
            padding-top: 5px;
            padding-bottom: 5px;
            text-align: center;
        }

        .table-content {
            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
            width: 100%;
            /*  border: 1px solid #ddd;
            border: 1px solid #ddd; */
        }

        .table-content td,
        .table-content th {
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 10px;
            /*  border: 1px solid rgb(126, 126, 126); */
            font-size: 14px;

        }

        .table-student-content {
            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
            width: 100%;
            /*  border: 1px solid #ddd;
            border: 1px solid #ddd; */
        }

        .table-student-content td,
        .table-student-content th {
            padding-top: 5px;
            padding-bottom: 5px;
            /* border: 1px solid #ddd; */
            font-size: 10px;

        }

        .form-code {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            padding-left: 10px;
            margin-top: -10px
        }

        .text-center {
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }

        .content last-child {
            page-break-after: avoid;
        }

        .text-fill-in {
            /* text-decoration: underline; */
            border-bottom: 1px solid rgb(0, 0, 0);
            text-align: center;
        }
    </style>
</head>

<body>
    @yield('content')


</body>

</html>