<?php

namespace App\Http\Controllers\GeneralController;

use App\Http\Controllers\Controller;
use App\Mail\ApplicantBriefingNotification;
use App\Mail\ApplicantEmail;
use App\Models\ApplicantAccount;
use App\Models\ApplicantBriefing;
use App\Models\ApplicantDocuments;
use App\Models\ApplicantEntranceExamination;
use App\Models\ApplicantExaminationAnswer;
use App\Models\ApplicantMedicalAppointment;
use App\Models\CourseOffer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ApplicantController extends Controller
{
    /* Applicant Panel */
    public function applicant_view(Request $_request)
    {
        $_courses = CourseOffer::all();
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_applicants =  $_course->applicant_not_verified;
        return view('pages.general-view.applicants.list_view', compact('_applicants', '_course', '_courses'));
    }
    public function pre_applicant_view(Request $_request)
    {
        $_courses = CourseOffer::all();
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_applicants =  $_course->student_pre_registrations;
        return view('pages.general-view.applicants.list_view', compact('_applicants', '_course', '_courses'));
    }
    public function applicant_profile(Request $_request)
    {
        $_account = ApplicantAccount::find(base64_decode($_request->_student));
        $_applicants = $_account->course->applicant_not_verified;
        return view('pages.general-view.applicants.profile_view', compact('_account', '_applicants'));
    }
    public function applicant_document_notification(Request $_request)
    {
        $_applicant = ApplicantAccount::find(base64_decode($_request->_applicant));
        $_email_model = new ApplicantEmail();
        //return $_applicant->email;
        Mail::to($_applicant->email)->send($_email_model->document_notificaiton($_applicant));
        return back()->with('success', 'Successfully Send the Notification');
    }
    public function applicant_document_review(Request $_request)
    {
        $_document = ApplicantDocuments::find(base64_decode($_request->_document));
        $_email_model = new ApplicantEmail();
        if ($_request->_verification_status) {
            $_document->is_approved = 1;
            $_document->staff_id = Auth::user()->staff->id;
            $_document->save();
            if (count($_document->account->applicant_documents) == count($_document->account->document_status)) {
                Mail::to($_document->account->email)->send($_email_model->document_verified($_document));
            }
            //return $_document->account->document_status->count();
            //Mail::to($_document->account->email)->send($_email_model->document_approved($_document->account, $_document));
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
    public function applicant_verified(Request $_request)
    {
        $_courses = CourseOffer::all();
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_applicants =  $_course->applicant_verified;
        return view('pages.general-view.applicants.list_view', compact('_applicants', '_course', '_courses'));
    }
    public function applicant_removed(Request $_request)
    {
        $_account = ApplicantAccount::find(base64_decode($_request->_applicant));
        $_account->is_removed = 1;
        $_account->save();
        return back()->with("success", 'Successfully Removed');
    }

    public function applicant_list(Request $_request)
    {
        $applicant = ApplicantAccount::where('course_id', $_request->course)->where('is_removed', 0)->get();
        return compact('applicant');
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

    /* Applicant Payment Verification */
    public function applicant_payment_verification(Request $_request)
    {
        $_courses = CourseOffer::all();
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_applicants =  $_course->applicant_payment_verification;
        return view('pages.general-view.applicants.payment-verification', compact('_applicants', '_course', '_courses'));
    }
    /* Applicant Payment Verified */
    public function applicant_payment_verified(Request $_request)
    {
        $_courses = CourseOffer::all();
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_applicants =  $_course->applicant_payment_verified;
        return view('pages.general-view.applicants.payment-verified', compact('_applicants', '_course', '_courses'));
    }

    public function entrance_examination_notification(Request $_request)
    {
        $_courses = CourseOffer::all();
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
        $_applicants = ApplicantMedicalAppointment::where('is_removed', false)->where('is_approved', false)->get();
        $_for_medical = ApplicantBriefing::join('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_briefings.applicant_id')->whereNull('ama.applicant_id')->where('applicant_briefings.is_removed', false)->get();
        $_scheduled = ApplicantMedicalAppointment::where('is_removed', false)->where('is_approved', false)->get();
        $_result = ApplicantMedicalAppointment::where('is_removed', false)->where('is_approved', true)->get();

        $_applicants = $_request->view == 'waiting_scheduled' ? $_for_medical : $_applicants;
        $_applicants = $_request->view == 'scheduled' ? $_scheduled : $_applicants;
        $_applicants = $_request->view == 'waiting_result' ? $_result : $_applicants;

        $_details = array(
            array('waiting for Scheduled', count($_for_medical), 'waiting_scheduled'),
            array('scheduled', count($_scheduled), 'scheduled'),
            array('waiting for Medical result', count($_result), 'waiting_result'),/*  array('pending'), array('fit to enroll'), array('disqualied') */
        );
        return view('pages.general-view.applicants.medical.overview_medical', compact('_courses', '_details', '_applicants'));
        /* try {
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
        } */
    }
}
