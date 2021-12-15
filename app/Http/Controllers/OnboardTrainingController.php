<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OnboardTrainingController extends Controller
{
    public function index()
    {
        return view('onboardtraining.dashboard.view');
    }

    public function midshipman_view(Request $_request)
    {
        $_cadet = [];
        $_data = [];
        return view('onboardtraining.student.view', compact('_cadet','_data'));
    }
}
