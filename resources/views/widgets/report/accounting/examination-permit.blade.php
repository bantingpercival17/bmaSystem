<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Examination Permit</title>
    <style>
        @page {
            margin: 20px;
        }

        .page-content {}

        .permit-content {
            grid-template-columns: auto auto auto;
            border-style: solid;
            border-width: medium;
            width: 29.8%;
            padding: 10px;
            margin-bottom: 5px;
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
            /* border: 1px solid #ddd; */
            font-size: 10px;

        }

        .row-height {
            height: 7px;
            ;
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
    @foreach ($sections as $section)
        @if (count($section->student_sections) > 0)
            @foreach ($section->student_sections as $student)
                <div class="permit-content">
                    <small class="text-small">BMA-FORM ACC-021</small> <br>
                    <div class="header-permit">
                        <label for="" class="permit-header"><b>BALIWAG MARITIME ACADEMY, INC.</b></label> <br>
                        <p class="text-small"></p>
                        <p class="text-small">
                            NO @php
                                echo 0 . '' . request()->input('course') . '-';
                                $count += 1;
                                if ($count < 10) {
                                    echo '00' . $count;
                                } elseif ($count < 100) {
                                    echo '0' . $count;
                                } else {
                                    echo $count;
                                }
                            @endphp
                        </p>
                        <p class="text-small">
                            {{ strtoupper(Auth::user()->staff->current_academic()->semester) . ' / SY ' . Auth::user()->staff->current_academic()->school_year }}
                        </p>
                        <table class="table-permit-header">
                            <thead>
                                <tr>
                                    <td>NAME:</td>
                                    <th style="text-align:center" class="text-fill-in">
                                        <small>{{ strtoupper($student->student->last_name . ', ' . $student->student->first_name) }}</small>
                                    </th>
                                </tr>
                                <tr>
                                    <td style="width:55px;">STDNT NO:</td>
                                    <th class="text-fill-in">
                                        <small>{{ $student->student->account->student_number }}</small>
                                    </th>
                                </tr>
                                <tr>
                                    <td>COURSE:</td>
                                    <th class="text-fill-in">
                                        <small>{{ strtoupper($student->student->current_enrollment->course->course_name) }}</small>
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
            @endforeach
        @endif
    @endforeach
</body>

</html>
