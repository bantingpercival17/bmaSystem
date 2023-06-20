<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\DeploymentAssesment;
use App\Models\DocumentRequirements;
use App\Models\Documents;
use App\Models\EnrollmentApplication;
use App\Models\PaymentTrasanctionOnline;
use App\Models\ShipBoardInformation;
use App\Models\ShipboardJournal;
use App\Models\ShippingAgencies;
use App\Models\StudentAccount;
use App\Models\StudentDetails;
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
            $student = StudentDetails::find($student->student_id);
            $profile_picture = $student->profile_picture();
            return response(['account' => $student, 'profile_picture' => $profile_picture], 200);
        } catch (Exception $error) {
            $this->debugTrackerStudent($error);
            return response(['error' => $error->getMessage()], 505);
        }
    }
    public function student_information()
    {
        try {
            $account = auth()->user();
            $student = StudentDetails::with('educational_background')->with('parent_details')->with('current_enrollment')->find($account->student_id);
            //$profile_picture = $student->profile_picture();
            return response(['student' => $student], 200);
        } catch (Exception $error) {
            $this->debugTrackerStudent($error);
            return response(['error' => $error->getMessage()], 505);
        }
    }
    public function student_update_information(Request $_request)
    {
        $_fields = $_request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'middle_initial' => 'required',
            'extension_name' => 'required | min:2',
            'birth_date' => 'required',
            'birth_place' => 'required',
            'gender' => 'required',
            'weight' => 'required',
            'height' => 'required'
        ]);
    }
    /* Student Enrollment Procudure */
    public function enrollment_overview(Request $_request)
    {
        try {
            $academic = AcademicYear::where('is_active', 1)->first(); // Get active Academic Year
            $student = StudentDetails::find(auth()->user()->student_id); // Get the login Student
            $registration = auth()->user()->student->student_enrollment_application; // Get the Registration Form
            $enrollment_assessment = auth()->user()->student->current_enrollment; // New Enrollment Year
            $medical_result = auth()->user()->student->enrollment_assessment->medical_result;
            $tags = [];
            $total_fees = [];
            $tuition_assessment = [];
            $units = [];
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
                $units = $enrollment_assessment->course->units($enrollment_assessment)->units;
                // Payment transaction
                $payment_transaction = [];
                if ($tuition_assessment) {
                    $payment_transaction = $tuition_assessment->online_payment;
                }
            }
            $tuition = compact('tuition_assessment', 'tags', 'units', 'total_fees');
            $data = compact('academic', 'registration', 'enrollment_assessment', 'tuition','medical_result');
            return response(['data' => $data], 200);
        } catch (Exception $error) {
            $this->debugTrackerStudent($error);
            return response(['error' => $error->getMessage()], 505);
        }
    }

    public function enrollment_application(Request $_request)
    {
        $_fields = $_request->validate([
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
            'household_income' => 'required',
            'dswd_listahan' => 'required',
            'homeownership' => 'required',
            'car_ownership' => 'required',
        ]);
        try {

            return response(['message' => 'Successfully Submitted.'], 200);
        } catch (Expression $error) {
            return response([
                'message' => $error
            ], 402);
        }
    }
    public function enrollment_payment_mode(Request $_request)
    {
        try {
            $student = auth()->user()->student->student_enrollment_application;
            $student->payment_mode = $_request->paymentMode;
            $student->save();
            return response(['message' => 'Successfully Submitted.'], 200);
        } catch (Expression $error) {
            return response([
                'message' => $error
            ], 402);
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
            $vessel_type = ['CONTAINER VESSEL', 'GENERAL CARGO', 'TANKER', 'BULK CARIER', 'CRUISE LINE '];

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
        } catch (Exception $error) {
            $this->debugTrackerStudent($error);
            return response(['error' => $error->getMessage()], 505);
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
        } catch (Expression $error) {
            return response([
                'message' => $error
            ], 402);
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
            $_file_link = $this->saveFiles($_request->file('file'), 'bma-students', 'accounting');
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
        } catch (Exception $error) {
            $this->debugTrackerStudent($error);
            return response(['error' => $error->getMessage()], 505);
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
        } catch (Exception $error) {
            $this->debugTrackerStudent($error);
            return response(['error' => $error->getMessage()], 505);
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
}
