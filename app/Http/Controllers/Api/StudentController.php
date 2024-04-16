<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\DeploymentAssesment;
use App\Models\DocumentRequirements;
use App\Models\Documents;
use App\Models\EducationalDetails;
use App\Models\EnrollmentApplication;
use App\Models\EnrollmentAssessment;
use App\Models\ParentDetails;
use App\Models\PaymentAssessment;
use App\Models\PaymentTrasanctionOnline;
use App\Models\ShipBoardInformation;
use App\Models\ShipboardJournal;
use App\Models\ShippingAgencies;
use App\Models\StudentAccount;
use App\Models\StudentDetails;
use App\Models\GradePublish;
use App\Models\ShipboardAssessmentDetails;
use App\Models\ShipboardExamination;
use App\Models\Staff;
use App\Models\ThirdDatabase\MobileApplicationDetails;
use App\Models\ThirdDatabase\MobileApplicationDonwloads;
use App\Report\Students\StudentReport;
use Exception;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function student_details()
    {
        try {
            $account = auth()->user();
            $student = StudentAccount::where('id', $account->id)
                ->with('student')
                ->first();
            $student = StudentDetails::with('parent_details')->find($student->student_id);
            $profile_picture = $student->profile_picture();
            return response(['account' => $student, 'profile_picture' => $profile_picture], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    public function student_information()
    {
        try {
            $account = auth()->user();
            $student = StudentDetails::with('educational_background')->with('parent_details')->with('enrollment_assessment')->with('account')->find($account->student_id);
            //$profile_picture = $student->profile_picture();
            return response(['student' => $student], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    /* Enrollment History of Student */
    function student_enrollment_history()
    {
        try {
            $data = auth()->user()->student->enrollment_history;
            return response(['data' => $data], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function student_enrollment_coe($data)
    {
        try {
            $assessment = EnrollmentAssessment::find($data);
            $pdfReport = new StudentReport();
            return $pdfReport->enrollment_certificate($assessment);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    public function student_update_information(Request $request)
    {
        $this->student_input_validation($request);
        try {
            /* SET THE INPUT FIELDS */
            /* STUDENT DETAILS */
            $studentDetails = [
                'last_name' => trim(ucwords(mb_strtolower($request->last_name))),
                'first_name' => trim(ucwords(mb_strtolower($request->first_name))),
                'middle_name' => trim(ucwords(mb_strtolower($request->middle_name))),
                'middle_initial' => trim(ucwords(mb_strtolower($request->middle_initial))),
                'extention_name' => $request->extension_name,
                'birthday' => $request->birth_date,
                'birth_place' => trim(ucwords(mb_strtolower($request->birth_place))),
                'height' => $request->height,
                'weight' => $request->weight,
                'civil_status' => trim(ucwords(mb_strtolower($request->civil_status))),
                'religion' => trim(ucwords(mb_strtolower($request->religion))),
                'nationality' => trim(ucwords(mb_strtolower($request->nationality))),
                'street' => ucwords(mb_strtolower(trim($request->street))),
                'barangay' => ucwords(mb_strtolower(trim($request->barangay))),
                'municipality' => ucwords(mb_strtolower(trim($request->municipality))),
                'province' => ucwords(mb_strtolower(trim($request->province))),
                'zip_code' => trim(ucwords(mb_strtolower($request->zip_code))),
                'contact_number' => $request->contact_number,
                'sex' => $request->gender,
                'is_removed' => false,
            ];
            /* Educational Attainment */
            $_elementary = ['student_id' => auth()->user()->student_id, 'school_level' => 'Elementary School', 'school_name' => trim(ucwords(mb_strtolower($request->elementary_school_name))), 'school_address' => trim(ucwords(mb_strtolower($request->elementary_school_address))), 'graduated_year' => trim(ucwords(mb_strtolower($request->elementary_school_year))), 'school_category' => 'n/a', 'is_removed' => false];
            $_high_school = ['student_id'  => auth()->user()->student_id, 'school_level' => 'Junior High School', 'school_name' => trim(ucwords(mb_strtolower($request->junior_high_school_name))), 'school_address' => trim(ucwords(mb_strtolower($request->junior_high_school_address))), 'graduated_year' => trim(ucwords(mb_strtolower($request->junior_high_school_year))), 'school_category' => 'n/a', 'is_removed' => false];
            $_senior_high_school = ['student_id'  => auth()->user()->student_id, 'school_level' => 'Senior High School', 'school_name' => trim(ucwords(mb_strtolower($request->senior_high_school_name))), 'school_address' => trim(ucwords(mb_strtolower($request->senior_high_school_address))), 'graduated_year' => trim(ucwords(mb_strtolower($request->senior_high_school_year))), 'school_category' => 'n/a', 'is_removed' => false];
            /* Parent Information */
            $_parent_info = [
                'father_last_name' => trim(ucwords(mb_strtolower($request->father_last_name))),
                'father_first_name' => trim(ucwords(mb_strtolower($request->father_first_name))),
                'father_middle_name' => trim(ucwords(mb_strtolower($request->father_middle_name))),
                'father_educational_attainment' => $request->father_educational_attainment,
                'father_employment_status' => $request->father_employment_status,
                'father_working_arrangement' => $request->father_working_arrangement,
                'father_contact_number' => $request->father_contact_number,

                'mother_last_name' => trim(ucwords(mb_strtolower($request->mother_last_name))),
                'mother_first_name' => trim(ucwords(mb_strtolower($request->mother_first_name))),
                'mother_middle_name' => trim(ucwords(mb_strtolower($request->mother_middle_name))),
                'mother_educational_attainment' => $request->mother_educational_attainment,
                'mother_employment_status' => $request->mother_employment_status,
                'mother_working_arrangement' => $request->mother_working_arrangement,
                'mother_contact_number' => $request->mother_contact_number,

                'guardian_last_name' => trim(ucwords(mb_strtolower($request->guardian_last_name))),
                'guardian_first_name' => trim(ucwords(mb_strtolower($request->guardian_first_name))),
                'guardian_middle_name' => trim(ucwords(mb_strtolower($request->guardian_middle_name))),
                'guardian_educational_attainment' => $request->guardian_educational_attainment,
                'guardian_employment_status' => $request->guardian_employment_status,
                'guardian_working_arrangement' => $request->guardian_working_arrangement,
                'guardian_contact_number' => $request->guardian_contact_number,
                'guardian_address' => $request->guardian_address,

                'household_income' =>  $request->household_income,
                'dswd_listahan' => $request->dswd_beneficiary,
                'homeownership' => $request->home_ownership,
                'car_ownership' => $request->car_ownership,

                'available_devices' => serialize($request->available_device),
                'available_connection' => $request->available_connection,
                'available_provider' => serialize($request->available_provider),
                'learning_modality' => serialize($request->learning_modality),
                'distance_learning_effect' => serialize($request->distance_learning_effect),
                'is_removed' => 0
            ];
            $_education = [$_elementary, $_high_school];

            if (auth()->user()->student->enrollment_assessment->course_id != 3) {
                $_education =  [$_elementary, $_high_school, $_senior_high_school];
            }
            $studentValidation = StudentDetails::find(Auth::user()->student_id); // Verify if the Student existing
            if ($studentValidation) {
                $studentValidation->update($studentDetails); # Update the Student Details
                # GET THE LIST OF EDUCATION DETAILS
                $education = $studentValidation->educational_background;
                if (count($education) > 0) {
                    EducationalDetails::where('student_id', $studentValidation->id)
                        ->where('is_removed', false)->update(['is_removed' => true]); # Set into hide the all Educational Details
                    foreach ($_education as $key => $value) {
                        $value['student_id'] = $studentValidation->id; # Set the index value of student id
                        $_data = EducationalDetails::where('student_id', $studentValidation->id)
                            ->where('school_level', $value['school_level'])
                            ->where('school_name', $value['school_name'])
                            ->where('school_address', $value['school_address'])
                            ->where('graduated_year', $value['graduated_year'])
                            ->where('is_removed', true)
                            ->first();
                        if ($_data) {
                            # Verify the Data is exsiting if yes it will update into show data
                            $_data->update(['is_removed' => false]);
                        } else {
                            # if not Create a new Data
                            EducationalDetails::create($value);
                        }
                    }
                } else {
                    // Store a new Educational Background
                    foreach ($_education as $key => $value) {
                        $value['student_id'] = $studentValidation->id;
                        EducationalDetails::create($value);
                    }
                }
                # Parent Details
                $_parent = $studentValidation->parent_details;
                if ($_parent) {
                    $_parent->update(['is_removed' => true]);
                    $_parent_info += ['student_id' => $studentValidation->id];
                    ParentDetails::create($_parent_info);
                } else {
                    $_parent_info += ['student_id' => $studentValidation->id];
                    ParentDetails::create($_parent_info);
                }
            } else {
                // Create Student Information
                $_student_store = StudentDetails::create($studentDetails);
                // Educational Background
                foreach ($_education as $key => $value) {
                    $value['student_id'] = $_student_store->id;
                    EducationalDetails::create($value);
                }
                // Additional Details
                $_parent_info += ['student_id' => $_student_store->id];
                ParentDetails::create($_parent_info);
            }
            #return StudentDetails::with(['educational_background', 'parent_details'])->find(Auth::user()->student_id);
            return response(['message' => 'Successfully Update Information.'], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    /* Student Enrollment Procudure */
    public function enrollment_overview(Request $_request)
    {
        try {
            // Enrollment Procudure
            $application = auth()->user()->student->student_enrollment_application;
            $academic = AcademicYear::where('is_active', true)->first();
            $medical_result = auth()->user()->student->prev_enrollment_assessment->medical_result;
            // Tuition Fee Assessment
            $enrollment_assessment = auth()->user()->student->current_enrollment;
            $tags = [];
            $total_fees = [];
            $tuition_assessment = [];
            $units = [];
            $payment_transaction = [];
            $online_transaction = [];
            if ($enrollment_assessment) {
                $tuition_fees = $enrollment_assessment->course_level_tuition_fee();
                if ($tuition_fees) {
                    $tags = $tuition_fees->semestral_fees();
                    $total_tuition  = $tuition_fees->total_tuition_fees($enrollment_assessment);
                    $total_tuition_with_interest  = $tuition_fees->total_tuition_fees_with_interest($enrollment_assessment);
                    $upon_enrollment = 0;
                    $upon_enrollment = $tuition_fees->upon_enrollment_v2($enrollment_assessment);
                    $monthly = 0;
                    $monthly = $tuition_fees->monthly_fees_v2($enrollment_assessment);
                    $total_fees = compact('total_tuition', 'total_tuition_with_interest', 'upon_enrollment', 'monthly');
                }
                $tuition_assessment = $enrollment_assessment->enrollment_payment_assessment;
                $payment_details = array();
                $units = $enrollment_assessment->course->units($enrollment_assessment)->units;
                // Payment transaction

                if ($tuition_assessment) {
                    $online_transaction = $tuition_assessment->online_enrollment_payment;
                    $payment_transaction = $tuition_assessment->payment_assessment_paid;
                }
            }
            $enrollment = compact('medical_result', 'application', 'enrollment_assessment');
            $tuition = compact('tuition_assessment', 'tags', 'units', 'total_fees', 'online_transaction', 'payment_transaction');
            $enrollment = compact('academic', 'enrollment', 'tuition');
            return response(['data' => $enrollment], 200);
            //return response(['data' => $data], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function student_input_validation(Request $request)
    {
        $_fields = [
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'middle_initial' => 'required',
            'extension_name' => 'required | min:2',
            'birth_date' => 'required',
            'birth_place' => 'required',
            'gender' => 'required',
            'weight' => 'required',
            'height' => 'required',
            'civil_status' => 'required',
            'religion' => 'required',
            'nationality' => 'required',
            'street' => 'required',
            'barangay' => 'required',
            'municipality' => 'required',
            'province' => 'required',
            'zip_code' => 'required',
            'personal_email' => 'required',
            'contact_number' => 'required | numeric| min:12',
            // FATHER INFORMATION
            'father_last_name' => 'required | min:2 | max:50',
            'father_first_name' => 'required | min:2 | max:50',
            'father_middle_name' => 'required | min:2 | max:50',
            'father_educational_attainment' => 'required | min:2 | max:100',
            'father_employment_status' => 'required | min:2 | max:50',
            'father_working_arrangement' => 'required | min:2 | max:50',
            'father_contact_number' => 'required | min:2 | max:12',
            // MOTHER INFORMATION
            'mother_last_name' => 'required | min:2 | max:50',
            'mother_first_name' => 'required | min:2 | max:50',
            'mother_middle_name' => 'required | min:2 | max:50',
            'mother_educational_attainment' => 'required | min:2 | max:100',
            'mother_employment_status' => 'required | min:2 | max:50',
            'mother_working_arrangement' => 'required | min:2 | max:50',
            'mother_contact_number' => 'required | min:2 | max:12',
            // GUARDIAN  INFORMATION
            'guardian_last_name' => 'required | min:2 | max:50',
            'guardian_first_name' => 'required | min:2 | max:50',
            'guardian_middle_name' => 'required | min:2 | max:50',
            'guardian_educational_attainment' => 'required | min:2 | max:50',
            'guardian_employment_status' => 'required | min:2 | max:50',
            'guardian_working_arrangement' => 'required | min:2 | max:50',
            'guardian_contact_number' => 'required| min:2 | max:12',
            'guardian_address' => 'required',
            // OTHER DETIALS
            /*   'household_income' => 'required',
        'dswd_listahan' => 'required',
        'homeownership' => 'required',
        'car_ownership' => 'required', */
            'elementary_school_name' => 'required|max:100',
            'elementary_school_address' => 'required|max:255',
            'elementary_school_year' => 'required|max:100',
            'junior_high_school_name' => 'required|max:100',
            'junior_high_school_address' => 'required|max:255',
            'junior_high_school_year' => 'required|max:100',
            'household_income' => 'required',
            'dswd_beneficiary' => 'required',
            'home_ownership' => 'required',
            'car_ownership' => 'required',
            'available_device' => 'required',
            'available_connection' => 'required',
            'available_provider' => 'required',
            'learning_modality' => 'required',
            'distance_learning_effect' => 'required'
        ];
        if (auth()->user()->student->enrollment_assessment->course_id != 3) {
            $_fields += [
                'senior_high_school_name' => 'required|max:100',
                'senior_high_school_address' => 'required|max:255',
                'senior_high_school_year' => 'required|max:100',
            ];
        }
        $request->validate($_fields);
    }
    public function enrollment_application(Request $_request)
    {
        $_fields = [
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'middle_initial' => 'required',
            'extension_name' => 'required | min:2',
            'birth_date' => 'required',
            'birth_place' => 'required',
            'gender' => 'required',
            'weight' => 'required',
            'height' => 'required',
            'civil_status' => 'required',
            'religion' => 'required',
            'nationality' => 'required',
            'street' => 'required',
            'barangay' => 'required',
            'municipality' => 'required',
            'province' => 'required',
            'zip_code' => 'required',
            'personal_email' => 'required',
            'contact_number' => 'required | numeric| min:12',
            // FATHER INFORMATION
            'father_last_name' => 'required | min:2 | max:50',
            'father_first_name' => 'required | min:2 | max:50',
            'father_middle_name' => 'required | min:2 | max:50',
            'father_educational_attainment' => 'required | min:2 | max:100',
            'father_employment_status' => 'required | min:2 | max:50',
            'father_working_arrangement' => 'required | min:2 | max:50',
            'father_contact_number' => 'required | min:2 | max:12',
            // MOTHER INFORMATION
            'mother_last_name' => 'required | min:2 | max:50',
            'mother_first_name' => 'required | min:2 | max:50',
            'mother_middle_name' => 'required | min:2 | max:50',
            'mother_educational_attainment' => 'required | min:2 | max:100',
            'mother_employment_status' => 'required | min:2 | max:50',
            'mother_working_arrangement' => 'required | min:2 | max:50',
            'mother_contact_number' => 'required | min:2 | max:12',
            // GUARDIAN  INFORMATION
            'guardian_last_name' => 'required | min:2 | max:50',
            'guardian_first_name' => 'required | min:2 | max:50',
            'guardian_middle_name' => 'required | min:2 | max:50',
            'guardian_educational_attainment' => 'required | min:2 | max:50',
            'guardian_employment_status' => 'required | min:2 | max:50',
            'guardian_working_arrangement' => 'required | min:2 | max:50',
            'guardian_contact_number' => 'required| min:2 | max:12',
            'guardian_address' => 'required',
            // OTHER DETIALS
            /*   'household_income' => 'required',
            'dswd_listahan' => 'required',
            'homeownership' => 'required',
            'car_ownership' => 'required', */
            'elementary_school_name' => 'required|max:100',
            'elementary_school_address' => 'required|max:255',
            'elementary_school_year' => 'required|max:100',
            'junior_high_school_name' => 'required|max:100',
            'junior_high_school_address' => 'required|max:255',
            'junior_high_school_year' => 'required|max:100',
            'household_income' => 'required',
            'dswd_beneficiary' => 'required',
            'home_ownership' => 'required',
            'car_ownership' => 'required',
            'available_device' => 'required',
            'available_connection' => 'required',
            'available_provider' => 'required',
            'learning_modality' => 'required',
            'distance_learning_effect' => 'required'
        ];
        if (auth()->user()->student->enrollment_assessment->course_id != 3) {
            $_fields += [
                'senior_high_school_name' => 'required|max:100',
                'senior_high_school_address' => 'required|max:255',
                'senior_high_school_year' => 'required|max:100',
            ];
        }
        $_request->validate($_fields);
        try {
            /* SET THE INPUT FIELDS */
            /* STUDENT DETAILS */
            $_student_details = [
                'last_name' => trim(ucwords(mb_strtolower($_request->last_name))),
                'first_name' => trim(ucwords(mb_strtolower($_request->first_name))),
                'middle_name' => trim(ucwords(mb_strtolower($_request->middle_name))),
                'middle_initial' => trim(ucwords(mb_strtolower($_request->middle_initial))),
                'extention_name' => $_request->extension_name,
                'birthday' => $_request->birth_date,
                'birth_place' => trim(ucwords(mb_strtolower($_request->birth_place))),
                'height' => $_request->height,
                'weight' => $_request->weight,
                'civil_status' => trim(ucwords(mb_strtolower($_request->civil_status))),
                'religion' => trim(ucwords(mb_strtolower($_request->religion))),
                'nationality' => trim(ucwords(mb_strtolower($_request->nationality))),
                'street' => ucwords(mb_strtolower(trim($_request->street))),
                'barangay' => ucwords(mb_strtolower(trim($_request->barangay))),
                'municipality' => ucwords(mb_strtolower(trim($_request->municipality))),
                'province' => ucwords(mb_strtolower(trim($_request->province))),
                'zip_code' => trim(ucwords(mb_strtolower($_request->zip_code))),
                'contact_number' => $_request->contact_number,
                'sex' => $_request->gender,
                'is_removed' => false,
            ];
            /* Educational Attainment */
            $_elementary = ['student_id' => auth()->user()->student_id, 'school_level' => 'Elementary School', 'school_name' => trim(ucwords(mb_strtolower($_request->elementary_school_name))), 'school_address' => trim(ucwords(mb_strtolower($_request->elementary_school_address))), 'graduated_year' => trim(ucwords(mb_strtolower($_request->elementary_school_year))), 'school_category' => 'n/a', 'is_removed' => false];
            $_high_school = ['student_id'  => auth()->user()->student_id, 'school_level' => 'Junior High School', 'school_name' => trim(ucwords(mb_strtolower($_request->junior_high_school_name))), 'school_address' => trim(ucwords(mb_strtolower($_request->junior_high_school_address))), 'graduated_year' => trim(ucwords(mb_strtolower($_request->junior_high_school_year))), 'school_category' => 'n/a', 'is_removed' => false];
            $_senior_high_school = ['student_id'  => auth()->user()->student_id, 'school_level' => 'Senior High School', 'school_name' => trim(ucwords(mb_strtolower($_request->senior_high_school_name))), 'school_address' => trim(ucwords(mb_strtolower($_request->senior_high_school_address))), 'graduated_year' => trim(ucwords(mb_strtolower($_request->senior_high_school_year))), 'school_category' => 'n/a', 'is_removed' => false];
            /* Parent Information */
            $_parent_info = [
                'father_last_name' => trim(ucwords(mb_strtolower($_request->father_last_name))),
                'father_first_name' => trim(ucwords(mb_strtolower($_request->father_first_name))),
                'father_middle_name' => trim(ucwords(mb_strtolower($_request->father_middle_name))),
                'father_educational_attainment' => $_request->father_educational_attainment,
                'father_employment_status' => $_request->father_employment_status,
                'father_working_arrangement' => $_request->father_working_arrangement,
                'father_contact_number' => $_request->father_contact_number,

                'mother_last_name' => trim(ucwords(mb_strtolower($_request->mother_last_name))),
                'mother_first_name' => trim(ucwords(mb_strtolower($_request->mother_first_name))),
                'mother_middle_name' => trim(ucwords(mb_strtolower($_request->mother_middle_name))),
                'mother_educational_attainment' => $_request->mother_educational_attainment,
                'mother_employment_status' => $_request->mother_employment_status,
                'mother_working_arrangement' => $_request->mother_working_arrangement,
                'mother_contact_number' => $_request->mother_contact_number,

                'guardian_last_name' => trim(ucwords(mb_strtolower($_request->guardian_last_name))),
                'guardian_first_name' => trim(ucwords(mb_strtolower($_request->guardian_first_name))),
                'guardian_middle_name' => trim(ucwords(mb_strtolower($_request->guardian_middle_name))),
                'guardian_educational_attainment' => $_request->guardian_educational_attainment,
                'guardian_employment_status' => $_request->guardian_employment_status,
                'guardian_working_arrangement' => $_request->guardian_working_arrangement,
                'guardian_contact_number' => $_request->guardian_contact_number,
                'guardian_address' => $_request->guardian_address,

                'household_income' =>  $_request->household_income,
                'dswd_listahan' => $_request->dswd_beneficiary,
                'homeownership' => $_request->home_ownership,
                'car_ownership' => $_request->car_ownership,

                'available_devices' => serialize($_request->available_device),
                'available_connection' => $_request->available_connection,
                'available_provider' => serialize($_request->available_provider),
                'learning_modality' => serialize($_request->learning_modality),
                'distance_learning_effect' => serialize($_request->distance_learning_effect),
                'is_removed' => 0
            ];
            $_education = [$_elementary, $_high_school];

            if (auth()->user()->student->enrollment_assessment->course_id != 3) {
                $_education =  [$_elementary, $_high_school, $_senior_high_school];
            }
            $_student_validation = StudentDetails::find(Auth::user()->student_id); // Verify if the Student existing
            if ($_student_validation) {
                // Update the Student Details
                $_student_validation->update($_student_details);
                // Validate the Educational Background
                $_educational = $_student_validation->educational_background;
                if (count($_educational) > 0) {
                    // Update the Educational Background
                    EducationalDetails::where('student_id', $_student_validation->id)->where(
                        'is_removed',
                        false
                    )->update(['is_removed' => true]);
                    foreach ($_education as $key => $value) {
                        $value['student_id'] = $_student_validation->id;
                        $_data = EducationalDetails::where('student_id', $_student_validation->id)
                            ->where('school_level', $value['school_level'])
                            ->where('school_name', $value['school_name'])
                            ->where('school_address', $value['school_address'])
                            ->where('graduated_year', $value['graduated_year'])
                            ->where('is_removed', true)
                            ->first();
                        if ($_data) {
                            $_data->update(['is_removed' => 0]);
                        } else {
                            EducationalDetails::create($value);
                        }
                    }
                } else {
                    // Store a new Educational Background
                    foreach ($_education as $key => $value) {
                        $value['student_id'] = $_student_validation->id;
                        EducationalDetails::create($value);
                    }
                }
                // Parent Details
                $_parent = $_student_validation->parent_details;
                if ($_parent) {
                    $_parent->update(['is_removed' => true]);
                    $_parent_info += ['student_id' => $_student_validation->id];
                    ParentDetails::create($_parent_info);
                } else {
                    $_parent_info += ['student_id' => $_student_validation->id];
                    ParentDetails::create($_parent_info);
                }
            } else {
                // Create Student Information
                $_student_store = StudentDetails::create($_student_details);
                // Educational Background
                foreach ($_education as $key => $value) {
                    $value['student_id'] = $_student_store->id;
                    EducationalDetails::create($value);
                }
                // Additional Details
                $_parent_info += ['student_id' => $_student_store->id];
                ParentDetails::create($_parent_info);
            }
            return $this->student_enrollment_application($_request);
            //return response(['message' => 'Successfully Submitted.'], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    public function student_enrollment_application(Request $_request)
    {
        try {
            $_up_comming_academic = AcademicYear::where('is_active', 1)->first();
            $_enrollment_application = EnrollmentApplication::where(['student_id' => auth()->user()->student_id, 'academic_id' => $_up_comming_academic->id])
                ->where('is_removed', false)
                ->first();
            if (!$_enrollment_application) {
                $_details = [
                    'student_id' => auth()->user()->student_id,
                    'academic_id' => $_up_comming_academic->id,
                    'course_id' => auth()->user()->student->enrollment_assessment->course_id,
                    'enrollment_place' => 'online',
                    'enrollment_category' => NULL,
                    'is_removed' => false,
                ];
                EnrollmentApplication::create($_details);
                return response(['message' => 'Successfully Send your Enrollment Application.'], 200);
            } else {
                return response([
                    'message' => ' Your Already Submit Enrollment Application!'
                ], 402);
            }
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    public function enrollment_payment_mode(Request $_request)
    {
        try {
            $student = auth()->user()->student->student_enrollment_application;
            $student->payment_mode = $_request->paymentMode;
            $student->save();
            return response(['message' => 'Successfully Submitted.'], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    /* Payment View */
    function student_payment_overview(Request $_request)
    {
        try {
            $enrollment_list = auth()->user()->student->enrollment_assessment_history;
            $currently_enrolled = auth()->user()->student->enrollment_assessment_details;
            $data = compact('enrollment_list', 'currently_enrolled');
            return response(['data' => $data], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function student_payment_transaction(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required',
            'amount_paid' => 'required',
            'reference_number' => 'required',
            'payment_mode' => 'required',
            'remarks' => 'required',
            'file' => 'required',
        ]);
        try {
            $paymentAssessment = PaymentAssessment::find($request->payment);
            $enrollment_assessment = EnrollmentAssessment::find($paymentAssessment->enrollment_id);
            $semester = '/' . $enrollment_assessment->academic->semester . '-' . $enrollment_assessment->academic->school_year;
            $_file_link = $this->saveFiles($request->file('file'), 'bma-students', 'accounting' . $semester);
            $payment_data = [
                'assessment_id' => $paymentAssessment->id,
                'amount_paid' => str_replace(',', '', $request->amount_paid),
                'reference_number' => $request->reference_number,
                'transaction_type' => $request->remarks,
                'reciept_attach_path' => $_file_link,
                'is_removed' => 0,
            ];
            if ($request->document) {
                PaymentTrasanctionOnline::find($request->document)->update(['is_removed' => true]);
            }
            PaymentTrasanctionOnline::create($payment_data);

            return response(['data' => 'done', 'message' => 'Successfully Submitted.'], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    /* Onboarding Performance Report */
    public function student_onboard_enrollment()
    {
        try {
            // Get the Document Requirments
            $document_requirements = Documents::where('is_removed', false)
                ->where('document_propose', 'PRE-DEPLOYMENT')
                ->with('student_upload_documents')
                ->orderByRaw('CHAR_LENGTH("document_name")')
                ->get();
            // Get the Shipping Agency
            $shipping_company = ShippingAgencies::select('id', 'agency_name')
                ->where('is_removed', false)
                ->orderBy('agency_name')
                ->get();
            // Get the Shipboard Information
            $shipboard_application = ShipBoardInformation::where('student_id', auth()->user()->student_id)
                ->where('is_removed', false)->with('document_requirements')->with('document_requirements_approved')->first();
            $document_status = ShipBoardInformation::where('student_id', auth()->user()->student_id)
                ->where('is_removed', false)->first();
            // VESSEL TYPE
            $vessel_type = ['CONTAINER VESSEL', 'GENERAL CARGO', 'TANKER', 'BULK CARIER', 'CRUISE LINE ', 'CAR CARIER', 'TRAINING SHIP'];

            // Enrollment Procudure
            $application = auth()->user()->student->student_enrollment_application;
            $academic = AcademicYear::where('is_active', true)->first();
            // Tuition Fee Assessment
            $enrollment_assessment = auth()->user()->student->current_enrollment;
            $tags = [];
            $total_fees = [];
            $tuition_assessment = [];
            $units = [];
            $payment_transaction = [];
            $online_transaction = [];
            if ($enrollment_assessment) {
                $tuition_fees = $enrollment_assessment->course_level_tuition_fee();
                if ($tuition_fees) {
                    $tags = $tuition_fees->semestral_fees();
                    $total_tuition  = $tuition_fees->total_tuition_fees($enrollment_assessment);
                    $total_tuition_with_interest  = $tuition_fees->total_tuition_fees_with_interest($enrollment_assessment);
                    $upon_enrollment = 0;
                    $upon_enrollment = $tuition_fees->upon_enrollment_v2($enrollment_assessment);
                    $monthly = 0;
                    $monthly = $tuition_fees->monthly_fees_v2($enrollment_assessment);
                    $total_fees = compact('total_tuition', 'total_tuition_with_interest', 'upon_enrollment', 'monthly');
                }
                $tuition_assessment = $enrollment_assessment->enrollment_payment_assessment;
                $payment_details = array();
                $units = $enrollment_assessment->course->units($enrollment_assessment)->units;
                // Payment transaction

                if ($tuition_assessment) {
                    $online_transaction = $tuition_assessment->online_enrollment_payment;
                    $payment_transaction = $tuition_assessment->payment_assessment_paid;
                }
            }
            $application_details = compact('shipping_company', 'document_requirements', 'vessel_type');
            $enrollment = compact('application', 'academic');
            $tuition = compact('tuition_assessment', 'tags', 'units', 'total_fees', 'online_transaction', 'payment_transaction');
            $enrollment = compact('application_details', 'shipboard_application', 'enrollment', 'tuition');
            return response(['data' => $enrollment], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    public function student_enrollment_application_sbt(Request $_request)
    {
        try {
            $_up_comming_academic = AcademicYear::where('is_active', 1)->first();
            $_enrollment_application = EnrollmentApplication::where(['student_id' => auth()->user()->student_id, 'academic_id' => $_up_comming_academic->id])
                ->where('is_removed', false)
                ->first();
            if (!$_enrollment_application) {
                $_details = [
                    'student_id' => auth()->user()->student_id,
                    'academic_id' => $_up_comming_academic->id,
                    'course_id' => auth()->user()->student->enrollment_assessment->course_id,
                    'enrollment_place' => 'online',
                    'enrollment_category' => 'SBT ENROLLMENT',
                    'is_removed' => false,
                ];
                EnrollmentApplication::create($_details);
                return response(['message' => 'Successfully Send your Enrollment Application.'], 200);
            } else {
                return response([
                    'message' => ' Your Already Submit Enrollment Application!'
                ], 402);
            }
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    public function student_enrollment_payment_store(Request $_request)
    {
        $_request->validate([
            'transaction_date' => 'required',
            'amount_paid' => 'required',
            'reference_number' => 'required',
            'file' => 'required',
        ]);
        try {
            $semester = '/' . auth()->user()->student->current_enrollment->academic->semester . '-' . auth()->user()->student->current_enrollment->academic->school_year;
            $_file_link = $this->saveFiles($_request->file('file'), 'bma-students', 'accounting' . $semester);
            $assessment = auth()->user()->student->current_enrollment->enrollment_payment_assessment;
            $_payment_data = [
                'assessment_id' => $assessment->id,
                'amount_paid' => str_replace(',', '', $_request->amount_paid),
                'reference_number' => $_request->reference_number,
                'transaction_type' => 'Upon Enrollment',
                'reciept_attach_path' => $_file_link,
                'is_removed' => 0,
            ];
            if ($_request->document) {
                PaymentTrasanctionOnline::find($_request->document)->update(['is_removed' => true]);
            }
            PaymentTrasanctionOnline::create($_payment_data);
            return response(['data' => 'done', 'message' => 'Successfully Submitted.'], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    public function student_onboarding()
    {
        try {
            $account = auth()->user();
            $onboard_assessment = ShipBoardInformation::where('student_id', $account->student_id)->get();
            //$journal = $_journal = ShipboardJournal::select('month', DB::raw('count(*) as total'))->where('student_id', $account->student_id)->where('is_removed', false)->groupBy('month')->get();
            // Get the Shipping Componies
            $shipboard_company = ShippingAgencies::select('id', 'agency_name')
                ->where('is_removed', false)
                ->get();
            // Get the Document Requierment for Shipboard Application
            $documents = Documents::where('is_removed', 1)
                ->where('document_propose', 'PRE-DEPLOYMENT')
                ->orderByRaw('CHAR_LENGTH("document_name")')
                ->get();

            return response(['shipboard_information' => $onboard_assessment, 'companies' => $shipboard_company, 'documents' => $documents], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }

    /* ACADEMIC */
    function semestral_grade(Request $request)
    {
        try {
            // Get First the Current and Paid Enrollment Assessment
            $query = EnrollmentAssessment::select('enrollment_assessments.*')
                ->with('academic')
                ->with('course')
                ->with('curriculum')
                ->where('student_id', Auth::user()->student_id)
                ->where('enrollment_assessments.is_removed', false)
                ->join('payment_assessments', 'payment_assessments.enrollment_id', 'enrollment_assessments.id')
                ->where('payment_assessments.is_removed', false)
                ->join('payment_transactions', 'payment_transactions.assessment_id', 'payment_assessments.id')
                ->where('payment_transactions.is_removed', false)->orderBy('enrollment_assessments.id', 'desc');

            if ($request->key) {
                $enrollment = EnrollmentAssessment::select('enrollment_assessments.*')
                    ->with('academic')
                    ->with('course')
                    ->with('curriculum')
                    ->find(base64_decode($request->key));

                if ($enrollment->student_id !== Auth::user()->student_id) {
                    return response(['status' => '404', 'message' => 'Invalid Account'], 200);
                }
            } else {
                // Get First the Current and Paid Enrollment Assessment
                $enrollment = $query->first();
            }
            $enrollmentHistory = Auth::user()->student->enrollment_history;
            $section =  $enrollment->student_section;
            $gradePublish = GradePublish::where('student_id', $enrollment->student_id)
                ->where('academic_id', $enrollment->academic_id)
                ->where('is_removed', false)->first();
            $percent = [[0, 69.46, 5.0], [69.47, 72.88, 3.0], [72.89, 76.27, 2.75], [76.28, 79.66, 2.5], [79.67, 83.05, 2.25], [83.06, 86.44, 2.0], [86.45, 89.83, 1.75], [89.84, 93.22, 1.5], [93.23, 96.61, 1.25], [96.62, 100, 1.0]];
            return response(['data' => $section, 'enrollment' => $enrollment, 'enrollmentHistory' => $enrollmentHistory, 'gradePublish' => $gradePublish, 'percent' => $percent], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function mobile_application_list(Request $request)
    {
        try {
            $application  = MobileApplicationDetails::where('is_removed', false)->with('latest_version')->get();
            return response(compact('application'), 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function mobile_application_download(Request $request)
    {
        try {
            $version = MobileApplicationDetails::with('latest_version')->find($request->data);
            $parsed_url = parse_url($version->latest_version->app_path);
            // Get the path part of the URL
            $path = $parsed_url['path'];
            $filePath = public_path($path);
            $student = auth()->user();
            MobileApplicationDonwloads::create(['app_id' => $version->id, 'version_id' => $version->latest_version->id, 'student_id' => $student->student_id]);
            return response(['message' => 'Donwload Success'], 200);
            //return response()->download($filePath);
            //return response(compact('student'), 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    public function student_logout()
    {
        auth()
            ->user()
            ->tokens()
            ->delete();
        return response(['message' => ' Logout Success..'], 200);
    }
    function teacher_image(Request $request)
    {
        $staff = Staff::find($request->staff);
        $image = $staff->profile_picture();
        $image = 'http://one.bma.edu.ph' . $image;
        return response(['image' => $image], 200);
    }
}
