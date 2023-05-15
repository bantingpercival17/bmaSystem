<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\DeploymentAssesment;
use App\Models\DocumentRequirements;
use App\Models\Documents;
use App\Models\ShipBoardInformation;
use App\Models\ShipboardPerformanceReport;
use App\Models\ShippingAgencies;
use App\Models\ShipboardJournal;
use Exception;
use Illuminate\Http\Request;

class ShipboardTraining extends Controller
{

    public function shipboard_performance_view(Request $_request)
    {
        try {
            $shipboard_information = ShipBoardInformation::where('student_id', auth()->user()->student_id)->with('document_requirements')->with('performance_report')->get();
            return response(['data' => compact('shipboard_information')], 200);
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
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
            }
            // Set the Shipboard Application
            $_deployment_assessment = ['student_id' => auth()->user()->student_id, 'agency_id' => $_agency, 'is_removed' => false];
            // Store the Shipboard Agecny
            $_find = DeploymentAssesment::where('student_id', auth()->user()->student_id)
                ->where('is_removed', false)
                ->first();
            $academic = AcademicYear::where('is_active', true)->first();
            $shipboard_information = ShipBoardInformation::where('student_id', auth()->user()->student_id)->first();
            if (!$shipboard_information) {
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
            }
            if (!$_find) {
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
            return compact('data', 'documents');
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
}
