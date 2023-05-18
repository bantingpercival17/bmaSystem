<?php

namespace App\Mail;

use App\Models\ApplicantEntranceExamination;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ApplicantEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
    // Pre Registration Notification Email
    public function pre_registration_notificaiton($_applicant)
    {
        return $this->from(Auth::user()->email, 'Baliwag Maritime Academy, Inc.')
            ->subject("PRE-REGISTRATION : " . $_applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.pre-registration-notification')
            ->with(['data' => $_applicant]);
    }

    // Document Attachment Notification Email
    public function document_notificaiton($_applicant, $document)
    {
        return $this->from(Auth::user()->email, 'Baliwag Maritime Academy, Inc.')
            ->subject("DOCUMENT ATTACHMENT : " . $_applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.document-notification')
            ->with(['data' => $_applicant, 'document' => $document]);
    }
    // Approved Documents Email
    public function document_approved($_applicant, $_document)
    {
        return $this->from(Auth::user()->email, "BMA REGISTRAR'S OFFICE")
            ->subject("DOCUMENT VERIFICATION : " . $_applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.document-verification')
            ->with(['data' => $_document]);
    }
    // Disapproved Documents Email
    public function document_disapproved($_document)
    {
        return $this->from(Auth::user()->email, "BMA REGISTRAR'S OFFICE")
            ->subject("DOCUMENT VERIFICATION : " . $_document->account->applicant_number)
            ->markdown('widgets.mail.applicant-mail.document-verification')
            ->with(['data' => $_document]);
    }
    // Approved All the Documents
    public function document_verified($_document)
    {
        return $this->from(Auth::user()->email, "BMA REGISTRAR'S OFFICE")
            ->subject("APPLICATION QUALIFIED : " . $_document->account->applicant_number)
            ->markdown('widgets.mail.applicant-mail.applicantion-qualified')
            ->with(['data' => $_document]);
    }
    // Approved Entrance Examination Payment 
    public function payment_approved($_applicant)
    {
        $length = 10;
        $_exam_code = 'CODE-' . substr(str_shuffle(str_repeat($x = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
        ApplicantEntranceExamination::create(
            [
                'applicant_id' => $_applicant->id,
                'examination_code' => $_exam_code
            ]
        );
        return $this->from(Auth::user()->email, "BMA ACCOUNTING'S OFFICE")
            ->subject("ENTRANCE EXAMINATION PAYMENT APPROVED: " . $_applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.entrance-examination-payment-approved')
            ->with(['data' => $_applicant, 'exam_code' => $_exam_code]);
    }
    public function orientation_schedule($_applicant)
    {
        return $this->from(Auth::user()->email, "BMA REGISTRAR'S OFFICE")
            ->subject("BRIEFING ORIENTION SCHEDULE : " . $_applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.orientation-schedule')
            ->with(['data' => $_applicant]);
    }
    public function orientation_attended($_applicant)
    {
        return $this->from(Auth::user()->email, "BMA REGISTRAR'S OFFICE")
            ->subject("MEDICAL SCHEDULE : " . $_applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.orientation-attended')
            ->with(['data' => $_applicant]);
    }
    public function medical_appointment_schedule($_applicant)
    {
        return $this->from(Auth::user()->email, "BMA SICKBAY'S OFFICE")
            ->subject("MEDICAL APPOINTMENT CONFIRM : " . $_applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.medical-appointment')
            ->with(['data' => $_applicant,]);
    }
    public function medical_result_passed($_applicant)
    {
        return $this->from(Auth::user()->email, "BMA SICKBAY'S OFFICE")
            ->subject("MEDICAL RESULT : " . $_applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.medical-result-passed')
            ->with(['data' => $_applicant]);
    }
}
