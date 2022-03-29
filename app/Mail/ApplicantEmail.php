<?php

namespace App\Mail;

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
    // Document Attachment Notification Email
    public function document_notificaiton($_applicant)
    {
        return $this->from(Auth::user()->email, 'Baliwag Maritime Academy, Inc.')
            ->subject("DOCUMENT ATTACHMENT : " . $_applicant->applicant_number)
            ->markdown('widgets.mail.applicant-mail.document-notification');
        //->with('_content', $this->subject_class);
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
}
