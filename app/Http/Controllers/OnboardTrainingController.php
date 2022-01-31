<?php

namespace App\Http\Controllers;

use App\Models\ShipBoardInformation;
use App\Models\ShipboardJournal;
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
        $_embarked = ShipBoardInformation::where('embarked', '!=', null)/* ->where('shipboard_status', 'on going') */->count();
        $_disembarked = ShipBoardInformation::where('disembarked', '!=', null)->where('shipboard_status', 'inc')->whereOr('shipboard_status', 'complete')->count();
        return view('onboardtraining.dashboard.view', compact('_embarked', '_disembarked'));
    }

    public function embarked_monitoring_view(Request $_request)
    {
        $_shipboard_monitoring_bsme = ShipBoardInformation::join('enrollment_assessments as ea', 'ea.student_id', 'ship_board_information.student_id')->where('ea.course_id', 1)->orderBy('ship_board_information.embarked', 'DESC')->distinct()->get('ea.student_id');
        $_shipboard_monitoring_bsmt = ShipBoardInformation::join('enrollment_assessments as ea', 'ea.student_id', 'ship_board_information.student_id')->where('ea.course_id', 2)->orderBy('ship_board_information.embarked', 'DESC')->distinct()->get('ea.student_id');
        //return count($_shipboard_monitoring);
        return view('onboardtraining.dashboard.embarked_list_view', compact('_shipboard_monitoring_bsme', '_shipboard_monitoring_bsmt'));
    }
    public function midshipman_view(Request $_request)
    {
        $_student_detials = new StudentDetails();
        $_certificates = TrainingCertificates::where('is_removed', 1)->orderByRaw('CHAR_LENGTH("training_name")')->get();
        $_students = $_request->_cadet ? $_student_detials->student_search($_request->_cadet) : [];
        $_midshipman = $_request->_midshipman ? $_student_detials->find(base64_decode($_request->_midshipman)) : [];
        return view('onboardtraining.student.view', compact('_midshipman', '_students', '_certificates'));
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
        $_midshipman = $_request->_midshipman ? StudentDetails::find(base64_decode($_request->_midshipman)) : [];
        $_shipboard_monitoring = ShipboardJournal::select('student_id')->where('is_approved', null)->groupBy('student_id')->where('is_removed', false)->get();
        return view('onboardtraining.shipboard.view', compact('_midshipman', '_shipboard_monitoring'));
    }
    public function  onboard_journal_view(Request $_request)
    {
        $_journals = ShipboardJournal::where('month', base64_decode($_request->_j))->where('student_id', base64_decode($_request->_midshipman))->get();
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
}
