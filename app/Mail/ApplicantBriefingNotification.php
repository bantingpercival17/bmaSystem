<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicantBriefingNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($_applicant)
    {
        $this->applicant = $_applicant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@bma.edu.ph', "BMA ENROLLMENT TEAM")
            ->subject("BRIEFING PROGRAM FOR INCOMING 4TH CLASS MIDSHIPMEN - APPLICANTS NUMBER: " . $this->applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.briefing-notification')->with(['data' => $this->applicant]);
        // return $this->markdown('widgets.mail.applicant-mail.briefing-notification');
    }
}
