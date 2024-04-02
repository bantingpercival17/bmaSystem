<?php

namespace App\Http\Controllers\GeneralController;

use App\Exports\ApplicantMedicalSchedule;
use App\Http\Controllers\Controller;
use App\Mail\ApplicantBriefingNotification;
use App\Mail\ApplicantEmail;
use App\Models\ApplicantAccount;
use App\Models\ApplicantAlumnia;
use App\Models\ApplicantBriefing;
use App\Models\ApplicantBriefingSchedule;
use App\Models\ApplicantDetials;
use App\Models\ApplicantDocuments;
use App\Models\ApplicantEntranceExamination;
use App\Models\ApplicantExaminationAnswer;
use App\Models\ApplicantMedicalAppointment;
use App\Models\ApplicantMedicalResult;
use App\Models\ApplicantNoDocumentNotification;
use App\Models\ApplicantNotQualified;
use App\Models\CourseOffer;
use App\Models\DocumentRequirements;
use App\Models\Documents;
use App\Models\MedicalAppointmentSchedule;
use App\Report\ApplicantReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Svg\Tag\Rect;
use Barryvdh\DomPDF\Facade as PDF;


class ApplicantController extends Controller
{
    # Dashboard Category Function
    public function dashboard_category($_request)
    {

        $_course = CourseOffer::find(base64_decode($_request->_course)); // Find a Course
        $_categories = array(
            array('view' => 'verified-applicant', 'function' => 'verified_applicants'),
            array('view' => 'pre-registration', 'function' => 'applicant_pre_registrations'),
            array('view' => 'incomplete-document', 'function' => 'applicant_incomplete_documents'),
            array('view' => 'for-checking', 'function' => 'applicant_for_checking'), // For Verification of Document
            array('view' => 'not-qualified', 'function' => 'applicant_not_qualified'), // Not Qualified
            array('view' => 'verified', 'function' => 'applicant_verified_documents'), // Verified Documents & Quified to Take Entrance Examination
            array('view' => 'entrance-examination-payment-verification', 'function' => 'applicant_payment_verification'), // Entrance Examination Payment Verification
            array('view' => 'entrance-examination-payment-verified', 'function' => 'applicant_payment_verified'), // Entrance Examination Payment Verified
            array('view' => 'ongoing-examination', 'function' => 'applicant_examination_ongoing'), // Entrance Examination On-going
            array('view' => 'alumnia', 'function' => 'applicant_alumnia'), // Entrance Examination On-going
            array('view' => 'entrance-examination-passer', 'function' => 'applicant_examination_passed'), // Entrance Examination Passed
            array('view' => 'examination-failed', 'function' => 'applicant_examination_failed'), // Entrance Examination Failed
            array('view' => 'briefing-orientation', 'function' => 'applicant_virtual_orientation'), // Orientation
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
            $document = Documents::find($_request->document);
            $_email_model = new ApplicantEmail();
            //Mail::to($_applicant->email)->send($_email_model->document_notificaiton($_applicant, $document));
            try {
                Mail::to($_applicant->email)->bcc('registrar@bma.edu.ph')->send($_email_model->document_notificaiton($_applicant, $document));
                //Mail::to('p.banting@bma.edu.ph')->bcc('k.j.cruz@bma.edu.ph')->send($_email_model->document_notificaiton($_applicant, $document));
                $message = "Email Sent";
            } catch (Exception $error) {
                $message  =  $error->getMessage();
            }
            ApplicantNoDocumentNotification::create([
                'applicant_id' => $_applicant->id,
                'document_id' => $document->id,
                'staff_id' => Auth::user()->staff->id,
                'mail_status' => $message,
            ]);
            return back()->with('success', 'Successfully Send the Notification');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    # Document Review Function
    public function applicant_document_review(Request $_request)
    {
        try {
            $_document = ApplicantDocuments::find(base64_decode($_request->_document));
            $_email_model = new ApplicantEmail();
            //$_applicant_email = 'p.banting@bma.edu.ph';
            $_applicant_email = $_document->account->email;
            if ($_request->_verification_status) {
                $_document->is_approved = 1;
                $_document->staff_id = Auth::user()->staff->id;
                $_document->save();
                if (count($_document->account->applicant_documents) == count($_document->account->document_status)) {
                    if (!$_document->account->is_alumnia) {
                        Mail::to($_applicant_email)->send($_email_model->document_verified($_document));
                    }
                }
                return back()->with('success', 'Approved.');
            } else {
                $_document->is_approved = 2;
                $_document->staff_id = Auth::user()->staff->id;
                $_document->feedback = $_request->_comment;
                $_document->save();
                if ($_request->_comment == 'Sorry, you did not meet the required grades.') {
                    $applicant = ApplicantNotQualified::where('applicant_id', $_document->applicant_id)->where('is_removed', false)->first();
                    if (!$applicant) {
                        ApplicantNotQualified::create([
                            'applicant_id' => $_document->applicant_id,
                            'course_id' => $_document->account->course_id,
                            'academic_id' => $_document->account->academic_id,
                            'staff_id' => Auth::user()->staff->id,
                            'remarks' => $_request->_comment
                        ]);
                    }
                }
                Mail::to($_applicant_email)->send($_email_model->document_disapproved($_document));
                return back()->with('success', 'Disapproved.');
                //echo "Disapproved";
            }
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function applicant_not_qualified(Request $request)
    {
        $applicant = ApplicantAccount::find(base64_decode($request->applicant));
        $data =  ApplicantNotQualified::where('applicant_id', $applicant->id)->first();
        if (!$data) {
            ApplicantNotQualified::create([
                'applicant_id' => $applicant->id,
                'course_id' => $applicant->course_id,
                'academic_id' => $applicant->academic_id,
                'staff_id' => Auth::user()->staff->id,
                'remarks' => $request->comment
            ]);
        }
        return back();
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
        try {
            $_applicant = ApplicantAccount::find($_request->_applicant);
            $_email_model = new ApplicantEmail();
            $data = array('respond' => '404', 'message' => '');
            if (!$_applicant->applicant) {
                Mail::to($_applicant->email)->send($_email_model->pre_registration_notificaiton($_applicant));
                $data['respond'] = '200';
                $data['message'] = 'Sent Pre Registration Notification ' . $_applicant->applicant_number;
            } else {
                if (!$_applicant->applicant_documents) {
                    //Mail::to($_applicant->email)->send($_email_model->document_notificaiton($_applicant,));
                    $data['respond'] = '200';
                    $data['message'] = 'Sent Document Attachment Notification ' . $_applicant->applicant_number;
                } else {
                    $data['respond'] = '200';
                    $data['message'] = 'Done all Step' . $_applicant->applicant_number;
                }
            }
            return compact('data');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function entrance_examination_notification(Request $_request)
    {
        try {
            $_course = CourseOffer::find(base64_decode($_request->_course));
            $_applicants =  $_course->applicant_payment_verified;
            foreach ($_applicants as $key => $applicant) {
                $_applicant = new ApplicantEmail();
                Mail::to($applicant->email)->send($_applicant->payment_approved($applicant));
                //Mail::to('percivalbanting@gmail.com')->send($_applicant->payment_approved($applicant));
            }
            return back()->with('success', 'Successfully Send');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
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
    public function appllicant_examination_result(Request $_request)
    {
        try {
            $applicant = ApplicantAccount::find(base64_decode($_request->_applicant));
            $_report = new ApplicantReport();
            return $_report->applicant_examination_result($applicant);
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function appllicant_examination_result_v2(Request $request)
    {
        try {
            $examination = ApplicantEntranceExamination::find(base64_decode($request->examination));
            $report = new ApplicantReport();
            return $report->applicant_examination_result_v2($examination);
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function briefing_notification(Request $_request)
    {
        $applicant_email = [
            'charleskianespiritu79@gmail.com',
            'elposjulius@gmail.com',
            'Harolddiegovalderama@gmail.com',
            'lizarondomj029@gmail.com',
            'estrelajaywan@gmail.com',
            'haroldtayao01@gmail.com',
            'riverakenneth1919@gmail.com',
            'gabrielbgonzales250@gmail.com',
            'marcustrinidadnazar@gmail.com',
            'triounkingleighsabado@gmail.com',
            'kenarchiedcgroyon@gmail.com',
            'williardvinas14@gmail.com',
            'jimyrlace007@gmail.com',
            'salvadorjohnlloyd961@gmail.com',
            'yoursong111@gmail.com',
            'jheddejesus@gmail.com',
            'johnpaullopez635@gmail.com',
            'jedgalapon27@gmail.com',
            'neilcrisostomo0@gmail.com',
            'deguzmanj140@gmail.com',
            'ivannjosh12349@gmail.com',
            'Kimlawrencemorelos11@gmail.com',
            'rhoelramos24@gmail.com',
            'villadarezmarklaurence4@gmail.com',
            'cruzcarlo696@gmail.com',
            'jmasuncion369@gmail.com',
            'batejustinejay254@gmail.com',
            'santiagonicus008@gmail.com',
            'karltristancarillo867@gmail.com',
            'cutedarcy123456789@gmail.com',
            'tolentinokristan09@gmail.com',
            'markuslumabao5@gmail.com',
            'gelloizbugarin22@gmail.com',
            'johnsarenofficial@gmail.com',
            'rodlestergutierrez05@gmail.com',
            'manigquebernard@gmail.com',
            'valdezallen552@gmail.com',
            'gutayaejay@gmail.com',
            'njpioquinto@gmail.com',
            'valmeokimjanuel@gmail.com',
            'santosemjay31@gmail.com',
            'charlesaaronco3@gmail.com',
            'Vincentpauldelossantos0126@gmail.com',
            'yatsamboat15@gmail.com',
            'Aldrichsagala3@gmail.com',
            'dave072504@gmail.com',
            'redskysantiago@gmail.com',
            'zidjhanmasculino424@gmail.com'
        ];
        foreach ($applicant_email as  $value) {
            $applicant = ApplicantAccount::where('email', trim($value))->first();
            if ($applicant) {
                $_mail_notification = new ApplicantBriefingNotification($applicant);
                Mail::to($applicant->email)->send($_mail_notification);
                //Mail::to('p.banting@bma.edu.ph')->send($_mail_notification);
            } else {
                echo '"' . $value . '", <br>';
            }
            // $_mail_notification = new ApplicantBriefingNotification($value);
            //Mail::to('p.banting@bma.edu.ph')->send($_mail_notification);
            //Mail::to($value->email)->send($_mail_notification);
        }
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
        //$categories = array('waiting_scheduled');
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
        $dates = MedicalAppointmentSchedule::orderBy('date', 'asc')->where('is_close', false)->get();
        return view('pages.general-view.applicants.medical.overview_medical', compact('_courses', '_applicants',  '_table_content', 'dates'));
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
            if ($_request->status != 'false') {
                $_appointment->is_approved = 1;
                $_appointment->save();
                $_email_model = new ApplicantEmail();
                //$_email = 'p.banting@bma.edu.ph';
                $_email = $_appointment->account->email;
                Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_appointment_schedule($_appointment->account));
                return back()->with('success', 'Appointment Approved');
            } else {
                $_appointment->is_approved = 0;
                $_appointment->is_removed = true;
                $_appointment->save();
                return back()->with('success', 'Medical Schedule Disapproved');
            }
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function medical_appointment(Request $_request)
    {
        try {
            $data = array(
                'applicant_id' => $_request->applicant,
                'appointment_date' => $_request->date,
                'approved_by' => Auth::user()->staff->id,
                'is_approved' => 1,
                'is_removed' => false
            );
            ApplicantMedicalAppointment::create($data);
            $_email_model = new ApplicantEmail();
            //$_email = 'p.banting@bma.edu.ph';
            $applicant = ApplicantAccount::find($_request->applicant);
            $_email = $applicant->email;
            Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_appointment_schedule($applicant));
            return back()->with('success', 'Medical Schedule Success.');
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
            if ($_request->result) {
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
            }
            return back()->with('success', 'Successfully Transact');

            // return back()->with('success', 'applicant_id' . base64_decode($_request->applicant) . 'is_fit' . base64_decode($_request->result));
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function applicant_change_course(Request $_request)
    {
        try {
            ApplicantAccount::find(base64_decode($_request->applicant))->update(['course_id' => $_request->course]);
            return back()->with('success', 'Update Course');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function applicant_orientation_schedule(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'time' => 'required',
            'category' => 'required'
        ]);
        try {
            $applicant = ApplicantBriefing::where('applicant_id', base64_decode($request->applicant))->get();
            if (count($applicant) > 0) {
                ApplicantBriefing::where('applicant_id', base64_decode($request->applicant))->update(['is_removed' => true]);
            }
            ApplicantBriefing::create([
                'applicant_id'  => base64_decode($request->applicant),
                'is_completed' => false,
                'is_removed' => false
            ]);
            ApplicantBriefingSchedule::create([
                'applicant_id' => base64_decode($request->applicant),
                'schedule_date' => $request->date,
                'schedule_time' => $request->time,
                'category' => $request->category,
                'staff_id' => Auth::user()->staff->id
            ]);
            $mail = new ApplicantEmail();
            $applicant = ApplicantAccount::find(base64_decode($request->applicant));
            Mail::to($applicant->email)->bcc('registrar@bma.edu.ph')->send($mail->orientation_schedule($applicant));
            // Mail::to('k.j.cruz@bma.edu.ph')->bcc('p.banting@bma.edu.ph')->send($mail->orientation_schedule($applicant));
            return back()->with('success', 'Successfully Scheduled');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function applicant_orientation_attended(Request $request)
    {
        try {
            $orientation =  ApplicantBriefing::where('applicant_id', $request->applicant)->where('is_removed', false)->first();
            $orientation->is_completed = true;
            $orientation->save();
            $mail = new ApplicantEmail();
            $applicant = ApplicantAccount::find($request->applicant);
            Mail::to($applicant->email)->bcc('registrar@bma.edu.ph')->send($mail->orientation_attended($applicant));
            // Mail::to('k.j.cruz@bma.edu.ph')->bcc('p.banting@bma.edu.ph')->send($mail->orientation_attended($applicant));
            return back()->with('success', 'Successfully Scheduled');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    function applicant_reconsideration(Request $request)
    {
        try {
            $applicant_account = ApplicantAccount::find(base64_decode($request->_applicant));
            ApplicantDocuments::where('applicant_id', $applicant_account->id)->where('is_removed', false)->update(['is_approved' => true, 'staff_id' => Auth::user()->staff->id]);
            $account = ApplicantNotQualified::where('applicant_id', $applicant_account->id)->where('is_removed', false)->first();
            $account->is_removed = true;
            $account->save();

            return back()->with('success', 'Successfully Transact');
        } catch (\Throwable $th) {
            $this->debugTracker($th);
            return back()->with('error', $th->getMessage());
        }
    }
    function applicant_summary_reports(Request $request)
    {
        try {
            if ($request->category == 'entrance-examination') {
                $applicantTable = env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations';
                $applicantDetails = ApplicantAccount::select(
                    'applicant_accounts.*'
                )
                    ->join($applicantTable, $applicantTable . '.applicant_id', 'applicant_accounts.id')
                    ->where('academic_id', base64_decode($request->_academic))
                    ->where('applicant_accounts.is_removed', false)
                    ->groupBy('applicant_accounts.id');
                // Get the total Applicant whom take the Examination
                $totalExaminees = $applicantDetails->where($applicantTable . '.is_finish', true)
                    /* ->whereNull($applicantTable . '.is_reset')
                    ->where($applicantTable . '.is_removed', false) */
                    ->orderBy($applicantTable . '.examination_start', 'asc')
                    ->get();
                /* $totalPassed = $applicantDetails->where(function ($query) {
                    $query->select(DB::raw('COUNT(*)'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_examination_answers')
                        ->join(env('DB_DATABASE') . '.examination_question_choices', env('DB_DATABASE') . '.examination_question_choices.id', '=', env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.choices_id')
                        ->where(env('DB_DATABASE') . '.examination_question_choices.is_answer', true)
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.examination_id', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.id');
                }, '>=', function ($query) {
                    $query->select(DB::raw('IF(applicant_accounts.course_id = 3, 20, 100)'));
                })->get();
                $totalFailed = $applicantDetails->where(function ($query) {
                    $query->select(DB::raw('COUNT(*)'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_examination_answers')
                        ->join(env('DB_DATABASE') . '.examination_question_choices', env('DB_DATABASE') . '.examination_question_choices.id', '=', env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.choices_id')
                        ->where(env('DB_DATABASE') . '.examination_question_choices.is_answer', true)
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.examination_id', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.id');
                }, '<=', function ($query) {
                    $query->select(DB::raw('IF(applicant_accounts.course_id = 3, 20, 100)'));
                })->get(); */
                $reportPDF = PDF::loadView("widgets.report.applicant.applicant-entrance-examination-report", compact('totalExaminees'));
                $file_name = 'FORM RG-01 - ';
                return $reportPDF->setPaper([0, 0, 612.00, 1008.00], 'portrait')->stream($file_name . '.pdf');
                /*  return array(
                    'totalExaminees' => $totalExaminees
                ); */
                //return compact('totalExaminees', 'totalPassed');
            } else {
                return back()->with('error', 'This Page is Ongoing Developement');
            }
        } catch (\Throwable $th) {
            $this->debugTracker($th);
            return back()->with('error', $th->getMessage());
        }
    }
}
