<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;

class ExecutiveReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($deck, $engine, $time, $deck_absent, $engine_absent)
    {
        $this->deck = $deck;
        $this->engine = $engine;
        $this->time = $time;
        $this->deck_absent = $deck_absent;
        $this->engine_absent = $engine_absent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $_layout_attendance =  'widgets.report.executive.onboarding-absent-report';
        $_layout_absent =  'widgets.report.executive.onboarding-report';
        // Deck Department
        $deckPdf = $this->store_pdf($this->deck, 'BSMT-ONBOARDING MASTERLIST REPORT', $_layout_attendance);
        // Engine Department
        $enginePdf = $this->store_pdf($this->engine, 'BSME-ONBOARDING MASTERLIST REPORT', $_layout_attendance);

        // Deck Absent Cadet
        $deck_absent = $this->store_pdf($this->deck_absent, 'BSMT-LIST OF MIDSHIPMAN ABSENT', $_layout_absent);

        // Deck Absent Cadet
        $engine_absent = $this->store_pdf($this->engine_absent, 'BSME-LIST OF MIDSHIPMAN ABSENT', $_layout_absent);
        // Send an Email
        return $this->from('support@bma.edu.ph', 'BMA SYSTEM EMAIL')
            ->subject(
                "Mishipman Attendance Monitoring Report " . date('Y-m-d')
            )
            ->attachData($deckPdf->output(), 'BSMT-ONBOARDING MASTERLIST REPORT')
            ->attachData($enginePdf->output(), 'BSME-ONBOARDING MASTERLIST REPORT')
            ->attachData($deck_absent->output(), 'BSMT-LIST OF MIDSHIPMAN ABSENT')
            //->attachData($engine_absent->output(), 'BSME-LIST OF MIDSHIPMAN ABSENT')
            ->markdown('emails.executive.report');
    }
    /*   public function store_pdf($_sections, $filename)
    {
        // Set the Layout for the report
        $_layout =  'widgets.report.executive.onboarding-report';
        $_time_arrival = $this->time;
        $pdf = PDF::loadView($_layout, compact('_sections', '_time_arrival')); // Set the PDF View
        $pdf->setPaper([0, 0, 612.00, 1008.00], 'portrait'); // Set the Paper sizw
        $file_name = 'executive/report/' . $filename . ' - ' . date('Ymd') . '.pdf'; // File name
        Storage::disk('public')->put($file_name, $pdf->output()); // Store to Local folder
        return $pdf;
    } */
    public function store_pdf($_sections, $filename, $_layout)
    {
        $_time_arrival = $this->time;
        $pdf = PDF::loadView($_layout, compact('_sections', '_time_arrival')); // Set the PDF View
        $pdf->setPaper([0, 0, 612.00, 1008.00], 'portrait'); // Set the Paper sizw
        $file_name = 'executive/report/' . $filename . ' - ' . date('Ymd') . '.pdf'; // File name
        Storage::disk('public')->put($file_name, $pdf->output()); // Store to Local folder
        return $pdf;
    }
    public function store_absent_pdf($_sections, $filename)
    {
        // Set the Layout for the report
        $_layout =  'widgets.report.executive.onboarding-absent-report';
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_sections'));
        // Set the Filename of report
        $pdf->setPaper([0, 0, 612.00, 1008.00], 'portrait'); // Set the Paper sizw
        $file_name = 'executive/report/absent/' . $filename . ' - ' . date('Ymd') . '.pdf'; // File name
        Storage::disk('public')->put($file_name, $pdf->output()); // Store to Local folder
        return $pdf;
    }
}
