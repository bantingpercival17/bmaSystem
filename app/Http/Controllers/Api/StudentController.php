<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\DeploymentAssesment;
use App\Models\Documents;
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
            $student = StudentDetails::find(auth()->user()->student_id);
            $registration = auth()->user()->student->student_enrollment_application;
            $enrollment_assessment = auth()->user()->student->current_enrollment;
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
                $tuition_assessment = $enrollment_assessment->payment_assessment;
                $units = $enrollment_assessment->course->units($enrollment_assessment)->units;
                // Payment transaction
                $payment_transaction = [];
                if ($tuition_assessment) {
                    $payment_transaction = $tuition_assessment->online_payment;
                }
            }
            $tuition = compact('tuition_assessment', 'tags', 'units', 'total_fees');
            $data = compact('academic', 'registration', 'enrollment_assessment', 'tuition');
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
            'contactNumber' => 'required | numeric| min:12'
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
