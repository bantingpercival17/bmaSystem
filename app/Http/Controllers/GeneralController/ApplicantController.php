<?php

namespace App\Http\Controllers\GeneralController;

use App\Exports\ApplicantMedicalSchedule;
use App\Http\Controllers\Controller;
use App\Mail\ApplicantBriefingNotification;
use App\Mail\ApplicantEmail;
use App\Models\ApplicantAccount;
use App\Models\ApplicantAlumnia;
use App\Models\ApplicantBriefing;
use App\Models\ApplicantDetials;
use App\Models\ApplicantDocuments;
use App\Models\ApplicantEntranceExamination;
use App\Models\ApplicantExaminationAnswer;
use App\Models\ApplicantMedicalAppointment;
use App\Models\ApplicantMedicalResult;
use App\Models\CourseOffer;
use App\Report\ApplicantReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class ApplicantController extends Controller
{
    # Dashboard Category Function
    public function dashboard_category($_request)
    {

        $_course = CourseOffer::find(base64_decode($_request->_course)); // Find a Course
        $_categories = array(
            array('view' => 'verified-applicant', 'function' => 'verified_applicants'),
            array('view' => 'pre-registration', 'function' => 'applicant_pre_registrations'),
            array('view' => 'for-checking', 'function' => 'applicant_for_checking'), // For Verification of Document
            array('view' => 'verified', 'function' => 'applicant_verified_documents'), // Verified Documents & Quified to Take Entrance Examination
            array('view' => 'entrance-examination-payment-verification', 'function' => 'applicant_payment_verification'), // Entrance Examination Payment Verification
            array('view' => 'entrance-examination-payment-verified', 'function' => 'applicant_payment_verified'), // Entrance Examination Payment Verified
            array('view' => 'ongoing-examination', 'function' => 'applicant_examination_ongoing'), // Entrance Examination On-going
            array('view' => 'examination-passed', 'function' => 'applicant_examination_passed'), // Entrance Examination Passed
            array('view' => 'examination-failed', 'function' => 'applicant_examination_failed'), // Entrance Examination Failed
            array('view' => 'virtual-orientation', 'function' => 'applicant_virtual_orientation'), // Orientation
            array('view' => 'medical-appointment', 'function' => 'applicant_medical_appointment'), // Medical Appointment
            array('view' => 'medical-scheduled', 'function' => 'applicant_medical_scheduled'),
            array('view' => 'medical-results', 'function' => 'applicant_medical_results'),
            array('view' => 'qualified', 'function' => 'applicant_qualified_to_enrolled'),
            //array('view', 'function'),
        );
        foreach ($_categories as $key => $value) {
            if ($_request->view == $value['view']) {
                $_category = $value['function'];
                $_applicants = $_course[$value['function']];
            }
        }
        return [$_applicants, $_category];
    }
    /* Applicant List View with the different views */
    public function applicant_view(Request $_request)
    {
        try {
            $_courses = CourseOffer::all(); // Get All Course
            $_course = CourseOffer::find(base64_decode($_request->_course)); // Find a Course
            $_data = $this->dashboard_category($_request);
            $_applicants = $_data[0];
            $_category = $_data[1];
            return view('pages.general-view.applicants.list_view', compact('_applicants', '_course', '_courses', '_category'));
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function applicant_profile(Request $_request)
    {
        try {
            $_account_check = ApplicantAccount::where('id', base64_decode($_request->_applicant))->where('is_removed', true)->first();
            if ($_account_check) {
                return redirect(route('applicant-lists') . '?_course=' . base64_encode($_account_check->course_id));
            } else {
                $_account = ApplicantAccount::find(base64_decode($_request->_applicant));
                $_dashboard_category = $this->dashboard_category($_request);
                $_applicants = $_dashboard_category[0];
                $_similar_account = $_account->similar_account();
                return view('pages.general-view.applicants.profile_view', compact('_account', '_applicants', '_similar_account'));
            }
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    # View Registration Form
    public function applicant_registration_form(Request $_request)
    {
        try {
            $_report = new ApplicantReport;
            $_applicant = ApplicantAccount::find(base64_decode($_request->_applicant));
            return $_report->applicant_form($_applicant);
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    # Send a Notification for Applicant's
    public function applicant_document_notification(Request $_request)
    {
        try {
            $_applicant = ApplicantAccount::find(base64_decode($_request->_applicant));
            $_email_model = new ApplicantEmail();
            //return $_applicant->email;
            Mail::to($_applicant->email)->send($_email_model->document_notificaiton($_applicant));
            return back()->with('success', 'Successfully Send the Notification');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    # Document Review Function
    public function applicant_document_review(Request $_request)
    {
        $_document = ApplicantDocuments::find(base64_decode($_request->_document));
        $_email_model = new ApplicantEmail();
        if ($_request->_verification_status) {
            $_document->is_approved = 1;
            $_document->staff_id = Auth::user()->staff->id;
            $_document->save();
            if (count($_document->account->applicant_documents) == count($_document->account->document_status)) {
                if (!$_document->account->is_alumnia) {
                    Mail::to($_document->account->email)->send($_email_model->document_verified($_document));
                }
            }
            return back()->with('success', 'Successfully Transact.');
        } else {
            $_document->is_approved = 2;
            $_document->staff_id = Auth::user()->staff->id;
            $_document->feedback = $_request->_comment;
            $_document->save();
            Mail::to($_document->account->email)->send($_email_model->document_disapproved($_document));
            return back()->with('success', 'Successfully Transact.');
            //echo "Disapproved";
        }
    }
    # Removed Applicant Function
    public function applicant_removed(Request $_request)
    {
        $_account = ApplicantAccount::find(base64_decode($_request->_applicant));
        $_account->is_removed = 1;
        $_account->save();
        return back()->with("success", 'Successfully Removed');
    }
    public function applicant_alumnia(Request $_request)
    {
        try {
            $_data = array('applicant_id' => base64_decode($_request->_applicant), 'staff_id' => Auth::user()->staff->id);
            ApplicantAlumnia::create($_data);
            $respond = array('respond' => 202, 'message' => 'Successfully Submitted');
            return compact('respond');
        } catch (Exception $err) {
            $respond = array('respond' => 404, 'message' => $err->getMessage());
            return compact('respond');
        }
    }
    public function send_email_notification(Request $_request)
    {
        $_applicant = ApplicantAccount::find($_request->_applicant);
        $_email_model = new ApplicantEmail();
        $data = array('respond' => '404', 'message' => '');
        if (!$_applicant->applicant) {
            Mail::to($_applicant->email)->send($_email_model->pre_registration_notificaiton($_applicant));
            $data['respond'] = '200';
            $data['message'] = 'Sent Pre Registration Notification ' . $_applicant->applicant_number;
        } else {
            if (!$_applicant->applicant_documents) {
                Mail::to($_applicant->email)->send($_email_model->document_notificaiton($_applicant));
                $data['respond'] = '200';
                $data['message'] = 'Sent Document Attachment Notification ' . $_applicant->applicant_number;
            } else {
                $data['respond'] = '200';
                $data['message'] = 'Done all Step' . $_applicant->applicant_number;
            }
        }
        return compact('data');
    }
    public function entrance_examination_notification(Request $_request)
    {
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_applicants =  $_course->applicant_payment_verified;
        foreach ($_applicants as $key => $applicant) {
            $_applicant = new ApplicantEmail();
            Mail::to($applicant->email)->send($_applicant->payment_approved($applicant));
            //Mail::to('percivalbanting@gmail.com')->send($_applicant->payment_approved($applicant));
        }
        return back()->with('success', 'Successfully Send');
    }

    public function applicant_entrance_examination(Request $_request)
    {
        $_status = ['ready-for-examination', 'on-going', 'passed', 'failed'];
        $_courses = CourseOffer::all();
        $_course = CourseOffer::find(base64_decode($_request->_course));
        //return $_course->applicant_examination_ongoing;
        $_data = array($_course->applicant_examination_ready, $_course->applicant_examination_ongoing, $_course->applicant_examination_passed, $_course->applicant_examination_failed);
        $_titles = array('Applicant Examination Ready', 'On-going Examination', 'Applicant Passed', 'Applicant Failed');
        if ($_request->_status) {
            foreach ($_status as $key => $value) {
                if (base64_decode($_request->_status) == $value) {
                    $_applicants = $_data[$key];
                    $_title = $_titles[$key];
                }
            }
        } else {
            return back();
        }

        return view('pages.general-view.applicants.entrance-examination-status', compact('_courses', '_applicants', '_course', '_title'));
    }
    public function applicant_examination_reset(Request $_request)
    {
        $applicant = ApplicantAccount::find(base64_decode($_request->_applicant));
        $_examination = $applicant->examination;
        if ($_examination) {
            $_examination->is_removed = true;
            $_examination->is_reset = true;
            $_examination->save();
        }

        $_applicant = new ApplicantEmail();
        Mail::to($applicant->email)->send($_applicant->payment_approved($applicant));

        return back()->with('success', 'Successfully Reset');
    }
    public function examination_remove(Request $_request)
    {
        $_examination = ApplicantEntranceExamination::find($_request->examination);
        $_examination->is_removed = true;
        $_examination->save();
        return back();
    }
    public function applicant_examination_log(Request $_request)
    {
        try {
            $applicant = ApplicantAccount::find(base64_decode($_request->_applicant));
            $_report = new ApplicantReport();
            return $_report->applicant_examination_logs($applicant);
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function briefing_notification(Request $_request)
    {
        $_course = CourseOffer::find($_request->_course);
        foreach ($_course->applicant_examination_result('passed') as $key => $value) {
            $_mail_notification = new ApplicantBriefingNotification($value);
            //Mail::to('percivalbanting@gmail.com')->send($_mail_notification);
            Mail::to($value->email)->send($_mail_notification);
        }
        return back()->with('success', 'Sent');
    }
    public function virtual_briefing_view(Request $_request)
    {
        try {
            $_courses = CourseOffer::all();
            $_course = CourseOffer::find(base64_decode($_request->_course));
            $_applicants =  $_course->applicant_briefing;
            return view('pages.general-view.applicants.briefing_list_view', compact('_applicants', '_course', '_courses'));
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
        }
    }

    public function medical_overview(Request $_request)
    {
        $_courses = CourseOffer::all();

        $_table_content = array(
            array('waiting for Scheduled', 'waiting_scheduled'),
            array('scheduled', 'scheduled'),
            array('waiting for Medical result', 'waiting_result'),
            array('passed', 'medical_result_passed'),
            array('pending', 'medical_result_pending'),
            array('failed', 'medical_result_failed')
        );
        $_applicants = [];
        if ($_request->view) {
            $_course = CourseOffer::find(base64_decode($_request->_course));
            foreach ($_table_content as $key => $content) {
                $_applicants = $_request->view == $content[0] ? $_course[$content[1]] : $_applicants;
            }
        }
        return view('pages.general-view.applicants.medical.overview_medical', compact('_courses', '_applicants',  '_table_content'));
    }
    public function medical_schedule_download(Request $_request)
    {
        try {
            $_file = new ApplicantMedicalSchedule;
            $_file_name = strtoupper('Medical Appiontment') . '.xlsx';
            $_file = Excel::download($_file, $_file_name); // Download the File
            ob_end_clean();
            return $_file;
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
        }
    }
    public function medical_appointment_approved(Request $_request)
    {
        try {
            $_appointment = ApplicantMedicalAppointment::find(base64_decode($_request->appointment));
            $_appointment->is_approved = 1;
            $_appointment->save();
            $_email_model = new ApplicantEmail();
            //$_email = 'p.banting@bma.edu.ph';
            $_email = $_appointment->account->email;
            Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_appointment_schedule($_appointment->account));
            return back()->with('success', 'Appointment Approved');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function medical_result(Request $_request)
    {
        try {
            $_applicant = ApplicantAccount::find(base64_decode($_request->applicant));
            if ($_request->result) {
                $_details = array('applicant_id' => base64_decode($_request->applicant), 'is_fit' => base64_decode($_request->result), 'remarks' => $_request->remarks);
            } else {
                $_details = array('applicant_id' => base64_decode($_request->applicant), 'is_pending' => base64_decode($_request->result), 'remarks' => $_request->remarks);
            }
            $_medical_result = ApplicantMedicalResult::where('applicant_id', $_applicant->id)->where('is_removed', false)->first();
            if ($_medical_result) {
                $_medical_result->is_removed = true;
                $_medical_result->save();
                ApplicantMedicalResult::create($_details);
            } else {
                ApplicantMedicalResult::create($_details);
            }

            $_email_model = new ApplicantEmail();
            $_email = $_applicant->email;
            //$_email = 'p.banting@bma.edu.ph';
           /*  if ($_request->result) {
                if (base64_decode($_request->result) == 1) {
                    // Email Passed
                    Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_result_passed($_applicant));
                } else {
                    // Email Failed
                    Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_result_passed($_applicant));
                }
            } else {
                //Email "Pending";
                Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_result_passed($_applicant));
            } */
            return back()->with('success', 'Successfully Transact');

            // return back()->with('success', 'applicant_id' . base64_decode($_request->applicant) . 'is_fit' . base64_decode($_request->result));
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
}
