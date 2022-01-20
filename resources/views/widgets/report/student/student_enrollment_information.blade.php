@extends('widgets.report.grade.report_layout_1')
@section('title-report', 'FORM RG-03 - STUDENT REGISTRATION : ' . strtoupper($_student->last_name . ', ' .
    $_student->first_name . ' ' . $_student->middle_name))
@section('form-code', 'RG - 03')
@section('content')
    <main class="content">
        <div class="form-rg">
            <h3 class="text-center">STUDENT'S REGISTRATION FORM</h3>
            <h6 class="text-center">A.Y.
                {{ strtoupper($_enrollment_assessment->academic->school_year . ' | ' . $_enrollment_assessment->academic->semester) }}
            </h6>
            <div class="student-information">
                <h5 for="" class="text-header">A. STUDENT'S INFORMATION</h5>

                <table class="form-rg-table">
                    <tbody>
                        <tr>
                            <td colspan="4"></td>
                            <td width="70px"><small>DATE:</small></td>
                            <td class="text-fill-in">
                                <b>{{ strtoupper(date('F j, Y', strtotime($_enrollment_assessment->created_at))) }}</b>
                            </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td width="80px">
                                <small>NAME (PRINT):</small>
                            </td>
                            <td class="text-fill-in"><b>{{ strtoupper($_student->last_name) }},</b> </td>
                            <td class="text-fill-in"> <b>{{ strtoupper($_student->first_name) }}</b></td>
                            <td class="text-fill-in"> <b>{{ strtoupper($_student->middle_name) }}</b></td>
                            <td>
                                <small>STUDENT #:</small>

                            </td>
                            <td class="text-fill-in"><b>{{ $_student->account->student_number }}</b></td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="1"></td>
                            <td> <small>SURNAME</small> </td>
                            <td><small>FIRST NAME</small></td>
                            <td><small>MIDDLE NAME</small></td>
                            <td colspan="2"></td>
                    </tbody>
                </table>
                <table class="form-rg-table">
                    <tbody>
                        <tr>
                            <td><small>COURSE:</small></td>
                            <td class="text-fill-in">
                                <b> {{ $_enrollment_assessment->course->course_name }}</b>
                            </td>
                            <td><small>YEAR:</small></td>
                            <td class="text-fill-in">
                                <b>{{ $_enrollment_assessment->course_id == 3 ? 'GRADE ' . $_enrollment_assessment->year_level : $_enrollment_assessment->year_level . ' CLASS' }}</b>
                            </td>
                            <td><small>SEMESTER:</small></td>
                            <td class="text-fill-in">
                                <b> {{ strtoupper($_enrollment_assessment->academic->semester) }}</b>
                            </td>

                            <td><small>AY:</small></td>
                            <td class="text-fill-in">
                                <b> {{ $_enrollment_assessment->academic->school_year }}</b>
                            </td>

                    </tbody>
                </table>
                <table class="form-rg-table">
                    <tbody>
                        <tr>
                            <td><small>COMPLETE ADDRESS:</small></td>
                            <td class="text-fill-in">
                                <b>{{ strtoupper($_student->street . ' ' . $_student->barangay) }}</b>
                            </td>
                            <td class="text-fill-in">
                                <b>{{ strtoupper($_student->city) }}</b>
                            </td>
                            <td class="text-fill-in">
                                <b>{{ strtoupper($_student->province) }}</b>
                            </td>
                            <td> <small>ZIP CODE: </small> </td>
                            <td class="text-fill-in"> <b>{{ $_student->zip_code }}</b></td>
                        </tr>
                        <tr class="text-center">
                            <td></td>
                            <td><small>(Street / Barangay)</small></td>
                            <td><small>(Town/ City/ Municipality)</small></td>
                            <td><small>(Pronvince)</small></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="parent-information">
                <h5 for="" class="text-header">B. PARENT / GUARDIAN INFOMATION</h5>
            </div>
        </div>
        <div class="page-break"></div>

        <div class="registrar-copy">
            <h3 class="text-center">STUDENT'S REGISTRATION FORM</h3>
            <small>| REGISTRAR'S COPY</small>
            <div class="student-information">
                <h4><b>STUDENT'S INFORMATION</b></h4>
                <table class="table">
                    <tbody>
                        <tr>
                            <td colspan="3"> </td>
                            <td colspan="1"> <small>DATE:</small>
                                <b>{{ strtoupper(date('F j, Y', strtotime($_enrollment_assessment->created_at))) }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <small class="text-left">NAME (PRINT):</small>
                                <span>
                                    <b>{{ strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name) }}</b>
                                </span>
                            </td>
                            <td colspan="1">
                                <small>STUDENT #:</small>
                                <b><b>{{ $_student->account->student_number }}</b></b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1"><small>COURSE:</small>
                                <b> {{ $_enrollment_assessment->course->course_name }}</b>
                            </td>
                            <td colspan="1">
                                <small>YEAR:</small>
                                <b>{{ $_enrollment_assessment->course_id == 3 ? 'GRADE ' . $_enrollment_assessment->year_level : $_enrollment_assessment->year_level . ' CLASS' }}</b>
                            </td>
                            <td colspan="1"><small>SEMESTER:</small>
                                <b> {{ strtoupper($_enrollment_assessment->academic->semester) }}</b>
                            </td>
                            <td colspan="1"><small>AY:</small>
                                <b> {{ $_enrollment_assessment->academic->school_year }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <small>COMPLETE ADDRESS:</small>
                                <b>
                                    {{ strtoupper($_student->street . ' ' . $_student->barangay . ' ' . $_student->city . ' ' . $_student->province) }}</b>
                            </td>
                            <td colspan="1">
                                <small>ZIP CODE: </small>
                                <b>{{ $_student->zip_code }}</b>
                            </td>
                        </tr>
                        <tr>

                            <td colspan="2"><small> DATE OF BIRTH:</small>
                                <b>{{ strtoupper(date('F j, Y', strtotime($_student->birthday))) }}</b>
                            </td>
                            <td colspan="2"><small> AGE:</small> <b>@php
                                echo date_diff(date_create($_student->birthday), date_create(date('Y-m-d')))->format('%y');
                            @endphp </b></td>

                        </tr>
                        <tr>
                            <td colspan="4"><small>BIRTH PLACE:</small>
                                <b>{{ strtoupper($_student->birth_place) }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1"> <small>NATIONALITY:</small><b> {{ strtoupper($_student->nationality) }}</b>
                            </td>
                            <td colspan="1"> <small>STATUS:</small>
                                <b>{{ $_student->status ? strtoupper($_student->status) : 'SINGLE' }}</b>
                            </td>
                            <td colspan="1"> <small>SEX:</small> <b>{{ strtoupper($_student->sex) }}</b> </td>
                            <td colspan="1"> <small>RELIGION:</small>
                                <b>{{ $_student->religion ? strtoupper($_student->religion) : '-' }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"><small>PARENT / GUARDIAN'S ADDRESS:</small>
                                <b>{{ strtoupper($_student->parent_address) }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><small>CONTACT NO.:</small><b> {{ $_student->account->contact_number }}</b>
                            </td>
                            <td colspan="2"><small>EMAIL ADDRESS: </small><b>{{ $_student->account->personal_email }}</b>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="educational-background">
                <h4><b>EDUCATIONAL BACKGROUND</b></h4>
                <table class="table">
                    <tbody>
                        @foreach ($_student->educational_background as $_data)
                            <tr>
                                <td colspan="2"><small>{{ strtoupper($_data->school_level) }}:</small>
                                    <b>{{ strtoupper($_data->school_name) }}</b>
                                </td>
                                <td><small>AY:</small> <b>{{ $_data->year }}</b></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="signature" style="right:100px">
                <br>
                <table class="table ">

                    <tbody class="text-center">
                        <tr>
                            <td colspan="6"></td>
                            <td colspan="2">
                                <u></u>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6"></td>
                            <td colspan="2">Midshipman's Signature</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="parent-information">
                <h4><b>PARENT / GUARDIAN INFORMATION</b></h4>
                @php
                    $_parent = $_student->additional_information;
                @endphp
                <table class="table" id="father-information">
                    <tr>
                        <td colspan="2">
                            <small>FATHER'S NAME: </small>
                            <b>{{ $_parent ? strtoupper($_parent->father_last_name . ', ' . $_parent->father_first_name . ' ' . $_parent->father_middle_name) : '-' }}</b>
                        </td>
                        <td colspan="1">
                            <small>CONTACT NUMBER: </small>
                            <b>{{ $_parent ? $_parent->father_contact_number : '-' }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">
                            <small>HIGHEST EDUCATIONAL ATTAINMENT: </small><br>
                            <b>{{ $_parent ? strtoupper($_parent->father_educational_attainment) : '-' }}</b>
                        </td>
                        <td colspan="1">
                            <small>EMPLOYMENT STATUS: </small><br>
                            <b>{{ $_parent ? strtoupper($_parent->father_employment_status) : '-' }}</b>
                        </td>
                        <td colspan="1">
                            <small>WORKING ARRANGEMENT: </small><br>
                            <b>{{ $_parent ? strtoupper($_parent->father_working_arrangement) : '-' }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <small>MOTHER'S MAIDEN NAME: </small>
                            <b>{{ $_parent ? strtoupper($_parent->mother_last_name . ', ' . $_parent->mother_first_name . ' ' . $_parent->mother_middle_name) : '-' }}</b>
                        </td>
                        <td colspan="1">
                            <small>CONTACT NUMBER: </small>
                            <b>{{ $_parent ? $_parent->mother_contact_number : '-' }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">
                            <small>HIGHEST EDUCATIONAL ATTAINMENT: </small><br>
                            <b>{{ $_parent ? strtoupper($_parent->mother_educational_attainment) : '-' }}</b>
                        </td>
                        <td colspan="1">
                            <small>EMPLOYMENT STATUS: </small><br>
                            <b>{{ $_parent ? strtoupper($_parent->mother_employment_status) : '-' }}</b>
                        </td>
                        <td colspan="1">
                            <small>WORKING ARRANGEMENT: </small><br>
                            <b>{{ $_parent ? strtoupper($_parent->mother_working_arrangement) : '-' }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <small>GUARDIAN'S MAIDEN NAME: </small>

                            <b>{{ $_parent ? strtoupper($_parent->guardian_last_name . ', ' . $_parent->guardian_first_name . ' ' . $_parent->guardian_middle_name) : '-' }}</b>
                        </td>
                        <td colspan="1">
                            <small>CONTACT NUMBER: </small>
                            <b>{{ $_parent ? $_parent->guardian_contact_number : '-' }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">
                            <small>HIGHEST EDUCATIONAL ATTAINMENT: </small><br>
                            <b>{{ $_parent ? strtoupper($_parent->guardian_educational_attainment) : '-' }}</b>
                        </td>
                        <td colspan="1">
                            <small>EMPLOYMENT STATUS: </small><br>
                            <b>{{ $_parent ? strtoupper($_parent->guardian_employment_status) : '-' }}</b>
                        </td>
                        <td colspan="1">
                            <small>WORKING ARRANGEMENT: </small><br>
                            <b>{{ $_parent ? strtoupper($_parent->guardian_working_arrangement) : '-' }}</b>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="1">
                            <small>HOUSEHOLD CAPITAL INCOME:</small><br>
                            <b>{{ $_parent ? $_parent->household_income : '-' }}</b>
                        </td>
                        <td colspan="2">
                            <small>IS YOUR FAMILY A BENEFICIARY OF DSWD LISTHAN / 4P's :</small><br>
                            <b>{{ $_parent ? $_parent->dswd_listahan : '-' }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">
                            <small>HOMEOWERSHIP:</small><br>
                            <b>{{ $_parent ? $_parent->homeownership : '-' }}</b>
                        </td>
                        <td colspan="2">
                            <small>CAR ONWNERSHIP :</small><br>
                            <b>{{ $_parent ? $_parent->car_ownership : '-' }}</b>
                        </td>
                    </tr>
                </table>
            </div><br><br><br>
            <div class="survey-information">
                <h4><b>ACCESS TO DISTANCE LEARNING</b></h4>
                <table class="table" id="father-information">
                    <tr>
                        <td colspan="1">
                            <small>1. WHAT DEVICES ARE AVAILABLE AT HOME THAT THE STUDENT CAN USE FOR LEARNING? </small>
                            @php
                                $_device = $_parent ? unserialize($_parent->available_devices) : [];
                                foreach ($_device as $_item) {
                                    echo '<p>- <b>' . strtoupper($_item) . '</b></p> ';
                                }
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">
                            <small>2. DO YOU HAVE A WAY TO CONNECT TO THE INTERNET? </small> <br>
                            <b>
                                {{ $_parent ? strtoupper($_parent->available_connection) : '-' }}
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">
                            <small>3. HOW DO YOU CONNECT TO THE INTERNET? </small>
                            @php
                                $_device = $_parent ? unserialize($_parent->available_provider) : [];
                                foreach ($_device as $_item) {
                                    echo '<p>- <b>' . strtoupper($_item) . '</b></p>';
                                }
                            @endphp
                        </td>

                    </tr>
                    <tr>
                        <td colspan="1">
                            <small>4. WHAT LEARNING MODALITY DO YOU PREFER? </small>
                            @php
                                $_device = $_parent ? unserialize($_parent->learning_modality) : [];
                                foreach ($_device as $_item) {
                                    echo '<p>-<b> ' . strtoupper($_item) . '</b></p>';
                                }
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">
                            <small>5. WHAT ARE THE CHALLENGES THAT MAY AFFECT YOUR LEARNING PROCESS THROUGH DISTANCE
                                EDUCATIONAL? </small>
                            @php
                                $_device = $_parent ? unserialize($_parent->distance_learning_effect) : [];
                                foreach ($_device as $_item) {
                                    echo '<p>-<b> ' . strtoupper($_item) . '</b></p>';
                                }
                            @endphp
                        </td>
                    </tr>
                </table>
            </div>
            <br><br>
            <p class="note">
                I hereby certify that the above information given are true and correct to the best of my knowledge and I
                allow the Baliwag Maritime Academy Inc, to use the information provided herein for the purpose of the
                Learner Information System and personal porfile. The information herein shall be treated as confidential on
                compliance with the Data Privacy Act of 2012.
            </p>
            <div class="signature">
                <br>
                <table class="table">
                    <tbody>
                        <tr class="text-center">
                            <td colspan="1">
                                <b><u>{{ strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name) }}</u>
                                </b>
                            </td>
                            <td colspan="2">
                                <u><b>{{ strtoupper(date('F j, Y', strtotime($_enrollment_assessment->created_at))) }}</b></u>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="1">Signature Over Printend Name of Student/Midshipman</td>
                            <td colspan="2">Date</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="page-break"></div>
        {{-- <h3 class="text-center">ENROLLMENT REGISTRATION</h3>

        <table class="table">
            <tbody>
                <tr>
                    <td> <small>DATE:</small>
                        <b>{{ strtoupper(date('F j, Y', strtotime($_enrollment_assessment->created_at))) }}</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="{{$_enrollment_assessment->bridging_program == 'with' ? 2 : 1 }}">
                        <small>STUDENT NAME: </small>
                        <b>{{ strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name) }}</b>
                    </td>
                    <td><small>STUDENT NO:</small> <b>{{ $_student->user->client_code }}</b> </td>
                </tr>
                <tr>
                    <td><small>COURSE: </small>
                        <b>
                            {{$_enrollment_assessment->course->course_name }}
                        </b>
                    </td>
                    @if ($_enrollment_assessment->bridging_program == 'with')
                        <td>
                            <small>LEVEL :</small>
                            <b>
                                {{$_enrollment_assessment->course_id == 3 ? 'GRADE ' .$_enrollment_assessment->year_level :$_enrollment_assessment->year_level . ' CLASS' }}
                            </b>
                        </td>
                        <td>
                            <small>BRIDGING PROGRAM : </small>
                            <b>
                                {{$_enrollment_assessment->bridging_program == 'with' ? 'YES' : 'NONE' }}
                            </b>
                        </td>
                    @else
                        <td>

                            <small>LEVEL :</small>
                            <b>
                                {{$_enrollment_assessment->course_id == 3 ? 'GRADE ' .$_enrollment_assessment->year_level :$_enrollment_assessment->year_level . ' CLASS' }}
                            </b>
                        </td>
                    @endif

                </tr>

            </tbody>
        </table>
        <br>
        <p class="title-header"><b>| ENROLLMENT DETAILS</b></p>
        @php
            $_units = 0;
            $_subject = $_student->course_subject_level($_enrollment_assessment->curriculum_id,$_enrollment_assessment->course_id,$_enrollment_assessment->year_level,$_enrollment_assessment->academic->semester);
        @endphp
        <table class="table-2">
            <thead>
                <tr>
                    <th>SUBJECT CODE</th>
                    <th>DESCRIPTIVE TITLE</th>
                    @if ($_enrollment_assessment->course_id != 3)
                        <th>LEC. HOURS</th>
                        <th>LAB. HOURS</th>
                    @endif
                    <th>UNIT</th>
                </tr>

            </thead>
            <tbody>
                @foreach ($_subject as $item)
                    <tr>
                        <td>{{ $item->course_code }}</td>
                        <td>{{ $item->subject_description }}</td>
                        @if ($_enrollment_assessment->course_id != 3)
                            <td style="text-align: center">{{ $item->lecture_hours }}</td>
                            <td style="text-align: center">{{ $item->laboratory_hours }}</td>
                        @endif
                        <td style="text-align: center">{{ $item->units }}</td>
                        @php
                            $_units += $item->units;
                        @endphp

                    </tr>
                @endforeach
                @if ($_enrollment_assessment->course_id != 3)
                    @if ($_enrollment_assessment->bridging_program == 'with')
                        <tr>
                            <td>BRDGE</td>
                            <td>INTRODUCTORY SUBJECT (IMC, IMTME, IMS)</td>
                            <td>(4)</td>
                            <td>0</td>
                            <td>3</td>
                            @php
                                $_units += 3;
                            @endphp
                        </tr>

                    @endif
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="{{$_enrollment_assessment->course_id != 3 ? 4 : 2 }}">TOTAL UNITS</th>
                    <th class="total-unit">{{ $_units }}</th>
                </tr>
            </tfoot>
        </table>
        <br>
        <p class="title-header"><b>| ASSESSMENT SUMMARY</b></p>
        @php
            $_fees =$_enrollment_assessment->assessment_fee($_enrollment_assessment);
            $_assessment = $_student->_assessment;
            $_total_payment = 0;
            $_monthly_payment = 0;
            $_total_fees_without_book = 0;
            $_total_books_uniform = 0;
            $_total_fees = 0;
            $_upon_enrollment = 0;
            $_monthly_fee = ['1ST MONTHLY', '2ND MONTHLY', '3RD MONTHLY', '4TH MONTHLY'];
            // Solution 1
            // Senior High Tuition
            if ($_enrollment_assessment->course_id == 3) {
                // If Installment - 100% Books plus the total of Tuition Fee/5
                if ($_assessment->mode_payment == 1) {
                    // Ito ay Para Sa installment
                    foreach ($_fees as $key => $_fees_value) {
                        if ($_fees_value->particular->fee_name != 'BOOKS' && $_fees_value->particular->fee_name != 'UNIFORMS') {
                            $_total_fees_without_book += $_fees_value->fee_amount;
                        } else {
                            $_total_books_uniform += $_fees_value->fee_amount;
                        }
                    }
                    // Wala pang internts
                    $_monthly_payment = ($_total_fees_without_book + 710) / 5;
                    $_upon_enrollment = $_monthly_payment + $_total_books_uniform;
                } else {
                    // For FULLPAYMENT of Senior High
                    foreach ($_fees as $key => $_fees_value) {
                        $_total_fees_without_book += $_fees_value->fee_amount;
                    }
                    $_upon_enrollment = $_total_fees_without_book;
                }
            } else {
                // Add all the fee in College
                foreach ($_fees as $key => $_fees_value) {
                    if ($_fees_value->fee_id == 6) {
                        if ($_enrollment_assessment->bridging_program == 'with') {
                            $_total_fees += $_fees_value->fee_amount;
                        }
                    } else {
                        $_total_fees += $_fees_value->fee_amount;
                    }
                } // End of Added
                // Payment Mode
                if ($_assessment->mode_payment == 1) {
                    //$_interest = ($_total_fees - 800) * .035;
                    $_interest =$_enrollment_assessment->course_id == 2 ? $_total_fees * 0.035 : ($_total_fees - 800) * 0.035;
                    $_total_fees += $_interest;
                    $_upon_enrollment = $_total_fees / 5;
                    $_monthly_payment = $_upon_enrollment;
                } else {
                    $_upon_enrollment = $_total_fees;
                }
            }
        @endphp
        <table class="table-2 ">
            <thead>
                <tr>
                    <th colspan="2">ASSESSMENT</th>
                </tr>
            </thead>
            <tbody>
                Assessment Details
                <tr>
                    <td>PAYMENT MODE:</td>
                    <td class="text-center">{{ $_assessment->mode_payment == 0 ? 'FULLPAYMENT' : 'INSTALLMENT' }}</td>

                </tr>
                @foreach ($_fees as $_item)
                    @if ($_item->fee_id == 6)
                        @if ($_enrollment_assessment->bridging_program == 'with')
                            <tr>
                                <td>{{ $_item->particular->fee_name }}</td>
                                <td class="text-center">{{ number_format($_item->fee_amount, '2') }}</td>
                                @php
                                    $_total_payment += $_item->fee_amount;
                                @endphp
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td>{{ $_item->particular->fee_name }}</td>
                            <td class="text-center">{{ number_format($_item->fee_amount, '2') }}</td>
                            @php
                                $_total_payment += $_item->fee_amount;
                            @endphp
                        </tr>
                    @endif

                @endforeach
                <tr>
                    <td>LESS: ESC/SHS VOUCHER: </td>
                    <td>-</td>
                </tr>
                <tr>
                    <td class="text-center"><b>TOTAL PAYMENT</b> </td>
                    <td class="text-center"><b>{{ number_format($_total_payment, '2') }}</b></td>
                </tr>
                <tr>
                    <th colspan="2">SCHEDULE OF PAYMENT</th>
                </tr>
                <tr>
                    <td class="text-center">PAYMENT DUE DATE</td>
                    <td class="text-center">AMOUNT</td>
                </tr>
                <tr>
                    <td>UPON ENROLLMENT</td>
                    <td class="text-center">{{ number_format($_upon_enrollment, '2') }}</td>
                </tr>
                <tr>
                    <td>4 MONTHLY INSTALLMENT</td>
                    <td class="text-center">{{ $_monthly_payment > 0 ? number_format($_monthly_payment, '2') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td><b>TOTAL FEES</b></td>
                    <td class="text-center">
                        <b>{{ $_monthly_payment > 0 ? number_format($_total_fees, '2') : '-' }}</b>
                    </td>
                </tr>
                {{-- @foreach ($_monthly_fee as $_due)
          <tr>
              <td>{{$_due}}</td>
              <td class="text-center">{{$_monthly_payment >0 ? number_format($_monthly_payment,'2'):'-'}}</td>
          </tr>
          @endforeach --}}
        </tbody>
        </table> --}}
    </main>
@endsection
