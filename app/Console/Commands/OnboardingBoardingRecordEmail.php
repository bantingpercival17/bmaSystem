<?php

namespace App\Console\Commands;

use App\Mail\ExecutiveReportMail;
use App\Models\AcademicYear;
use App\Models\Section;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class OnboardingBoardingRecordEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:onboard_report_email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $_academic = AcademicYear::where('is_active', true)->first();
        $_deck = Section::where('course_id', 2)
            ->where('academic_id', $_academic->id)
            ->where('is_removed', false)->get();
        $_engine = Section::where('course_id', 1)
            ->where('academic_id', $_academic->id)
            ->where('is_removed', false)->get();
        // Additional Data
        $_time_arrival = array(
            array('year_level' => 4, 'time_arrival' => 1730),
            array('year_level' => 3, 'time_arrival' => 1800),
            array('year_level' => 2, 'time_arrival' => 1830),
            array('year_level' => 1, 'time_arrival' => 1900)
        );
        $_absent_on_deck = Section::where('course_id', 2)
            ->where('is_removed', false)
            ->where('academic_id', $_academic->id)->orderBy('year_level', 'desc')->get();
        $_absent_on_engine = Section::where('course_id', 1)
            ->where('is_removed', false)
            ->where('academic_id', $_academic->id)->orderBy('year_level', 'desc')->get();
        $mail = new ExecutiveReportMail($_deck, $_engine, $_time_arrival, $_absent_on_deck, $_absent_on_engine);
        Mail::to('p.banting@bma.edu.ph')->send($mail); // Testing Email
        /*   $other_email = ['qmr@bma.edu.ph', 'ict@bma.edu.ph', 'exo@bma.edu.ph'];
        Mail::to('report@bma.edu.ph')->bcc($other_email)->send($mail); // Offical Emails */
        return "Sent";
    }
}
