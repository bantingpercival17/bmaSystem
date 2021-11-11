<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GradeVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($_subject_class, $_status)
    {
        $this->subject_class  = $_subject_class;
        $this->status = $_status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $_user = $this->subject_class->staff->user;
        $_section = $this->subject_class->section;
        return $this->from($_user->email, "DEPARMENT HEAD")
            ->subject("GRADE VERIFICATION : " . $_section->section_name . " : " . strtoupper($this->status))
            ->markdown('widgets.mail.grade_verification__mail')
            ->with('_content', $this->subject_class);
    }
}
