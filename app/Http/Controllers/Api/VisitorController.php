<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class VisitorController extends Controller
{
    function visitor_logs(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        $ipAddress = $request->ip();
        $agent = new Agent();
        $device = $agent->device();
        $browser = $agent->browser();
        $platform = $agent->platform();

        $visitorDetails = array(
            'ip_address' => $ipAddress,
            'userAgent' => $userAgent,
            'device' => $device,
            'browser' => $browser,
            'platform' => $platform
        );
        // Return the device information as a response or use it as needed
        //return response()->json($deviceInfo);
        return response()->json($visitorDetails);
    }
}
