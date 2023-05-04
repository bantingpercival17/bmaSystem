<?php

namespace App\Http\Controllers;

use App\Exports\CourseApplicantMedicalList;
use App\Exports\CourseStudentMedicalList;
use App\Models\CourseOffer;
use App\Models\MedicalAppointmentSchedule;
use App\Models\StudentDetails;
use App\Models\StudentMedicalAppointment;
use App\Models\StudentMedicalResult;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MedicalController extends Controller
{
    public function student_medical_appointment(Request $_request)
    {
        try {
            StudentMedicalAppointment::all();
            $_courses = CourseOffer::all();
            $_applicants = [];
            $_table_content = array(
                array('scheduled', 'student_medical_scheduled'),
                array('waiting for result', 'student_medical_waiting_for_result'),
                array('passed', 'student_medical_passed'),
                array('pending', 'student_medical_pending'),
                array('failed', 'student_medical_failed')
            );
            if ($_request->_course) {
                $_course = CourseOffer::find(base64_decode($_request->_course));
                foreach ($_table_content as $key => $content) {
                    $_applicants = $_request->view == $content[0] ? $_course[$content[1]] : $_applicants;
                }
            }
            return view('pages.medical.view', compact('_courses', '_applicants', '_table_content'));
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
            $this->debugTracker($error);
        }
    }

    public function student_medical_appointment_approved(Request $_request)
    {
        try {
            try {
                $_appointment = StudentMedicalAppointment::find(base64_decode($_request->appointment));
                $_appointment->is_approved = 1;
                $_appointment->save();
                return back()->with('success', 'Appointment Approved');
            } catch (Exception $error) {
                return back()->with('error', $error->getMessage());
            }
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
            $this->debugTracker($error);
        }
    }
    public function student_medical_result(Request $_request)
    {
        try {
            $_student = StudentDetails::find(base64_decode($_request->student));
            if ($_request->result) {
                $_details = array('student_id' => base64_decode($_request->student), 'is_fit' => base64_decode($_request->result), 'remarks' => $_request->remarks, 'staff_id' => Auth::user()->staff->id);
            } else {
                $_details = array('student_id' => base64_decode($_request->student), 'is_pending' => 0, 'remarks' => $_request->remarks, 'staff_id' => Auth::user()->staff->id);
            }
            $_medical_result = StudentMedicalResult::where('student_id', $_student->id)->where('is_removed', false)->first();
            if ($_medical_result) {
                $_medical_result->is_removed = true;
                $_medical_result->save();
                StudentMedicalResult::create($_details);
            } else {
                StudentMedicalResult::create($_details);
            }
            return back()->with('success', 'Successfully Transact');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
            $this->debugTracker($error);
        }
    }

    public function applicant_medical_list_report(Request $_request)
    {
        $_file_name = strtoupper(str_replace('_', '-', $_request->category)) . '-' . date('mdy') . '.xlsx'; // Set Filename
        $_report = new CourseApplicantMedicalList($_request->category);
        // $_report->setAutoSize(true);
        $_file = Excel::download($_report, $_file_name); // Download the File
        ob_end_clean();
        return $_file;
    }
    public function student_medical_list_report(Request $_request)
    {
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_file_name =  strtoupper(str_replace('_', '-', $_request->category)) . '-' . $_course->course_code . '-' . date('mdy') . '.xlsx'; // Set Filename
        $_report = new CourseStudentMedicalList($_request->category, $_course);
        $_file = Excel::download($_report, $_file_name); // Download the File
        ob_end_clean();
        return $_file;
    }
    public function appointment_view(Request $request)
    {
        try {
            $dates = MedicalAppointmentSchedule::orderBy('date','desc')->get();
            return view('pages.medical.schedule', compact('dates'));
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
            $this->debugTracker($error);
        }
    }
    public function appointment_store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'capacity' => 'required'
        ]);
        try {
            $data = MedicalAppointmentSchedule::where('date', $request->date)->first();
            if (!$data) {
                MedicalAppointmentSchedule::create([
                    'date' => $request->date,
                    'capacity' => $request->capacity
                ]);
                return back()->with('success', 'Successfuly Created');
            }
            return back()->with('error', 'This Date is already Existing.');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
            $this->debugTracker($error);
        }
    }
}
