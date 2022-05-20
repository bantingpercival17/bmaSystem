<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($_ticket)
    {
        $this->ticket = $_ticket;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@bma.edu.ph', "BMA TECHNICAL ISSUE")
            ->subject("TICKET NUMBER: " . $this->ticket->ticket_number)
            ->markdown('widgets.mail.ticket.ticket_mail')->with(['data' => $this->ticket]);
    }
}
