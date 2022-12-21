<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeploymentAssesment;
use App\Models\DocumentRequirements;
use App\Models\Documents;
use App\Models\ShipBoardInformation;
use App\Models\ShipboardPerformanceReport;
use Exception;
use Illuminate\Http\Request;

class ShipboardTraining extends Controller
{

    public function shipboard_performance_view(Request $_request)
    {
        try {
            $_performance_report = ShipboardPerformanceReport::where('shipboard_id', base64_decode($_request->shipboardId))
                ->where('is_removed', false)->orderBy('date_covered', 'asc')->get();
            // $_performance_report = ShipBoardInformation::find(base64_decode($_request->shipboardId))->performance_report;
            // $_performance_report = $_performance_report->with('performance_report');
            return response(['data' => $_performance_report], 200);
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
        }
    }
    public function shipboard_performance_store(Request $_request)
    {
        try {
            $_month = date_create($_request->start_date);
            $_month = date_format($_month, 'F-Y');
            $_data = array(
                'shipboard_id' => base64_decode($_request->shipboardId),
                'month' => $_month,
                'date_covered' => $_request->start_date . ":" . $_request->end_date,
                'task_trb' => $_request->task,
                'trb_code' => $_request->trb_code,
            );
            $data = ShipboardPerformanceReport::create($_data);
            return response(['data' => $data], 200);
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
            $_request->header('User-Agent');
            // Create a function to Controler file to save and store the details of bugs
        }
    }
    public function student_shipboard_application(Request $_request)
    {
        try {
            $user = auth()->user()->student_id;
            $_application  = DeploymentAssesment::select('id', 'student_id', 'agency_id')->where('student_id', $user)->where('is_removed', false)->with('shipboard_companies')->first();
            $_documents = DocumentRequirements::select(
                'id',
                'student_id',
                'document_id',
                'file_path',
                'document_path',
                'document_comment',
                'document_status',
                'updated_at',
                'created_at'
            )->where('student_id', $user)->where('is_removed', false)->with('documents')->get();
            $_data = $_application ? $_data = array(
                'company' => $_application, 'documents_list' => $_documents
            ) : null;
            return response(['data' => $_data], 200);
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
            $_request->header('User-Agent');
            // Create a function to Controler file to save and store the details of bugs
        }
    }
    public function onboard_application(Request $_request)
    {
        try {
            // Set the Shipboard Application
            $_deployment_assessment = array('student_id' => auth()->user()->student_id, 'agency_id' => base64_decode($_request->companies));
            $_find = DeploymentAssesment::where('student_id', auth()->user()->student_id)->where('is_removed', false)->first();
            if (!$_find) {
                $_assessment =   DeploymentAssesment::create($_deployment_assessment);
            } else {
                $_assessment = $_find;
            }

            // Json Decode the Document Field
            $_documents = json_decode($_request->documents);
            // Foreach the Documents
            foreach ($_documents as $key => $document) {
                $document = base64_decode($document); // Decode the Encyption Value
                $_data = explode('~', $document); // Separate the two Value
                $_document_details = array(
                    'student_id' => auth()->user()->id,
                    'document_id' => base64_decode($_data[0]),
                    'document_path' => $_data[1],
                    'file_path' => $_data[1],
                );
                // Checking if the Document is already have for the User
                $_find = DocumentRequirements::where('student_id', auth()->user()->id)->where('document_id', base64_decode($_data[0]))->where('is_removed', false)->first();
                if (!$_find) {
                    DocumentRequirements::create($_document_details);
                }
            }
            return response(['data' => $_assessment], 200);
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
}
