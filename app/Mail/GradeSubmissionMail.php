<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GradeSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($_subject_class)
    {
        $this->subject_class  = $_subject_class;
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
        return $this->from($_user->email, $_user->name)
            ->subject("GRADE SUBMISSION : " . $_section->section_name)
            ->markdown('widgets.mail.grade_submission_mail')
            ->with('_content', $this->subject_class);
        //return $this->markdown('widgets.mail.grade_submission_mail');
    }
}
