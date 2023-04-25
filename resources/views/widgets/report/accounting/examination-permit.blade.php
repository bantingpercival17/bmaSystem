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

        /* .permit-content {
            border-style: solid;
            border-width: medium;
            width: 29.8%;
            padding: 10px;
            margin-bottom: 5px;
             display: inline-block;
        } */

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
    </style>
</head>

<body>
    @php
        $count = 0;
    @endphp
    <table class="page-table">
        <tbody>
            @foreach ($sections as $section)
                @if (count($section->student_sections) > 0)
                    @foreach ($section->student_sections as $student)
                        @php
                            $modValue = $count % 3;
                            if ($modValue == 0) {
                                echo '<tr>';
                            }
                            if ($modValue == 0) {
                                echo '</tr>';
                            }
                            $count += 1;
                            $ctrlNo = 0 . '' . request()->input('course') . '-';
                            $count;
                            if ($count < 10) {
                                $ctrlNo = $ctrlNo . '00' . $count;
                            } elseif ($count < 100) {
                                $ctrlNo = $ctrlNo . '0' . $count;
                            } else {
                                $ctrlNo = $ctrlNo . $count;
                            }
                        @endphp
                        <td style="width:100%">
                            <div class="permit-content">
                                <small class="text-small">BMA-FORM ACC-021</small> <br>
                                <div class="header-permit">
                                    <label for="" class="permit-header"><b>BALIWAG MARITIME ACADEMY,
                                            INC.</b></label> <br>
                                    <p class="text-small"></p>
                                    <p class="text-small">
                                        NO {{ $ctrlNo }}
                                    </p>
                                    <p class="text-small">
                                        {{ strtoupper(Auth::user()->staff->current_academic()->semester) . ' / SY ' . Auth::user()->staff->current_academic()->school_year }}
                                    </p>
                                    <table class="table-permit-header">
                                        <thead>
                                            <tr>
                                                <td>NAME:</td>
                                                <th style="text-align:center" class="text-fill-in">
                                                    {{ strtoupper($student->student->last_name . ', ' . $student->student->first_name) }}
                                                </th>
                                            </tr>
                                            <tr>
                                                <td style="width:55px;">STDNT NO:</td>
                                                <th class="text-fill-in">
                                                    {{ $student->student->account->student_number }}
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>COURSE:</td>
                                                <th class="text-fill-in">
                                                    {{ strtoupper($student->student->current_enrollment->course->course_name) }}
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="content-permit">
                                    <h4 style="text-align:center; padding:0px; margin: 0px; margin-top:10px;">
                                        {{ strtoupper(request()->input('term')) }}
                                        EXAM
                                    </h4>
                                    <table class="table-2" style="text-align:center; padding:0px; margin: 0px;">
                                        <thead>
                                            <tr>
                                                <th>SUBJECT</th>
                                                <th>INSTRUCTOR'S <br> SIGNATURE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i <= 10; $i++)
                                                <tr>
                                                    <th class="row-height"></th>
                                                    <th></th>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                                <div class="footer-permit">
                                    <span class="text-small">Valid only when duly signed.</span> <br> <br>

                                    <label class="text-medium text-underline"><b>IRENE CAMACHO</b></label>
                                    <br>
                                    <label class="text-medium">Authorized Signature</label>
                                </div>
                            </div>
                        </td>
                    @endforeach
                @endif
            @endforeach

        </tbody>
    </table>
</body>

</html>
