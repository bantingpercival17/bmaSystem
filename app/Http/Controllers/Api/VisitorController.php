<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    function visitor_logs(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        $ipAddress = $request->ip();
        $jsonData = json_encode($userAgent);

        // Generate a unique file name
        $fileName = 'data_' . $ipAddress . '.json';

        // Specify the file path
        $filePath =  '/device/' . $fileName;
        // Return the device information as a response or use it as needed
        //return response()->json($deviceInfo);
        return response()->json($ipAddress);
    }
}
