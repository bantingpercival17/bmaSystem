<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}
