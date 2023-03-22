<?php

namespace App\Console\Commands;

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
        $data = [
            'title' => 'Test Email',
            'body' => 'This is a test email sent from Laravel.'
        ];
    
        Mail::send('p.banting@bma.edu.ph', $data, function($message) {
            $message->to('support@bma.edu.ph', 'Recipient Name')
                    ->subject('Test Email');
        });
    }
}
