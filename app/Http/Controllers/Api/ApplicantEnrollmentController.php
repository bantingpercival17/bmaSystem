<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\EducationalDetails;
use App\Models\EnrollmentApplication;
use App\Models\EnrollmentAssessment;
use App\Models\ParentDetails;
use App\Models\PaymentTrasanctionOnline;
use App\Models\StudentApplicantDetails;
use App\Models\StudentDetails;
use Illuminate\Http\Request;

class ApplicantEnrollmentController extends Controller
{
    function enrollment_overview(Request $request)
    {
        try {
            // Enrollment Procudure
            $semester = AcademicYear::where('semester', 'First Semester')->orderBy('id', 'desc')->first();
            $student = auth()->user()->student_applicant->student_details;
            // Enrollment Details
            $enrollment_application = $student ? EnrollmentApplication::with('course')->where('student_id', $student->id)->where('academic_id', $semester->id)->where('is_removed', false)->first() : [];
            $enrollment_assessment = $student ? EnrollmentAssessment::with('course')->where('student_id', $student->id)->where('academic_id', $semester->id)->where('is_removed', false)->first() : [];
            $enrollmentDetails = compact('enrollment_application', 'enrollment_assessment');
            $tuition_assessment = [];
            $tags = [];
            $units = [];
            $total_fees = [];
            $online_transaction = [];
            $payment_transaction = [];
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
            $tuitionFeeDetails =  compact('tuition_assessment', 'tags', 'units', 'total_fees', 'online_transaction', 'payment_transaction');
            $data = compact('enrollmentDetails', 'semester', 'tuitionFeeDetails');
            return response($data, 200);
        } catch (\Throwable $th) {
            $this->debugTrackerApplicant($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    function enrollment_applicant_details()
    {
        try {
            $user = auth()->user();
            $student = $user->applicant;
            return response(compact('student', 'user'), 200);
        } catch (\Throwable $th) {
            $this->debugTrackerApplicant($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    function enrollment_application_registration(Request $request)
    {
        $this->field_validation($request);
        try {
            $applicant = new ApplicantController();
            $applicant->applicant_store_information($request);
            // User Account
            $account = auth()->user();
            // Set the Student Information Data Fields;
            $studentData = array(
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
            );
            // Find if the Applicant have an Student Information
            $studentDetails = $account->student_applicant->student_details;
            if (!$studentDetails) {
                $studentDetails = StudentDetails::create($studentData);
                $applicantStudent = StudentApplicantDetails::create([
                    'student_id' => $studentDetails->id,
                    'applicant_id' => $account->id
                ]);
            }
            // Set the Educational  Attainment 
            $elementary = ['student_id' => $studentDetails->id, 'school_level' => 'Elementary School', 'school_name' => trim(ucwords(mb_strtolower($request->elementary_school_name))), 'school_address' => trim(ucwords(mb_strtolower($request->elementary_school_address))), 'graduated_year' => trim(ucwords(mb_strtolower($request->elementary_school_year))), 'school_category' => 'n/a', 'is_removed' => false];
            $high_school = ['student_id'  => $studentDetails->id, 'school_level' => 'Junior High School', 'school_name' => trim(ucwords(mb_strtolower($request->junior_high_school_name))), 'school_address' => trim(ucwords(mb_strtolower($request->junior_high_school_address))), 'graduated_year' => trim(ucwords(mb_strtolower($request->junior_high_school_year))), 'school_category' => 'n/a', 'is_removed' => false];
            $senior_high_school = ['student_id'  => $studentDetails->id, 'school_level' => 'Senior High School', 'school_name' => trim(ucwords(mb_strtolower($request->senior_high_school_name))), 'school_address' => trim(ucwords(mb_strtolower($request->senior_high_school_address))), 'graduated_year' => trim(ucwords(mb_strtolower($request->senior_high_school_year))), 'school_category' => 'n/a', 'is_removed' => false];
            $education = [$elementary, $high_school];
            if ($account->course_id != 3) {
                $education =  [$elementary, $high_school, $senior_high_school];
            } // add the Senior High School Details for the College Applicant's / Client's
            $educationDetails = $studentDetails->educational_background; // Get the Educational Attainment
            // Check if the Data is Existing
            if (count($educationDetails) > 0) {
                EducationalDetails::where('student_id', $studentDetails->id)
                    ->where('is_removed', false)->update(['is_removed' => true]); # Set into hide all Educational Details
                foreach ($education as $key => $value) {
                    $_data = EducationalDetails::where('student_id', $studentDetails->id)
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
                // Store / Save the Educational Details
                foreach ($education as $key => $value) {
                    EducationalDetails::create($value);
                }
            }
            // Parent Information
            $parentInformation = [
                'student_id' => $studentDetails->id,
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
            # Parent Details
            // Same Logic in the Educational Details
            $parentInformationData = $studentDetails->parent_details;
            if ($parentInformationData) {
                //$parentInformationData->update(['is_removed' => true]);
                $verify = ParentDetails::where($parentInformation)->first();
                if ($verify) {
                    $parentInformationData->update(['is_removed' => false]);
                } else {
                    ParentDetails::create($parentInformation);
                }
            } else {
                ParentDetails::create($parentInformation);
            }
            // Enrollment Application Details
            $semester = AcademicYear::where('semester', 'First Semester')->orderBy('id', 'desc')->first();
            $enrollment_application =  EnrollmentApplication::where(['student_id' => $studentDetails->id, 'academic_id' => $semester->id])->where('is_removed', false)->first();
            if (!$enrollment_application) {
                $_details = [
                    'student_id' => $studentDetails->id,
                    'academic_id' => $semester->id,
                    'course_id' => $account->course_id,
                    'enrollment_place' => 'online',
                    'enrollment_category' => NULL,
                    'is_removed' => false,
                ];
                EnrollmentApplication::create($_details);
            }
            return response(['message' => 'Successfully Send your Enrollment Application.'], 200);
        } catch (\Throwable $th) {
            $this->debugTrackerApplicant($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    function field_validation(Request $request)
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
        if (auth()->user()->course_id != 3) {
            $_fields += [
                'senior_high_school_name' => 'required|max:100',
                'senior_high_school_address' => 'required|max:255',
                'senior_high_school_year' => 'required|max:100',
            ];
        }
        $request->validate($_fields);
    }
    function enrollment_payment_mode(Request $request)
    {
        try {
            $semester = AcademicYear::where('semester', 'First Semester')->orderBy('id', 'desc')->first();
            $student = auth()->user()->student_applicant->student_details;
            // Enrollment Details
            $enrollment_application = EnrollmentApplication::with('course')->where('student_id', $student->id)->where('academic_id', $semester->id)->where('is_removed', false)->first();
            $enrollment_application->payment_mode = $request->paymentMode;
            $enrollment_application->save();
            return response(['message' => 'Successfully Submitted.'], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function enrollment_payment(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required',
            'amount_paid' => 'required',
            'reference_number' => 'required',
            'file' => 'required',
        ]);
        try {
            // Enrollment Procudure
            $semester = AcademicYear::where('semester', 'First Semester')->orderBy('id', 'desc')->first();
            $student = auth()->user()->student_applicant->student_details;
            // Enrollment Details
            $enrollment_assessment =  EnrollmentAssessment::with('course')->where('student_id', $student->id)->where('academic_id', $semester->id)->where('is_removed', false)->first();
            $semester = '/' . $enrollment_assessment->academic->semester . '-' . $enrollment_assessment->academic->school_year;
            $_file_link = $this->saveApplicantFile($request->file('file'), 'bma-applicants', 'accounting' . $semester);
            $assessment = $enrollment_assessment->enrollment_payment_assessment;
            $_payment_data = [
                'assessment_id' => $assessment->id,
                'amount_paid' => str_replace(',', '', $request->amount_paid),
                'reference_number' => $request->reference_number,
                'transaction_type' => 'Upon Enrollment',
                'reciept_attach_path' => $_file_link,
                'is_removed' => 0,
            ];
            if ($request->document) {
                PaymentTrasanctionOnline::find($request->document)->update(['is_removed' => true]);
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
}
