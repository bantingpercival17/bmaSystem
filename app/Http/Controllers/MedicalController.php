<?php

namespace App\Http\Controllers;

use App\Models\CourseOffer;
use App\Models\StudentDetails;
use App\Models\StudentMedicalAppointment;
use App\Models\StudentMedicalResult;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalController extends Controller
{
    public function student_medical_appointment(Request $_request)
    {
        try {
            $_courses = CourseOffer::all();

            //return view('pages.medical.view', compact('_courses'));
            StudentMedicalAppointment::all();
            $_courses = CourseOffer::all();
            $_applicants = StudentMedicalAppointment::where('is_removed', false)->where('is_approved', false)->get();
            $_scheduled = StudentMedicalAppointment::select('student_medical_appointments.*')
                ->join('student_details', 'student_details.id', 'student_medical_appointments.student_id')
                ->whereNull('is_approved')
                ->orderBy('appointment_date', 'asc')->get();
            $_approved = StudentMedicalAppointment::select('student_medical_appointments.*')
                ->join('student_details', 'student_details.id', 'student_medical_appointments.student_id')
                ->where('is_approved', true)
                ->orderBy('appointment_date', 'asc')->get();
            /* $_scheduled = StudentMedicalAppointment::select('applicant_medical_appointments.*')->join('applicant_accounts', 'applicant_accounts.id', 'applicant_medical_appointments.applicant_id')
                ->where('applicant_accounts.is_removed', false)
                ->where('applicant_medical_appointments.is_removed', false)
                ->where('applicant_medical_appointments.is_approved', false)
                ->orderBy('appointment_date', 'asc')->get(); */
            /*   $_result = StudentMedicalAppointment::select('applicant_medical_appointments.*')->leftJoin('applicant_medical_results as amr', 'amr.applicant_id', 'applicant_medical_appointments.applicant_id')->whereNull('amr.applicant_id')->where('applicant_medical_appointments.is_removed', false)->where('applicant_medical_appointments.is_approved', true)->get();


            $_passed  = StudentMedicalAppointment::select('applicant_medical_appointments.*')
                ->join('applicant_medical_results as amr', 'amr.applicant_id', 'applicant_medical_appointments.applicant_id')
                ->where('applicant_medical_appointments.is_removed', false)
                ->where('applicant_medical_appointments.is_approved', true)->groupBy('amr.applicant_id')->where('amr.is_fit', true)->get();
            $_pending = StudentMedicalAppointment::select('applicant_medical_appointments.*')
                ->join('applicant_medical_results as amr', 'amr.applicant_id', 'applicant_medical_appointments.applicant_id')
                ->where('applicant_medical_appointments.is_removed', false)
                ->where('applicant_medical_appointments.is_approved', true)->groupBy('amr.applicant_id')->where('is_pending', 0)->get();
            $_failed = StudentMedicalAppointment::select('applicant_medical_appointments.*')
                ->join('applicant_medical_results as amr', 'amr.applicant_id', 'applicant_medical_appointments.applicant_id')
                ->where('applicant_medical_appointments.is_removed', false)
                ->where('applicant_medical_appointments.is_approved', true)->groupBy('amr.applicant_id')->where('amr.is_fit', false)->get();
 */
            /*  $_applicants = $_request->view == 'scheduled' ? $_scheduled : $_applicants;
            $_applicants = $_request->view == 'waiting for Medical result' ? $_result : $_applicants;
            $_applicants = $_request->view == 'passed' ? $_passed : $_applicants;
            $_applicants = $_request->view == 'pending' ? $_pending : $_applicants;
            $_applicants = $_request->view == 'failed' ? $_failed : $_applicants; */
            $_applicants = $_request->view == 'scheduled' ? $_scheduled : $_applicants;
            $_applicants = $_request->view == 'approved' ? $_approved : $_applicants;

            $_details = array(
                array('scheduled', count($_scheduled), 'scheduled'),
                array('approved', count($_approved), 'approved'),
                //array('waiting for Medical result', count($_result), 'waiting_result'),/*  array('pending'), array('fit to enroll'), array('disqualied') */
            );
            $_results = array(
                //  array('passed', count($_passed), 'waiting_scheduled'),
                // array('pending', count($_pending), 'scheduled'),
                //array('failed', count($_failed), 'waiting_result'),/*  array('pending'), array('fit to enroll'), array('disqualied') */
            );
            /*   if ($_request->_students) {
              
                $_student = explode(',', $_request->_students);
                $_count = count($_student);
                if ($_count > 1) {
                    $_students = ApplicantDetials::where('last_name', 'like', "%" . trim($_student[0]) . "%")
                        ->where('first_name', 'like', "%" . trim($_student[1]) . "%")
                        ->orderBy('last_name', 'asc');
                } else {
                    $_students = ApplicantDetials::select('applicant_medical_appointments.*')
                        ->join('applicant_medical_appointments', 'applicant_detials.applicant_id', 'applicant_medical_appointments.applicant_id')
                        ->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%");
                 
                }
                $_applicants = $_students->leftJoin('applicant_medical_results as amr', 'amr.applicant_id', 'applicant_medical_appointments.applicant_id')
                  
                    ->where('applicant_medical_appointments.is_removed', false)
                    ->where('applicant_medical_appointments.is_approved', true)->groupBy('applicant_detials.applicant_id')->get();
              
            } */

            return view('pages.medical.view', compact('_courses', '_details', '_applicants', '_results'));
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
        }
    }

    public function student_medical_appointment_approved(Request $_request)
    {
        try {
            try {
                $_appointment = StudentMedicalAppointment::find(base64_decode($_request->appointment));
                $_appointment->is_approved = 1;
                $_appointment->save();
                //$_email_model = new ApplicantEmail();
                //$_email = 'p.banting@bma.edu.ph';
                //$_email = $_appointment->account->email;
                //  Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_appointment_schedule($_appointment->account));

                return back()->with('success', 'Appointment Approved');
            } catch (Exception $error) {
                return back()->with('error', $error->getMessage());
            }
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
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

            /* $_email_model = new ApplicantEmail();
            $_email = $_applicant->email;
            //$_email = 'p.banting@bma.edu.ph';

            if ($_request->result) {
                if (base64_decode($_request->result) == 1) {
                    Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_result_passed($_applicant));
                    //return "Passed";
                } else {
                    //return "Failed";
                    Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_result_passed($_applicant));
                }
            } else {
                //return "Pending";
                Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_result_passed($_applicant));
            } */
            return back()->with('success', 'Successfully Transact');

            // return back()->with('success', 'applicant_id' . base64_decode($_request->applicant) . 'is_fit' . base64_decode($_request->result));
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
}
