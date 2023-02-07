@extends('widgets.report.grade.report_layout_1')
@section('title-report', 'FORM RG-03 - STUDENT REGISTRATION : ' . strtoupper($_student->last_name . ', ' .
    $_student->first_name . ' ' . $_student->middle_name))
@section('form-code', 'RG - 03')
@section('content')
    <main class="content">
        <div class="form-rg-information">
            <h3 class="text-center">STUDENT'S REGISTRATION FORM</h3>
            <h6 class="text-center">A.Y.
                {{ strtoupper($_enrollment_assessment->academic->school_year . ' | ' . $_enrollment_assessment->academic->semester) }}
            </h6>
            <div class="student-information">
                <h5 for="" class="text-header">A. STUDENT'S INFORMATION</h5>
                <table class="form-rg-table">
                    <tbody>
                        <tr>
                            <td colspan="5"></td>
                            <td width="60px"><small>DATE:</small></td>
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
                            <td class="text-fill-in"> <b>{{ strtoupper($_student->middle_initial) }}</b></td>
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
                            <td><small>MIDDLE INITIAL</small></td>
                            <td><small>MIDDLE NAME</small></td>
                            <td colspan="2"></td>
                    </tbody>
                </table>
                <table class="form-rg-table">
                    <tbody>
                        <tr>
                            <td width="5%"><small>COURSE:</small></td>
                            <td width="40%" class="text-fill-in">
                                <b> {{ $_enrollment_assessment->course->course_name }}</b>
                            </td>
                            <td width="3%"><small>YEAR:</small></td>
                            <td class="text-fill-in">
                                <b>{{ strtoupper(Auth::user()->staff->convert_year_level($_enrollment_assessment->year_level)) }}</b>
                            </td>
                            <td width="5%"><small>SEMESTER:</small></td>
                            <td class="text-fill-in">
                                <b> {{ strtoupper($_enrollment_assessment->academic->semester) }}</b>
                            </td>

                            <td width="3%"><small>AY:</small></td>
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
                                <b>{{ strtoupper($_student->municipality) }}</b>
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
                <table class="form-rg-table">
                    <tbody>
                        <tr>
                            <td width="10%"><small> DATE OF BIRTH:</small> </td>
                            <td class="text-fill-in">
                                <b>{{ strtoupper(date('F j, Y', strtotime($_student->birthday))) }}</b>
                            </td>
                            <td width="3%"><small> AGE:</small></td>
                            <td width="15%" class="text-fill-in">
                                <b>@php
                                    echo date_diff(date_create($_student->birthday), date_create(date('Y-m-d')))->format('%y');
                                @endphp
                                    years old
                                </b>
                            </td>
                            <td width="10%"><small>BIRTH PLACE:</small> </td>
                            <td class="text-fill-in">
                                <b>{{ strtoupper($_student->birth_place) }}</b>
                            </td>

                        </tr>
                    </tbody>
                </table>
                <table class="form-rg-table">
                    <tbody>
                        <tr>
                            <td width="10%"> <small>NATIONALITY:</small></td>
                            <td class="text-fill-in">
                                <b> {{ strtoupper($_student->nationality) }}</b>
                            </td>
                            <td width="6%"> <small>STATUS:</small></td>
                            <td class="text-fill-in">
                                <b>{{ $_student->status ? strtoupper($_student->status) : 'SINGLE' }}</b>
                            </td>
                            <td width="3%"> <small>SEX:</small> </td>
                            <td class="text-fill-in">
                                <b>{{ strtoupper($_student->sex) }}</b>
                            </td>
                            <td width="7%"> <small>RELIGION:</small></td>
                            <td class="text-fill-in">
                                <b>{{ $_student->religion ? strtoupper($_student->religion) : '-' }}</b>
                            </td>
                            <td width="3%"><small>BMI: </small></td>
                            <td class="text-fill-in">
                                @php
                                    $_cm = $_student->height;
                                    $_kg = $_student->weight;
                                    $_bmi = '';
                                    try {
                                        if ($_cm > 0 && $_kg > 0) {
                                            $_cm *= 0.01;
                                            $_kg *= 0.453592;
                                            $_height = $_cm * $_cm;
                                            $_bmi = number_format($_kg / $_height, 2);
                                        }
                                    } catch (\Throwable $th) {
                                        $_bmi = '';
                                    }
                                    
                                @endphp
                                <b>{{ $_bmi }}</b>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="form-rg-table">
                    <tbody>
                        <tr>
                            <td width="21%"><small>PARENT / GUARDIAN'S ADDRESS:</small></td>
                            <td class="text-fill-in">
                                <b>{{ $_student->parent_details ? strtoupper($_student->parent_details->guardian_address) : '-' }}</b>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="form-rg-table">
                    <tbody>
                        <tr>
                            <td width="10%"><small>CONTACT NO.:</small></td>
                            <td class="text-fill-in">
                                <b> {{ $_student->contact_number }}</b>
                            </td>
                            <td width="12%"><small>EMAIL ADDRESS: </small></td>
                            <td class="text-fill-in">
                                <b>{{ $_student->account->personal_email }}</b>
                            </td>

                        </tr>
                    </tbody>
                </table>
                <br>
                <div class="educational-background">
                    <h6><b>EDUCATIONAL BACKGROUND</b></h6>
                    <table class="form-rg-table">
                        @if ($_enrollment_assessment->course_id != 3)
                            <tbody>
                                @foreach ($_student->educational_background as $_data)
                                    <tr>
                                        <td width="15%"><small>{{ strtoupper($_data->school_level) }}:</small>
                                        </td>
                                        <td class="text-fill" width="68%">
                                            <b>{{ strtoupper($_data->school_name) }}</b>
                                        </td>
                                        <td width="3%"><small>AY:</small></td>
                                        @php
                                            $orgDate = $_data->graduated_year;
                                            $newDate = date('F Y', strtotime($orgDate));
                                        @endphp
                                        <td class="text-fill-in"><b>{{ $newDate }}</b></td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="2"><small>COLLEGE (IF ANY):</small>

                                    </td>
                                    <td><small>AY:</td>
                                    <td></td>
                                </tr>

                            </tbody>
                        @else
                            <tbody>
                                @foreach ($_student->educational_background as $_data)
                                    <tr>
                                        @if (strtoupper($_data->school_level) != 'SENIOR HIGH SCHOOL')
                                            <td colspan="2"><small>{{ strtoupper($_data->school_level) }}:</small>
                                                <b>{{ strtoupper($_data->school_name) }}</b>
                                            </td>
                                            <td><small>DATE GRADUATED:</small> </td>
                                            @php
                                                $orgDate = $_data->graduated_year;
                                                $newDate = date('F d,Y', strtotime($orgDate));
                                            @endphp
                                            <td class="text-fill-in"><b>{{ $newDate }}</b></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                </div>
            </div>
            <div class="parent-information">
                <br>
                <h5 for="" class="text-header">B. PARENT / GUARDIAN INFOMATION</h5>
                @php
                    $_parent = $_student->parent_details;
                    $_educational_attainment = ['Elementary Graduate', 'High School Graduate', 'College', 'Vocational', "Master's / Doctorate Degree", 'Did not attend school', 'Other: ________'];
                    $_employment_status = ['Full Time', 'Part Time', 'Self-employed (i.e. Family Business)', 'Unemployed due to community quarantine', 'Not Working'];
                    $_arrangement = ['WFH', 'Office', 'Field Work'];
                    $_income = ['Below 10,000', '10,000-20,000', '20,000-40,000', '40,000-60,000', '60,000 Above'];
                    $_dswd = ['Yes', 'No'];
                    $_homeownership = ['Owned', 'Mortgaged', 'Rented'];
                    $_cars = ['0', '1', '2', '3', 'Others'];
                    $_device = ['Cable TV', 'Non-Cable TV', 'Basic Cellphone', 'Smartphone', 'Tablet', 'Radio', 'Desktop Computer', 'Laptop', 'None', 'Others ______'];
                    $_connect = ['Yes', 'No'];
                    $_provider = ['own mobile data', 'own broadband (DSL, Wireless Fiber, Satellite)', 'computer shop', 'other places outside the home with internet connection (library, barangay,municipal hall neighbor, relatives)', 'none'];
                    $_learning_modality = ['online learning', 'Blended', 'Face-to-Face'];
                    $_inputs = ['lack of available gadgets / equipment', 'insufficient load/data allowance', 'existing health condition/s', 'difficulty in independent learning', 'conflict with other activities (i.e. house chores)', 'none or lack of available space for studying', 'distractions (i.e. social media, noise from community/ neighbor)', 'none'];
                    
                @endphp
                <table class="form-rg-table">
                    <tbody>
                        <tr>
                            <th><b>FATHER</b></th>
                            <th><b>MOTHER</b></th>
                            <th><b>GUARIAN</b></th>
                        </tr>
                        <tr>
                            <td> <small>B1. (Last name, First name, Middle name)</small></td>
                            <td> <small>B6. (Last name, First name, Middle name)</small></td>
                            <td> <small>B11. (Last name, First name, Middle name)</small></td>
                        </tr>
                        <tr>
                            <td class="text-fill-in">
                                <b>
                                    {{ $_parent ? strtoupper($_parent->father_last_name . ', ' . $_parent->father_first_name . ' ' . $_parent->father_middle_name) : '-' }}
                                </b>
                            </td>
                            <td class="text-fill-in">
                                <b>
                                    {{ $_parent ? strtoupper($_parent->mother_last_name . ', ' . $_parent->mother_first_name . ' ' . $_parent->mother_middle_name) : '-' }}
                                </b>
                            </td>
                            <td class="text-fill-in">
                                <b>
                                    {{ $_parent ? strtoupper($_parent->guardian_last_name . ', ' . $_parent->guardian_first_name . ' ' . $_parent->guardian_middle_name) : '-' }}
                                </b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><small>B2. HIGHEST EDUCATIONAL ATTAINMENT:</small></p>
                                @foreach ($_educational_attainment as $_key => $_education)
                                    <p class="checkbox-container">
                                        <label><input class="checkbox-input" type="checkbox"
                                                {{ $_parent ? ($_parent->father_educational_attainment == $_education ? 'checked' : '') : '' }} />{{ $_education }}</label>
                                    </p>
                                @endforeach
                            </td>
                            <td>
                                <p><small>B7. HIGHEST EDUCATIONAL ATTAINMENT:</small></p>
                                @foreach ($_educational_attainment as $_key => $_education)
                                    <p class="checkbox-container">
                                        <label><input class="checkbox-input" type="checkbox"
                                                {{ $_parent ? ($_parent->mother_educational_attainment == $_education ? 'checked' : '') : '' }} />{{ $_education }}</label>
                                    </p>
                                @endforeach
                            </td>
                            <td>
                                <p><small>B12. HIGHEST EDUCATIONAL ATTAINMENT:</small></p>
                                @foreach ($_educational_attainment as $_key => $_education)
                                    <p class="checkbox-container">
                                        <label><input class="checkbox-input" type="checkbox"
                                                {{ $_parent ? ($_parent->guardian_educational_attainment == $_education ? 'checked' : '') : '' }} />{{ $_education }}</label>
                                    </p>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <small>B3. EMPLOYMENT STATUS</small>
                                @foreach ($_employment_status as $_key => $_status)
                                    <p class="checkbox-container">
                                        <label>
                                            <input class="checkbox-input" type="checkbox"
                                                {{ $_parent ? ($_parent->father_employment_status == $_status ? 'checked' : '') : '' }} />
                                            {{ $_status }}
                                        </label>
                                    </p>
                                @endforeach
                            </td>
                            <td>
                                <small>B8. EMPLOYMENT STATUS</small>
                                @foreach ($_employment_status as $_key => $_status)
                                    <p class="checkbox-container">
                                        <label>
                                            <input class="checkbox-input" type="checkbox"
                                                {{ $_parent ? ($_parent->mother_employment_status == $_status ? 'checked' : '') : '' }} />
                                            {{ $_status }}
                                        </label>
                                    </p>
                                @endforeach
                            </td>
                            <td>
                                <small>B13. EMPLOYMENT STATUS</small>
                                @foreach ($_employment_status as $_key => $_status)
                                    <p class="checkbox-container">
                                        <label>
                                            <input class="checkbox-input" type="checkbox"
                                                {{ $_parent ? ($_parent->guardian_employment_status == $_status ? 'checked' : '') : '' }} />
                                            {{ $_status }}
                                        </label>
                                    </p>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <small>B4. WORKING ARRANGEMENT</small>


                                @foreach ($_arrangement as $_key => $_data)
                                    <p class="checkbox-container">
                                        <label>
                                            <input class="checkbox-input" type="checkbox"
                                                {{ $_parent ? ($_parent->father_working_arrangement == $_data ? 'checked' : '') : '' }} />
                                            {{ $_data }}
                                        </label>
                                    </p>
                                @endforeach
                            </td>
                            <td>
                                <small>B9. WORKING ARRANGEMENT</small>
                                @foreach ($_arrangement as $_key => $_data)
                                    <p class="checkbox-container">
                                        <label>
                                            <input class="checkbox-input" type="checkbox"
                                                {{ $_parent ? ($_parent->mother_working_arrangement == $_data ? 'checked' : '') : '' }} />
                                            {{ $_data }}
                                        </label>
                                    </p>
                                @endforeach
                            </td>
                            <td>
                                <small>B14. WORKING ARRANGEMENT</small>
                                @foreach ($_arrangement as $_key => $_data)
                                    <p class="checkbox-container">
                                        <label>
                                            <input class="checkbox-input" type="checkbox"
                                                {{ $_parent ? ($_parent->guardian_working_arrangement == $_data ? 'checked' : '') : '' }} />
                                            {{ $_data }}
                                        </label>
                                    </p>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>B5. CONTACT NUMBER/s</td>
                            <td>B10. CONTACT NUMBER/s</td>
                            <td>B15. CONTACT NUMBER/s</td>
                        </tr>
                        <tr>
                            <td class="text-fill-in">
                                <b>{{ $_parent ? $_parent->father_contact_number : '-' }}</b>
                            </td>
                            <td class="text-fill-in">
                                <b>{{ $_parent ? $_parent->mother_contact_number : '-' }}</b>
                            </td>
                            <td class="text-fill-in">
                                <b>{{ $_parent ? $_parent->guardian_contact_number : '-' }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="">
                                <small>B16. HOUSEHOLD CAPITAL INCOME:</small><br>
                                @foreach ($_income as $_key => $_data)
                                    <p class="checkbox-container">
                                        <label>
                                            <input class="checkbox-input" type="checkbox"
                                                {{ $_parent ? ($_parent->household_income == $_data ? 'checked' : '') : '' }} />
                                            {{ $_data }}
                                        </label>
                                    </p>
                                @endforeach
                            </td>
                            <td colspan="" colspan="1" valign="top">
                                <small>B17. IS YOUR FAMILY A BENEFICIARY OF DSWD LISTHAN / 4P's :</small><br>
                                @foreach ($_dswd as $_key => $_data)
                                    <p class="checkbox-container">
                                        <label>
                                            <input class="checkbox-input" type="checkbox"
                                                {{ $_parent ? ($_parent->dswd_listahan == $_data ? 'checked' : '') : '' }} />
                                            {{ $_data }}
                                        </label>
                                    </p>
                                @endforeach
                            </td>
                            <td colspan="1" colspan="1" valign="top">
                                <small>B18. HOMEOWERSHIP:</small><br>
                                @foreach ($_homeownership as $_key => $_data)
                                    <p class="checkbox-container">
                                        <label>
                                            <input class="checkbox-input" type="checkbox"
                                                {{ $_parent ? ($_parent->homeownership == $_data ? 'checked' : '') : '' }} />
                                            {{ $_data }}
                                        </label>
                                    </p>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" colspan="1" valign="top">
                                <small>B19. CAR ONWNERSHIP :</small><br>
                                @foreach ($_cars as $_key => $_data)
                                    <label class="checkbox-container">
                                        <input class="checkbox-input" type="checkbox"
                                            {{ $_parent ? ($_parent->car_ownership == $_data ? 'checked' : '') : '' }} />
                                        {{ $_data }}
                                    </label>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="page-break"></div>
            <div class="survey-information">
                <br>
                <h5 for="" class="text-header">C. ACCESS TO DISTANCE LEARNING</h5>
                <table class="form-rg-table">
                    <tr>
                        <td colspan="1">
                            <small>C1. WHAT DEVICES ARE AVAILABLE AT HOME THAT THE STUDENT CAN USE FOR LEARNING?
                            </small><br>

                            @php
                                $_device_1 = $_parent ? unserialize($_parent->available_devices) : [];
                            @endphp
                            @foreach ($_device as $_data)
                                <p class="checkbox-container">
                                    <label>
                                        <input class="checkbox-input" type="checkbox"
                                            {{ in_array($_data, $_device_1) ? 'checked' : '' }} />
                                        {{ $_data }}
                                    </label>
                                </p>
                            @endforeach

                        </td>
                        <td colspan="1" valign="top">
                            <small>C2. DO YOU HAVE A WAY TO CONNECT TO THE INTERNET? </small> <br>
                            @foreach ($_connect as $_data)
                                <div>
                                    <p class="checkbox-container">
                                        <label>
                                            <input class="checkbox-input" type="checkbox"
                                                {{ $_parent ? ($_parent->available_connection == $_data ? 'checked' : '') : '' }} />
                                            {{ $_data }}
                                        </label>
                                    </p>
                                </div>
                            @endforeach
                        </td>
                        <td colspan="1" valign="top">
                            <small>C3. HOW DO YOU CONNECT TO THE INTERNET? </small><br>
                            @php
                                $_array = $_parent ? unserialize($_parent->available_provider) : [];
                            @endphp
                            @foreach ($_provider as $_data)
                                <p class="checkbox-container">
                                    <label>
                                        <input class="checkbox-input" type="checkbox"
                                            {{ in_array($_data, $_array) ? 'checked' : '' }} />
                                        {{ $_data }}
                                    </label>
                                </p>
                            @endforeach
                        </td>
                    </tr>

                    <tr>
                        <td colspan="1" valign="top">
                            <small>C4. WHAT LEARNING MODALITY DO YOU PREFER? </small><br>
                            @php
                                $_array = $_parent ? unserialize($_parent->learning_modality) : [];
                            @endphp
                            @foreach ($_learning_modality as $_data)
                                <p class="checkbox-container">
                                    <label>
                                        <input class="checkbox-input" type="checkbox"
                                            {{ in_array($_data, $_array) ? 'checked' : '' }} />
                                        {{ $_data }}
                                    </label>
                                </p>
                            @endforeach
                        </td>
                        <td colspan="2" valign="top">
                            <small>C5. WHAT ARE THE CHALLENGES THAT MAY AFFECT YOUR LEARNING PROCESS THROUGH DISTANCE
                                EDUCATIONAL? </small><br>
                            @php
                                $_array = $_parent ? unserialize($_parent->distance_learning_effect) : [];
                            @endphp
                            @foreach ($_inputs as $_data)
                                <p class="checkbox-container">
                                    <label>
                                        <input class="checkbox-input" type="checkbox"
                                            {{ in_array($_data, $_array) ? 'checked' : '' }} />
                                        {{ $_data }}
                                    </label>
                                </p>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </div>
            <br><br>
            <p class="note">
                I hereby certify that the above information given are true and correct to the best of my knowledge and I
                allow the Baliwag Maritime Academy Inc, to use the information provided herein for the purpose of the
                Learner Information System and personal porfile. The information herein shall be treated as confidential in
                compliance with the Data Privacy Act of 2012.
            </p>

            <p class="note">
                <label for=""><b>DATA PRIVACY NOTICE</b></label>
                <br> <br>
                BMA is committed to comply with all government requirement as RA No. 10173, otherwise known as the Data
                Privacy Act. BMA will by way of contracts, requires to submit personal information of customers.
                <br><br>
                The above personal information will be used to facilitate your registration and admission in the Academy. We
                ask for certain sensitive personal information that will
                be processed in the school's information system to be used only to serve its educational activities as well
                as to comply with legal obligation which includes periodic submission
                of requirements to government agency/ies with jurisdiction over the school. Only authorized personnel have
                access to these data which will
                be stored for a limited time duration that is relevant to the purpose for which these were processed and for
                as long as required be applicable law. Disposal
                of stored data after the retention period shall be done using secure methods.
                {{-- I hereby certify that the above information given are true and correct to the best of my knowledge and I
                allow the Baliwag Maritime Academy Inc, to use the information provided herein for the purpose of the
                Learner Information System and personal porfile. The information herein shall be treated as confidential in
                compliance with the Data Privacy Act of 2012. --}}
            </p>
            <div class="signature">
                <br>
                <table class="form-rg-table">
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
        <div class="form-rg-assessment">
            <h3 class="text-center">ENROLLMENT REGISTRATION</h3>
            <h6 class="text-center">A.Y.
                {{ strtoupper($_enrollment_assessment->academic->school_year . ' | ' . $_enrollment_assessment->academic->semester) }}
            </h6>
            <table class="table">
                <tbody>
                    <tr>
                        <td> <small>DATE:</small>
                            <b>{{ strtoupper(date('F j, Y', strtotime($_enrollment_assessment->created_at))) }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="{{ $_enrollment_assessment->bridging_program == 'with' ? 2 : 1 }}">
                            <small>STUDENT NAME: </small>
                            <b>{{ strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name) }}</b>
                        </td>
                        <td><small>STUDENT NO:</small> <b>{{ $_student->account->student_number }}</b> </td>
                    </tr>
                    <tr>
                        <td><small>{{ $_enrollment_assessment->course_id != 3 ? 'COURSE:' : 'STRAND: ' }} </small>
                            <b>
                                {{ $_enrollment_assessment->course->course_name }}
                            </b>
                        </td>
                        @if ($_enrollment_assessment->bridging_program == 'with')
                            <td>
                                <small>LEVEL :</small>
                                <b>
                                    {{ strtoupper(Auth::user()->staff->convert_year_level($_enrollment_assessment->year_level)) }}
                                </b>
                            </td>
                            <td>
                                <small>BRIDGING PROGRAM : </small>
                                <b>
                                    {{ $_enrollment_assessment->bridging_program == 'with' ? 'YES' : 'NONE' }}
                                </b>
                            </td>
                        @else
                            <td>

                                <small>LEVEL :</small>
                                <b>
                                    {{ strtoupper(Auth::user()->staff->convert_year_level($_enrollment_assessment->year_level)) }}
                                </b>
                            </td>
                        @endif

                    </tr>

                </tbody>
            </table>
            <div class="subject-detials">
                <p class="title-header"><b>ENROLLMENT DETAILS</b></p>
                @php
                    $_units = 0;
                @endphp
                <table class="subject-list-table">
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
                        @if (count($_enrollment_assessment->course_subjects($_enrollment_assessment)))
                            @foreach ($_enrollment_assessment->course_subjects($_enrollment_assessment) as $_data)
                                @if ($_enrollment_assessment->bridging_program == 'with' || $_data->subject->subject_code != 'BRDGE')
                                    <tr>
                                        <td>{{ $_data->subject->subject_code }}</td>
                                        <td>{{ $_data->subject->subject_name }}</td>
                                        @if ($_enrollment_assessment->course_id != 3)
                                            <td style="text-align: center">{{ $_data->subject->lecture_hours }}</td>
                                            <td style="text-align: center">{{ $_data->subject->laboratory_hours }}</td>
                                        @endif
                                        <td style="text-align: center">{{ $_data->subject->units }}</td>
                                        @php
                                            $_units += $_data->subject->units;
                                        @endphp

                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="{{ $_enrollment_assessment->course_id != 3 ? 5 : 3 }}">No Subjects Encoded
                                </td>
                            </tr>
                        @endif

                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="{{ $_enrollment_assessment->course_id != 3 ? 4 : 2 }}">TOTAL UNITS</th>
                            <th class="total-unit">{{ $_units }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <br><br>
            <div class="assessment-fees">
                @php
                    $_assessment = $_enrollment_assessment->payment_assessments;
                    $_course_semestral_fee = $_enrollment_assessment->payment_assessments->course_semestral_fee;
                    $_payment_details = $_assessment;
                    $_total_payment = 0;
                    $_upon_enrollment = 0;
                    $_monthly_payment = 0;
                    $_total_fees = 0;
                @endphp
                <p class="title-header"><b>ASSESSMENT SUMMARY</b></p>
                <table class="subject-list-table ">
                    <thead>
                        <tr>
                            <th colspan="2">ASSESSMENT</th>
                        </tr>
                    </thead>
                    <tbody>
                        Assessment Details
                        <tr>
                            <td>PAYMENT MODE:</td>
                            <td class="text-center">
                                {{ $_payment_details->payment_mode == 0 ? 'FULLPAYMENT' : 'INSTALLMENT' }}

                            </td>

                        </tr>
                        @if (count($_course_semestral_fee->semestral_fees()) > 0)
                            @foreach ($_course_semestral_fee->semestral_fees() as $item)
                                <tr>
                                    <td>
                                        {{ ucwords(str_replace(['_', 'tags'], [' ', 'Fee'], $item->particular_tag)) }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $_particular_amount = $_enrollment_assessment->course_id == 3 ? $item->fees : $_course_semestral_fee->particular_tags($item->particular_tag);
                                            
                                            $_total_payment += $_particular_amount;
                                        @endphp
                                        <b> {{ number_format($_particular_amount, 2) }}</b>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($_enrollment_assessment->course_id == 3)
                                @foreach ($_course_semestral_fee->additional_fees($_course_semestral_fee->id) as $item)
                                    <tr>
                                        <td> <span class="mt-2 badge bg-success">
                                                {{ ucwords(str_replace(['_', 'tags'], [' ', 'Fee'], $item->particular_name)) }}</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $_total_payment += $item->particular_amount;
                                            @endphp
                                            <b> {{ number_format($item->particular_amount, 2) }}</b>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endif
                        @php
                            
                        @endphp
                        <tr>
                            <td class="text-center"><b>TOTAL PAYMENT</b> </td>
                            <td class="text-center">
                                <b>{{ number_format($_total_payment, '2') }}</b>
                            </td>
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
                            <td class="text-center">
                                {{ number_format($_payment_details->course_semestral_fee->upon_enrollment($_payment_details), '2') }}
                            </td>
                        </tr>
                        <tr>
                            <td>4 MONTHLY INSTALLMENT</td>
                            <td class="text-center">
                                {{ number_format($_payment_details->course_semestral_fee->monthly_fees($_payment_details), '2') }}
                            </td>
                        </tr>
                        <tr>
                            <td><b>TOTAL FEES</b></td>
                            <td class="text-center">
                                <b>{{ $_payment_details
                                    ? ($_payment_details->course_semestral_fee_id
                                        ? number_format($_payment_details->course_semestral_fee->total_payments($_payment_details), 2)
                                        : number_format($_payment_details->total_payment, 2))
                                    : '-' }}</b>
                            </td>
                        </tr>
                        {{-- @foreach ($_monthly_fee as $_due)
                  <tr>
                      <td>{{$_due}}</td>
                    <td class="text-center">{{$_monthly_payment >0 ? number_format($_monthly_payment,'2'):'-'}}</td>
                    </tr>
                    @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
