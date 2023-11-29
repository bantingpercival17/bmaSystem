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
    public function __construct()
    {
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       /*  return $this->from('support@bma.edu.ph', 'BMA ENROLLMENT TEAM')
            ->subject('Enrollment Details')
            ->markdown('widgets.mail.student-enrollment-mail')->with(['data' => $this->data]); */
     
    }
    public function student_forget_password($account,$password) {
        return $this->from('support@bma.edu.ph', 'Baliwag Maritime Academy, Inc.')
        ->subject("PASSWORD RESET REQUEST : " . $account->student_number)
        ->markdown('widgets.mail.student-mail.forget-password')
        ->with(['data' => $account,'password'=>$password]);
    }
}
