<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserDeviceDetails;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class VisitorController extends Controller
{
    function visitor_logs(Request $request, $user = 'unknown')
    {
        $userAgent = $request->header('User-Agent');
        $ipAddress = $request->ip();
        $agent = new Agent();

        $device = $agent->device();
        $browser = $agent->browser();
        $platform = $agent->platform();

        $visitorDetails = array(
            'userAgent' => $userAgent,
            'device' => $device,
            'browser' => $browser,
            'platform' => $platform,
            'robot' => $agent->isRobot()
        );
        $data = array(
            'ip_address' => $ipAddress,
            'client_name' => $user,
            'accessing_page' => request()->url(),
            'device_details' => json_encode($visitorDetails)
        );
        UserDeviceDetails::create($data);
        $bot = array('robot' => $agent->isRobot());
        return compact('bot');
    }
}
