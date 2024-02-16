<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\BatchNumber;
use App\Models\DeploymentAssesment;
use App\Models\DocumentRequirements;
use App\Models\Documents;
use App\Models\OnboardStudentProfile;
use App\Models\ShipboardAssessmentDetails;
use App\Models\ShipboardExamination;
use App\Models\ShipboardExaminationAnswer;
use App\Models\ShipBoardInformation;
use App\Models\ShipboardPerformanceReport;
use App\Models\ShippingAgencies;
use App\Models\ShipboardJournal;
use App\Models\StudentBatch;
use App\Models\StudentDetails;
use App\Report\OnboardTrainingReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipboardTraining extends Controller
{

    public function shipboard_performance_view(Request $_request)
    {
        try {
            $shipboard_information = ShipBoardInformation::where('student_id', auth()->user()->student_id)->with('document_requirements')->with('performance_report')->orderBy('id', 'desc')->get();
            $narative_report = auth()->user()->student->narative_report;
            $shipping_company = ShippingAgencies::select('id', 'agency_name')
                ->where('is_removed', false)
                ->orderBy('agency_name')
                ->get();
            $document_requirements = Documents::where('is_removed', false)
                ->where('document_propose', 'PRE-DEPLOYMENT')
                ->with('student_upload_documents')
                ->orderByRaw('CHAR_LENGTH("document_name")')
                ->get();
            $vessel_type = ['CONTAINER VESSEL', 'GENERAL CARGO', 'TANKER', 'BULK CARIER', 'CRUISE LINE '];
            return response(['data' => compact('shipboard_information', 'narative_report', 'shipping_company', 'vessel_type', 'document_requirements')], 200);
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
        }
    }
    function profile_details(Request $request)
    {
        try {
            $user = auth()->user();
            $student = StudentDetails::with('batch')->with('onboard_profile')->with('enrollment_assessment')->with('account')->find($user->student->id);
            $batch = BatchNumber::all();
            return response(compact('student', 'batch'), 200);
        } catch (\Throwable $th) {
            $this->debugTrackerStudent($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    function store_profile_details(Request $request)
    {
        try {
            $user = auth()->user();
            $checkProfile = OnboardStudentProfile::where('student_id', $user->student->id)->first();
            if ($checkProfile) {
                $checkProfile->facebook_link = $request->facebookAccount;
                $checkProfile->mismo_account = $request->mismoAccount;
            } else {
                OnboardStudentProfile::create([
                    'student_id' => $user->student->id,
                    'facebook_link' => $request->facebookAccount,
                    'mismo_account' => $request->mismoAccount
                ]);
            }

            if ($request->batchNo) {
                StudentBatch::create([
                    'batch_id' => $request->batchNo,
                    'student_id' => $user->student->id
                ]);
            }
            return $checkProfile;
            // return response(compact('student', 'batch'), 200);
        } catch (\Throwable $th) {
            $this->debugTrackerStudent($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    function pre_deployment_requirements(Request $request)
    {
        try {
            $user = auth()->user();
            $document_requirements = Documents::where('document_propose', 'DOCUMENTS-MONITORING')->where('department_id', 4)->get();
            $onboard_requirements =  DocumentRequirements::where('document_requirements.is_removed', false)
                ->where('document_requirements.student_id', $user->student->id)->get();
            return response(compact('document_requirements', 'onboard_requirements'), 200);
        } catch (\Throwable $th) {
            $this->debugTrackerStudent($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function shipboard_performance_store(Request $_request)
    {
        $_request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'date_preferred' => 'required',
            'input' => 'required',
            'trb_tasks' => 'required',
            'trb_code' => 'required',
            'signed' => 'required',
            'remarks' => 'required',
        ]);
        try {
            $_month = date_create($_request->start_date);
            $_month = date_format($_month, 'F - Y');
            $sign = $_request->signed == 'Yes' ? 1 : 0;
            $journal = $_request->input == 'Yes' ? 1 : 0;
            $_data = array(
                'shipboard_id' => $_request->shipboard_id,
                'month' => $_month,
                'date_covered' => $_request->start_date . ":" . $_request->end_date,
                'task_trb' => $_request->trb_tasks,
                'trb_code' => $_request->trb_code,
                'date_preferred' => $_request->date_preferred,
                'daily_journal' => $journal,
                'have_signature' => $sign,
                'remarks' => $_request->remarks
            );
            $data = ShipboardPerformanceReport::create($_data);
            return response(['data' => $data], 200);
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
            $_request->header('User-Agent');
            // Create a function to Controler file to save and store the details of bugs
        }
    }

    public function onboard_enrollment(Request $_request)
    {
        $_validate['agency'] = 'required';
        $_validate['vessel_name'] = 'required';
        $_validate['vessel_type'] = 'required';
        $_validate['sea_experience'] = 'required';
        $_validate['embarked'] = 'date | required';
        $_documents = Documents::where('is_removed', false)
            ->where('document_propose', 'PRE-DEPLOYMENT')
            ->get(); // Get the Document Requirements
        // Check the Input agecy if empty and the validation to Shipping Agency
        if ($_request->agency === 'NA') {
            $_validate['shipping_company'] = 'required';
            $_validate['company_address'] = 'required';
        }
        // Set validation for Documents
        foreach ($_documents as $document) {
            $_validate[strtolower(str_replace(' ', '_', $document->document_name))] = 'required';
        }
        $_request->validate($_validate);
        try {
            $_agency = $_request->agency; // Get the Input name Agency to check if it's NA
            if ($_request->agency === 'NA') {
                $_agency = ShippingAgencies::create([
                    'agency_name' => strtoupper($_request->shipping_company),
                    'agency_address' => strtoupper($_request->company_address),
                    'staff_id' => 7,
                    'is_removed' => 0
                ]); // Store Agencies
                $_agency = $_agency->id; // Get Id
                $company_name = strtoupper($_request->shipping_company);
            } else {
                $agency = ShippingAgencies::find($_request->agency);
                $company_name = $agency->agency_name;
                $_agency = $agency->id;
            }
            // Set the Shipboard Application
            $_deployment_assessment = ['student_id' => auth()->user()->student_id, 'agency_id' => $_agency, 'is_removed' => false];
            // Store the Shipboard Agecny
            $_find = DeploymentAssesment::where('student_id', auth()->user()->student_id)
                ->where('is_removed', false)
                ->first();
            $academic = AcademicYear::where('is_active', true)->first();
            /* $shipboard_information = ShipBoardInformation::where('student_id', auth()->user()->student_id)->first();
            if ($shipboard_information) {
                
            } */
            $shipboard_information =  ShipBoardInformation::create([
                'student_id' => auth()->user()->student_id,
                'company_name' => $company_name,
                'vessel_name' => $_request->vessel_name,
                'vessel_type' => $_request->vessel_type,
                'shipping_company' => $_request->sea_experience,
                'shipboard_status' => 'ON-GOING',
                'sbt_batch' => 'SBT ' . $academic->school_year . " " . $academic->semester,
                'embarked' => $_request->embarked,
                'disembarked' => null
            ]);
            if ($_find) {
                DeploymentAssesment::create($_deployment_assessment);
            }
            foreach ($_documents as $_docu) {
                $name = strtolower(str_replace(' ', '_', $_docu->document_name)); // Get the input name
                $_data_link = $this->saveFiles($_request->file($name), 'bma-students', 'onboard'); // store to the public folder
                $_document_detials = [
                    'document_id' => $_docu->id,
                    'student_id' =>  auth()->user()->student_id,
                    'document_path' => $_data_link,
                    'file_path' => $_data_link,
                    'document_status' => null,
                    'deployment_id' => $shipboard_information->id,
                    'is_removed' => 0,
                ];
                DocumentRequirements::create($_document_detials);
            }
            return response(['data' => 'done', 'message' => 'Successfully Submitted.'], 200);
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
            $_request->header('User-Agent');
            // Create a function to Controler file to save and store the details of bugs
        }
    }
    public function upload_documents(Request $_request)
    {
        try {
            // return $_request;
            $_document = Documents::find(base64_decode($_request->document));
            $_data_link = $this->saveFiles($_request->_file, 'public', 'onboard');
            $_data_link =  $_data_link != null ?
                $_data_link = $_request->document . "~" . $_data_link :
                $_data_link = null;
            return response(['data' => $_data_link], 200);
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
            $_request->header('User-Agent');
            // Create a function to Controler file to save and store the details of bugs
        }
    }
    function upload_documents_v2(Request $request)
    {
        $request->validate([
            'file' => 'required| mimes:jpg,bmp,png',
        ]);
        try {
            # If verify the Document Data
            $documentChecker = DocumentRequirements::where([
                'student_id' => Auth::user()->student->id,
                'document_id' => $request->document, 'is_removed' => false
            ])->first();
            if ($documentChecker) {
                $documentChecker->is_removed = true;
                $documentChecker->save();
            }

            $_data_link = $this->saveFiles($request->file, 'bma-students', 'onboard/pre-deployment');
            $_data = [
                'student_id' => Auth::user()->student->id,
                'document_id' => $request->document,
                'file_path' => $_data_link,
                'document_path' => $_data_link,
                'document_status' => 0
            ];
            $data = DocumentRequirements::create($_data);
            return response(['data' => $data], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    public function reupload_documents(Request $_request)
    {
        try {
            //return $_request;
            $_document = DocumentRequirements::find(base64_decode($_request->document));
            $_data_link = $this->saveFiles($_request->file, 'bma-students', 'onboard');
            $_details = array(
                'student_id' => $_document->student_id,
                'document_id' => $_document->document_id,
                'file_path' => $_data_link,
                'document_path' => $_data_link,
            );
            if ($_data_link != null) {
                DocumentRequirements::create($_details);
                $_document->is_removed = true;
                $_document->save();
            }

            $_data_link =  $_data_link != null ?
                $_data_link = $_request->document . "~" . $_data_link :
                $_data_link = null;
            return response(['data' => $_data_link], 200);
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
            $_request->header('User-Agent');
            // Create a function to Controler file to save and store the details of bugs
        }
    }
    public function reupload_documents_v2(Request $_request)
    {
        try {
            $_document = DocumentRequirements::find($_request->document); // Get the Submitted File
            $_data_link = $this->saveFiles($_request->file, 'bma-students', 'onboard'); // Store File on the Local Folder
            $_details = array(
                'student_id' => $_document->student_id,
                'document_id' => $_document->document_id,
                'file_path' => $_data_link,
                'document_path' => $_data_link,
                'deployment_id' => $_request->deployment
            ); // Set the Data for Storing a new submitted Files
            if ($_data_link != null) {
                DocumentRequirements::create($_details);
                $_document->is_removed = true;
                $_document->save();
            } // Removed the Exsiting Data
            $_data_link =  $_data_link != null ?
                $_data_link = $_request->document . "~" . $_data_link :
                $_data_link = null;
            ShipBoardInformation::find($_document->deployment_id)->update(['is_approved' => null]);
            return response(['data' => 'done', 'message' => 'Successfully Re-Uploaded the Documents.'], 200);
            //return response(['data' => $_data_link], 200);
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
            $_request->header('User-Agent');
            // Create a function to Controler file to save and store the details of bugs
        }
    }

    public function performance_report_view(Request $_request)
    {
        try {
            $data = ShipboardPerformanceReport::with('document_attachments')->find(base64_decode($_request->id));
            $documents = [
                ['PAGE OF TRAINING RECORD BOOK', 'trb_documents', 'trb_remark'],
                ['PAGE OF SHIPS LOGBOOK', 'journal_documents', 'journal_remark'],
                ['ON THE JOB PHOTOS', 'crew_list'], ['CREWLIST OF MONTH', 'mdsd'],
                ['MDSD FOR THE MONTH', 'while_at_work'],
                ['COPY THE DAILY JOURNAL', 'while_at_work']
            ];
            $documentsV1 = [];
            $dataV1 = [];
            if ($_request->month) {
                $user = auth()->user();
                $documentsV1 =  ['Training Record Book', 'Daily Journal', 'Crew List', "Master's Declaration of Safe Departure", 'Picture while at work'];
                $dataV1 = ShipboardJournal::where([
                    /*  'student_id' => $user->student->id, */
                    'month' => $_request->month,
                    'is_removed' => false,
                ])->get();
                $dataV1 = ShipboardJournal::where('student_id', $user->student->id)->where('is_removed', false)->where('month', base64_decode($_request->month))->get();
                #$dataV1 = $_request->month;
                /* $_journals = ShipboardJournal::select('month', DB::raw('count(*) as total'))
                    ->where('student_id', Auth::user()->student_id)
                    ->groupBy('month')
                    ->get(); */
            }
            $version1 = compact('documentsV1', 'dataV1');
            return compact('data', 'documents', 'version1');
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
            $_request->header('User-Agent');
            // Create a function to Controler file to save and store the details of bugs
        }
    }
    public function performance_file_attachment(Request $request)
    {
        if ($request->document == 'PAGE OF TRAINING RECORD BOOK' || $request->document == 'PAGE OF SHIPS LOGBOOK') {
            $fields = array(
                'files.*' => 'required | mimes:jpg,bmp,png,pdf,docx',
                'remarks' => 'required'
            );
        } else {
            $fields = array(
                'files.*' => 'required | mimes:jpg,bmp,png,pdf,docx',
            );
        }
        $request->validate($fields);
        try {
            $file_link = [];
            //Get the Shipboard Information
            $shipboard = ShipboardPerformanceReport::find(base64_decode($request->shipboard));
            // Save & get the Link of the Attach File
            foreach ($request->file('files') as $file) {
                $file_link[] = $this->saveFiles($file, 'bma-students', 'onboard/report/' . $shipboard->month);
            }
            // Store the Details of Report Document
            $data = array(
                'shipboard_id' => base64_decode($request->shipboard),
                'student_id' => auth()->user()->student_id,
                'file_links' => json_encode($file_link),
                'month' => '',
                'journal_type' => $request->document,
                'remark' => $request->remarks ?: null,
                'is_removed' => false
            );
            ShipboardJournal::create($data);
            return response(['data' => 'done', 'message' => 'Successfully Upload the Documents.'], 200);
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
            $request->header('User-Agent');
            // Create a function to Controler file to save and store the details of bugs
        }
    }
    function student_onboard_mopm_report($data, $version)
    {
        try {
            $user = auth()->user();
            if ($version == 'v1') {
                $_generate_report = new OnboardTrainingReport();
                $student = StudentDetails::find($user->student->id);
                return $_generate_report->monthly_summary_report($student, $data);
                #return route('onboard.narative-report-monthly-summary') . '?_midshipman=' . base64_encode(auth()->user()->student_id) . '&_month=' . $data;
            }
            if ($version == 'v2') {
                $generateReport = new OnboardTrainingReport();
                $student = StudentDetails::find($user->student->id);
                $narativeReport = ShipboardPerformanceReport::find($data);
                return $generateReport->monthlySummaryReport($student, $narativeReport);
            }
        } catch (\Throwable $th) {
            $this->debugTrackerStudent($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    function student_onboard_assessment_view(Request $request)
    {
        try {
            $assessment = ShipboardExamination::where('student_id', auth()->user()->student_id)->where('is_removed', false)->with('result')->first();
            $shipboardInformation = ShipBoardInformation::where('student_id', auth()->user()->student_id)->where('is_removed', false)->first();
            $examinationDetails = ShipboardAssessmentDetails::where('student_id', auth()->user()->student_id)->where('is_removed', false)->first();
            return response(['assessment' => $assessment, 'examinationDetails' => $examinationDetails, 'shipboardInformation' => $shipboardInformation], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function student_onboard_assessment_verification(Request $request)
    {
        $request->validate(['examination_code' => 'required',]);
        try {
            $assessment = ShipboardExamination::where('student_id', auth()->user()->student_id)->where('is_removed', false)->first();
            if ($request->examination_code != $assessment->examination_code) {
                return response(['errors' => ['data' => ["Invalid Examination Code, Try again!"]]], 422);
            }
            $assessment->examination_start = now();
            $assessment->is_finish = 0;
            $assessment->save();
            return response(['data' => 'Examination Code Verified'], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function student_onboard_assessment_questioner()
    {
        try {
            $questioner = ShipboardExamination::where('student_id', auth()->user()->student_id)->where('is_removed', false)->first();
            $examinations = $questioner->assessment_questions;
            return response(['questions' => $examinations, 'details' => $questioner], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function student_assessment_answer(Request $request)
    {
        try {
            $questioner = ShipboardExaminationAnswer::find($request->question);
            $questioner->choices_id = $request->choices;
            $questioner->save();
            return response(['data' => 'Complete'], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function finish_onboard_assessment(Request $request)
    {
        try {
            $questioner = ShipboardExamination::find($request->examination);
            $questioner->examination_end = now();
            $questioner->is_finish = 1;
            $questioner->save();
            return response(['data' => ['message' => 'Examination Complete']], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
}
