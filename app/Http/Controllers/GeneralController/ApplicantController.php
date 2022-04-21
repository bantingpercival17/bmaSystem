<?php

namespace App\Http\Controllers\GeneralController;

use App\Http\Controllers\Controller;
use App\Mail\ApplicantEmail;
use App\Models\ApplicantAccount;
use App\Models\ApplicantDocuments;
use App\Models\CourseOffer;
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
}