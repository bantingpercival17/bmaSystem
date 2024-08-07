<?php

namespace App\Http\Controllers;

use App\Models\ComprehensiveExaminationExaminee;
use App\Models\DeploymentAssesment;
use App\Models\DocumentRequirements;
use App\Models\Documents;
use App\Models\Examination;
use App\Models\ExaminationCategory;
use App\Models\ExaminationQuestion;
use App\Models\ShipboardAssessmentDetails;
use App\Models\ShipboardExamination;
use App\Models\ShipboardExaminationAnswer;
use App\Models\ShipBoardInformation;
use App\Models\ShipboardJournal;
use App\Models\ShipboardPerformanceReport;
use App\Models\Staff;
use App\Models\StudentDetails;
use App\Models\StudentTraining;
use App\Models\TrainingCertificates;
use App\Report\OnboardTrainingReport;
use App\Report\OnboardTraningReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardTrainingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('onboard');
    }
    public function index()
    {
        $_embarked = ShipBoardInformation::where('shipboard_status', '!=', 'complete')->count();
        $_disembarked = ShipBoardInformation::/* where('shipboard_status', 'inc')- */where('shipboard_status', 'complete')->count();

        //$_disembarked = ShipBoardInformation::where('disembarked', '!=', null)->where('shipboard_status', 'inc')->whereOr('shipboard_status', 'complete')->count();
        return view('onboardtraining.dashboard.view', compact('_embarked', '_disembarked'));
    }

    public function embarked_monitoring_view(Request $_request)
    {
        $_shipboard_monitoring_bsme = ShipBoardInformation::join('enrollment_assessments as ea', 'ea.student_id', 'ship_board_information.student_id')->where('ea.course_id', 1)->orderBy('ship_board_information.embarked', 'DESC')/* ->where('ship_board_infomation.shipboard_status', '!=', 'complete') */->distinct()->get('ea.student_id');
        $_shipboard_monitoring_bsmt = ShipBoardInformation::join('enrollment_assessments as ea', 'ea.student_id', 'ship_board_information.student_id')->where('ea.course_id', 2)->orderBy('ship_board_information.embarked', 'DESC')/* ->where('ship_board_infomation.shipboard_status', '!=', 'complete') */->distinct()->get('ea.student_id');
        //return count($_shipboard_monitoring);
        return view('onboardtraining.dashboard.embarked_list_view', compact('_shipboard_monitoring_bsme', '_shipboard_monitoring_bsmt'));
    }
    public function midshipman_view(Request $_request)
    {
        $_student_details = new StudentDetails();
        $_shipboard_application = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')->join('deployment_assesments', 'student_details.id', 'deployment_assesments.student_id')->whereNull('staff_id')->orderby('deployment_assesments.id', 'desc')->paginate(10);
        $_documents = Documents::where('is_removed', false)->where('document_propose', 'PRE-DEPLOYMENT')->orderByRaw('CHAR_LENGTH("document_name")')->get();

        $_certificates = TrainingCertificates::where('is_removed', 1)->orderByRaw('CHAR_LENGTH("training_name")')->get();
        $_document_requirement = DocumentRequirements::where('student_id', Auth::user()->student_id)->where('is_removed', 0)->get();
        // Document Status
        $_document_status = DocumentRequirements::where('student_id', Auth::user()->student_id)->where('is_removed', false)->where('document_status', true)->get();
        $_students = $_request->_cadet ? $_student_details->student_search($_request->_cadet) : $_shipboard_application;
        $_midshipman = $_request->_midshipman ? $_student_details->find(base64_decode($_request->_midshipman)) : [];
        return view('onboardtraining.student.view', compact('_midshipman', '_students', '_certificates', '_documents', '_document_requirement', '_document_status'));
    }
    public function midshipman_view_v2(Request $_request)
    {
        $student_details = new StudentDetails();
        $midshipman = $_request->midshipman ? $student_details->find(base64_decode($_request->midshipman)) : []; // Student View Porfile
        $shipboard_application = $student_details->shipboard_application_list()->get(); // List of Shipboard Application
        $students = $_request->students ? $student_details->student_search($_request->students) : $shipboard_application; // Student Search
        return view('onboardtraining.student.view_v2', compact('midshipman', 'students'));
    }
    public function shipboard_application_verification(Request $_request)
    {
        try {
            $_document_verification = DocumentRequirements::find(base64_decode($_request->_document)); // Get the Document
            // The the Shipboard Information
            if ($_document_verification->deployment_id) {
                $shipboard_information = ShipBoardInformation::find($_document_verification->deployment_id);
            }
            if ($_request->document_status == 1) {
                // Check the Input Document Status
                $_document_verification->document_status = 1; // Set the value for the update of the document
            } else {
                // Set the Update for the following Columns
                $_document_verification->document_status = 2;
                $_document_verification->document_comment = $_request->_comment;
                if ($_document_verification->deployment_id) {
                    $shipboard_information->update(['is_approved' => false]);
                }
            }
            $_document_verification->staff_id = Auth::user()->staff->id;
            $_document_verification->save(); // Update the values of Document Verification
            // The total number of Document Requirements
            $_documents = Documents::where('is_removed', false)
                ->where('document_propose', 'PRE-DEPLOYMENT')
                ->with('student_upload_documents')
                ->orderByRaw('CHAR_LENGTH("document_name")')
                ->count();
            // Get the Approved Documents
            if ($_document_verification->deployment_id) {
                $_document_status = $shipboard_information->document_requirements_approved->count();
                if ($_documents == $_document_status) {
                    $shipboard_information->update(['is_approved' => true]);
                    $_deployment = DeploymentAssesment::where('student_id', $_document_verification->student_id)->where('is_removed', false)->first();
                    $_deployment->staff_id = Auth::user()->staff->id;
                    $_deployment->save();
                }
            }
            return back()->with('message', 'Successfully Verified!');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
            //return response(['error' => $error->getMessage()], 505);
        }
    }
    public function midshipman_certificate_store(Request $_request)
    {
        $_request->validate([
            '_cer_code' => 'required',
        ]);
        $_certificate = array(
            'student_id' => base64_decode($_request->_cadet),
            'training_id' => base64_decode($_request->_certificate),
            'certificate_number' => $_request->_cer_code,
            'staff_id' => Auth::user()->staff->id,
            'is_active' => 1,
            'is_removed' => 0,
        );
        //return $_certificate;
        StudentTraining::create($_certificate);
        //SubjectClass::create($_subject_class_detail);
        return back()->with('message', 'Successfully Added Certificates!');
    }
    public function onboard_info_store(Request $_request)
    {
        $_request->validate([
            'company_name' => 'string | required',
            '_ship_name' => 'string | required',
            '_type_vessel' => 'string | required',
            '_ship' => 'string | required',
            '_shipboard_status' => 'string | required',
            '_sbt_batch' => 'string | required',
            '_embarked' => 'date | required',
        ]);

        $_shipboard_info = array(
            'student_id' => $_request->_student_id,
            'company_name' => $_request->company_name,
            'vessel_name' => $_request->_ship_name,
            'vessel_type' => $_request->_type_vessel,
            'shipping_company' => $_request->_ship,
            'shipboard_status' => $_request->_shipboard_status,
            'sbt_batch' => $_request->_sbt_batch,
            'embarked' => $_request->_embarked,
        );
        $_check  = ShipBoardInformation::where('student_id', $_request->_student_id)->first();
        if ($_check) {
            ShipBoardInformation::where('student_id', $_request->_student_id)->update([
                'company_name' => $_request->company_name,
                'vessel_name' => $_request->_ship_name,
                'vessel_type' => $_request->_type_vessel,
                'shipping_company' => $_request->_ship,
                'shipboard_status' => $_request->_shipboard_status,
                'sbt_batch' => $_request->_sbt_batch,
                'embarked' => $_request->_embarked,
                'disembarked' => $_request->_disemabarke ?: null
            ]);
            return redirect(route('onboard.midshipman') . '?_midshipman=' . base64_encode($_request->_student_id))->with('success', 'Successfully Updated');
        } else {
            ShipBoardInformation::create($_shipboard_info);
            return redirect(route('onboard.midshipman') . '?_midshipman=' . base64_encode($_request->_student_id))->with('success', 'Successfully Submitted');
        }
    }
    public function onboard_training_view(Request $_request)
    {
        $_student_detials = new StudentDetails();
        $_midshipman = $_request->_midshipman ? StudentDetails::find(base64_decode($_request->_midshipman)) : [];
        $_shipboard_monitoring = $_request->_cadet ? $_student_detials->student_search($_request->_cadet) : $_student_detials->student_shipboard_journals()->paginate(10);
        if ($_midshipman) {
            $_department = $_midshipman->enrollment_assessment->course_id == 1 ? "BSMARE" : "BSMT";
            $_assessors = Staff::where('department', $_department)->where('is_removed', false)->get();
        } else {
            $_assessors = [];
        }
        return view('onboardtraining.shipboard.view', compact('_midshipman', '_shipboard_monitoring', '_assessors'));
    }
    public function  onboard_journal_view(Request $_request)
    {
        $_journals = ShipboardJournal::where('month', base64_decode($_request->_j))->where('student_id', base64_decode($_request->_midshipman))->where('is_removed', false)->get();
        $_midshipman = $_journals[0]->student;

        return view('onboardtraining.shipboard.document', compact('_midshipman', '_journals'));
    }
    public function onboard_narative_approved(Request $_request)
    {
        $_data = ShipboardJournal::find(base64_decode($_request->_n));
        $_data->is_approved = 1;
        $_data->staff_id = Auth::user()->staff->id;
        $_data->save();
        return back()->with('success', 'Successfully Approved');
    }
    public function onboard_narative_disapproved(Request $_request)
    {
        $_data = ShipboardJournal::find(base64_decode($_request->_narative));
        $_data->is_approved = 2;
        $_data->feedback = $_request->_feedback;
        $_data->staff_id = Auth::user()->staff->id;
        $_data->save();
        return back()->with('success', 'Narative Report Disapproved');
    }

    public function onboard_narative_summary_report(Request $_request)
    {
        try {
            $_generate_report = new OnboardTrainingReport();
            $_data = StudentDetails::find(base64_decode($_request->_midshipman));
            return $_generate_report->narative_summary_report($_data);
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function onboard_narative_summary_report_v2(Request $_request)
    {
        try {
            $_generate_report = new OnboardTrainingReport();
            $_data = StudentDetails::find(base64_decode($_request->_midshipman));
            return $_generate_report->narative_summary_report_v2($_data);
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function onboard_monthly_summary_report(Request $_request)
    {
        try {
            $_generate_report = new OnboardTrainingReport();
            $_data = StudentDetails::find(base64_decode($_request->_midshipman));
            return $_generate_report->monthly_summary_report($_data, $_request->_month);
        } catch (Exception $error) {
            return $error->getMessage();
            return back()->with('error', $error->getMessage());
        }
    }
    public function onboard_monthly_summary_report_v2(Request $_request)
    {
        try {
            $generateReport = new OnboardTrainingReport();
            $student = StudentDetails::find(base64_decode($_request->_midshipman));
            $narativeReport = ShipboardPerformanceReport::find(base64_decode($_request->narativeReport));
            return $generateReport->monthlySummaryReport($student, $narativeReport);
            //return $_generate_report->monthly_summary_report($_data);
        } catch (Exception $error) {
            return $error->getMessage();
            return back()->with('error', $error->getMessage());
        }
    }
    # Onboard Examination Assessment
    public function onboard_examination(Request $_request)
    {
        try {
            $length = 10;
            $_exam_code = 'BMA-' . substr(str_shuffle(str_repeat($x = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
            $_data = array(
                'student_id' => base64_decode($_request->_midshipman),
                'examination_code' => $_exam_code,
                'staff_id' => Auth::user()->staff->id
            );
            //return $this->onboard_examination_setup(base64_decode($_request->_midshipman), $_examination);
            $_check = ShipboardExamination::where('student_id', base64_decode($_request->_midshipman))->where('is_removed', false)->first();
            if ($_check) {
                $_check->is_removed = true;
                $_check->save();
            }
            $_examination = ShipboardExamination::create($_data);
            $this->onboard_examination_setup(base64_decode($_request->_midshipman), $_examination);
            return back()->with('success', 'Examination Approved');
        } catch (Exception $error) {
            return $error->getMessage();
            return back()->with('error', $error->getMessage());
        }
    }
    # Onboard Examination Assessment
    public function onboard_examination_setup($_data, $_examination_code)
    {
        $_student = StudentDetails::find($_data); // Student
        $_shipboard_information = $_student->shipboard_training;
        $_examination_name = $_student->enrollment_assessment->course_id == 1 ? 'ONBOARD EXAMINATION BSMARE' : 'ONBOARD EXAMINATION BSMT'; // EXAMINATION NAME
        $_examination = Examination::where('examination_name', $_examination_name)->where('department', 'college')->where('is_removed', false)->first();
        $_general_question = ExaminationCategory::where('category_name', 'GENERAL QUESTION')->where('examination_id', $_examination->id)->where('is_removed', false)->first(); // GET THE GENERAL QUESTION CATEGORY
        $_training_record_book = ExaminationCategory::where('category_name', 'TRB')->where('examination_id', $_examination->id)->where('is_removed', false)->first(); // GET THE TRAINING RECORD BOOK CATEGORY
        $_custom_category = ExaminationCategory::where('category_name', $_shipboard_information->vessel_type)->where('examination_id', $_examination->id)->where('is_removed', false)->first(); // GET THE CATEGORY
        $_categories = array(
            array($_general_question, 10),
            array($_training_record_book, 20),
            array($_custom_category, 10)
        ); // [Category, NumberOfItems]
        foreach ($_categories as $key => $category) {
            // GET THE QUESTION PER CATEGORIES
            $_questions = ExaminationQuestion::where('category_id', $category['0']->id)->where('is_removed', false)->inRandomOrder()->limit($category[1])->get();
            foreach ($_questions as $key => $_question) {
                ShipboardExaminationAnswer::create(['examination_id' => $_examination_code->id, 'question_id' => $_question->id]);
            }
        }
    }
    # Assessment Report
    public function onboard_assessment_report(Request $_request)
    {
        $_request->validate([
            '_midshipman' => 'required',
            '_assessor' => 'required',
            '_practical_score' => 'required',
            '_oral_score' => 'required'
        ]);
        try {
            $_generate_report = new OnboardTrainingReport();
            $_data = StudentDetails::find(base64_decode($_request->_midshipman));
            $_index = array(
                'student_id' => $_data->id,
                'assesor_id' => $_request->_assessor,
                'practical_score' => $_request->_practical_score,
                'oral_score' => $_request->_oral_score,
                'staff_id' => Auth::user()->staff->id
            );
            if ($_request->_assessment_details) {
                /*  $_assessment = ShipboardAssessmentDetails::where('student_id', $_data->id)->where('is_removed', false)->first();
                if ($_assessment) {
                    $_assessment->is_removed = true;
                    $_assessment->save();
                } */
            } else {
                ShipboardAssessmentDetails::create($_index);
            }

            return $_generate_report->assessment_report($_data);
        } catch (Exception $error) {
            return $error->getMessage();
            return back()->with('error', $error->getMessage());
        }
    }
    public function onboard_assessment_report_v2(Request $_request)
    {
        try {
            $_generate_report = new OnboardTrainingReport();
            $_data = StudentDetails::find(base64_decode($_request->_midshipman));
            return $_generate_report->assessment_report($_data);
        } catch (Exception $error) {
            return $error->getMessage();
            return back()->with('error', $error->getMessage());
        }
    }
    public function onboard_performance_report(Request $request)
    {
        try {
            $performance_report = ShipboardPerformanceReport::find(base64_decode($request->report));
            $midshipman = StudentDetails::find(base64_decode($request->midshipman));
            return view('pages.onboard.mopm.performance-report', compact('performance_report', 'midshipman'));
        } catch (Exception $error) {
            $this->debugTracker($error);
            return back()->with('error', $error->getMessage());
        }
    }
    function comprehensive_examination_approved($data)
    {
        try {
            ComprehensiveExaminationExaminee::create(['student_id' => base64_decode($data), 'approved_staff_id' => Auth::user()->staff->id]);
            return back()->with('success', 'Approved Comprehesive Examination');
        } catch (\Throwable $th) {
            $this->debugTracker($th);
            return back()->with('th', $th->getMessage());
        }
    }
}
