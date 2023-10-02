<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Examination Permit</title>
    <style>
        @page {
            margin: 10px;
            position: relative;
        }

        .page-content {}

        .permit-content {
            /*   border-style: solid;
            border-width: medium;
            width: 29.8%;
            padding: 10px;
            margin-bottom: 5px;
             display: inline-block; */
            text-align: center;
        }

        .header-permit,
        .footer-permit {
            text-align: center
        }

        .text-small {
            font-size: 10px;
            padding: 0px;
            margin: 0px;
        }

        .text-medium {
            font-size: 12px;
        }

        .text-underline {
            text-decoration: underline;
            width: 100%
        }

        .page-table {
            width: 100%;
        }

        .page-table td {
            padding: 5px;
            border-style: solid;
            border-width: medium;
        }

        .table-permit-header {
            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        .table-permit-header td,
        .table-permit-header th {
            padding-top: 0px;
            padding-bottom: 0px;
            font-size: 10px;
            border-style: none;
            border-width: none;

        }

        .row-height {
            height: 7px;
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

        .text-fill-in {
            /* text-decoration: underline; */
            border-bottom: 1px solid rgb(0, 0, 0);
            text-align: center;
        }

        .text-header-content {
            text-align: center;
        }
    </style>
</head>

<body>
    @php
        $count = 0;
    @endphp
    <table class="page-table">
        <tbody>
            @forelse ($section->student_sections as $student)
                @php
                    $modValue = $count % 2;
                    if ($modValue === 0) {
                        echo '<tr>';
                    }
                    if ($modValue === 3) {
                        echo '</tr>';
                    }
                    $ctrlNo = 0 . '' . '-';
                    $count += 1;
                    $ctrlNo = $count < 10 ? $ctrlNo . '00' . $count : ($count < 100 ? $ctrlNo . '0' . $count : $ctrlNo . $count);
                @endphp
                <td style="width:100%;">
                    <div class="permit-content">
                        <img src="{{ $student->student->profile_picture() }}" alt="" width="40%">
                        {{--  <img src="data:image/png;base64, {!! base64_encode(
                            QrCode::style('round', 0.5)->eye('square')->size(140)->generate(
                                    $student->student->account->student_number . '.' . mb_strtolower(str_replace(' ', '', $student->student->last_name)),
                                ),
<<<<<<< HEAD
                        ) !!} "> <br>
                        {{ $student->student->account->student_number . '.' . mb_strtolower(str_replace(' ', '', $student->student->last_name.'-'.$student->student->first_name)) }}
=======
                        ) !!} "> --}} <br>
                        @php
                            $name = strtoupper($student->student->last_name . ', ' . $student->student->first_name . ' ' . $student->student->middle_initial);
                            $name = mb_strtolower($student->student->extention_name) != 'n/a' ? strtoupper($student->student->last_name . ' ' . $student->student->extention_name . ', ' . $student->student->first_name . ' ' . $student->student->middle_initial) : $name;
                        @endphp
                        {{ $name }}
                        {{-- {{ $student->student->account->student_number . '.' . mb_strtolower(str_replace(' ', '', $student->student->last_name)) }} --}}
>>>>>>> 7d39c10837b01f42506176c354756752f2f55d5a
                    </div>
                </td>
            @empty
            @endforelse
        </tbody>
    </table>


</body>

</html>
