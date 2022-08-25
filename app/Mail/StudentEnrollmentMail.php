<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentEnrollmentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($_data)
    {
        $this->data = $_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@bma.edu.ph', 'BMA ENROLLMENT TEAM')
            ->subject('Enrollment Details')
            ->markdown('widgets.mail.student-enrollment-mail')->with(['data' => $this->data]);
        /* return $this->from('support@bma.edu.ph', "BMA ENROLLMENT TEAM")
            ->subject("VIRTUAL BRIEFING - APPLICANTS NUMBER: " . $this->applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.briefing-notification')->with(['data' => $this->applicant]); */
        // return $this->markdown('widgets.mail.applicant-mail.briefing-notification');
    }
}
