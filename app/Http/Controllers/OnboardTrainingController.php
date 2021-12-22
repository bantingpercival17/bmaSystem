<?php

namespace App\Http\Controllers;

use App\Models\StudentDetails;
use App\Models\StudentTraining;
use App\Models\TrainingCertificates;
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
        return view('onboardtraining.dashboard.view');
    }

    public function midshipman_view(Request $_request)
    {
        $_student_detials = new StudentDetails();
        $_certificates = TrainingCertificates::where('is_removed', 1)->orderByRaw('CHAR_LENGTH("training_name")')->get();
        $_students = $_request->_cadet ? $_student_detials->student_search($_request->_cadet) : [];
        $_cadet = $_request->search_data ? $_student_detials->find(base64_decode($_request->search_data)) : [];
        return view('onboardtraining.student.view', compact('_cadet', '_students', '_certificates'));
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
            'staff_id' => Auth::user()->id,
            'is_active' => 1,
            'is_removed' => 0,
        );
        //return $_certificate;
        StudentTraining::create($_certificate);
        //SubjectClass::create($_subject_class_detail);
        return back()->with('message', 'Successfully Added Certificates!');
    }
}
