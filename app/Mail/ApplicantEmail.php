<?php

namespace App\Mail;

use App\Models\AcademicYear;
use App\Models\ApplicantEntranceExamination;
use App\Models\ApplicantExaminationSchedule;
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
        $semester = AcademicYear::where('semester', 'First Semester')->orderBy('id', 'desc')->first();
        return $this->from('support@bma.edu.ph', 'Baliwag Maritime Academy, Inc.')
            ->subject("REGISTRATION : " . $_applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.pre-registration-notification')
            ->with(['data' => $_applicant, 'semester' => $semester]);
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

        $applicantExamination = ApplicantEntranceExamination::where('applicant_id', $_applicant->id)->where('is_removed', false)->first();
        if ($applicantExamination) {
            $_exam_code = $applicantExamination->examination_code;
        } else {
            $applicantExamination = ApplicantEntranceExamination::create(
                [
                    'applicant_id' => $_applicant->id,
                    'examination_code' => $_exam_code
                ]
            );
            // If the Payment Transaction Transaction Date
            $date =  $applicantExamination->created_at;
            // Rule to Create the Examination Scheduled
            // Monday, Wednesday and Friday 09:00 AM TO 11:00 and 02:00 TO 04:00 PM
            // Revise this Scheduled (Tuesday, Wednesday, Thursday, Saturday) 09:00 AM
            $scheduledDate = $this->examinationScheduled($date);
            $scheduleDetails = array(
                'examination_id' => $applicantExamination->id, 'applicant_id' => $_applicant->id, 'schedule_date' => $scheduledDate->format('Y-m-d H:i:s')
            );
            ApplicantExaminationSchedule::create($scheduleDetails);
        }
        return $this->from(Auth::user()->email, "BMA ACCOUNTING'S OFFICE")
            ->subject("ENTRANCE EXAMINATION PAYMENT APPROVED: " . $_applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.entrance-examination-payment-approved')
            ->with(['data' => $_applicant, 'exam_code' => $_exam_code, 'examinationDetails' => $applicantExamination, 'data1' => $scheduleDetails]);
    }
    /*  function examinationScheduled($currentDate)
    {
        $plusOne = "+1 days";
        $plusTwo = "+2 days";
        $dayName = $currentDate->format('N');
        if ($dayName == 1 || $dayName == 3) {
            $modDate = $currentDate->modify($plusTwo);
        } else if ($dayName == 5) {
            $modDate = $currentDate->modify("+3 days");
        } else {
            $modDate = $currentDate->modify($plusOne);
        }
        return $modDate;
    } */
    function examinationScheduled($currentDate)
    {
        // Array of allowed days (Tuesday, Wednesday, Thursday, Saturday)
        $allowedDays = [2, 3, 4, 6];

        // Clone the current date to avoid modifying the original
        $modDate = clone $currentDate;

        // Loop until we find an allowed day
        while (!in_array($modDate->format('N'), $allowedDays)) {
            $modDate->modify('+2 day');
        }

        // Set the time to 9:00 AM
        $modDate->setTime(9, 0);

        return $modDate;
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
    public function entrance_examination_notificaiton($_applicant)
    {
        return $this->from(Auth::user()->email, 'BALIWAG MARITIME ACADEMY, INC.')
            ->subject("ENTRANCE EXAMINATION NOFICATION : " . $_applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.payment-notification')
            ->with(['data' => $_applicant]);
    }
    function documents_notification($applicant)
    {
        return $this->from(Auth::user()->email, "BMA REGISTRAR'S OFFICE")
            ->subject("PENDING DOCUMENTARY REQUIREMENTS : " . $applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.documentary-requirements-notification')
            ->with(['data' => $applicant]);
    }
}
