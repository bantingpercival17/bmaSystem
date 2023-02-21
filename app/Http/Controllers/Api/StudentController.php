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

    public function student_update_information(Request $_request)
    {
        $_fields = $_request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'middleName' => 'required | min:3',
            'extensionName' => 'required | min:2',
            'birthday' => 'required',
            'birthPlace' => 'required',
            'civilStatus' => 'required',
            'religion' => 'required',
            'nationality' => 'required',
            'street' => 'required',
            'barangay' => 'required',
            'municipality' => 'required',
            'province' => 'required',
            'zipCode' => 'required',
            'contactNumber' => 'required | numeric| min:12',
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
            $tuition_assessment = [];
            $units = [];
            if ($enrollment_assessment) {

                $tuition_fees = $enrollment_assessment->course_level_tuition_fee();
                if ($tuition_fees) {
                    $tags = $tuition_fees->semestral_fees();
                }
                $tuition_assessment = $enrollment_assessment->payment_assessment;
                $units = $enrollment_assessment->course->units($enrollment_assessment)->units;
            }
            $tuition = compact('tuition_assessment', 'tags', 'units');
            $data = compact('academic', 'registration', 'enrollment_assessment', 'tuition');
            return response(['data' => $data], 200);
        } catch (Exception $error) {
            $this->debugTrackerStudent($error);
            return response(['error' => $error->getMessage()], 505);
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
