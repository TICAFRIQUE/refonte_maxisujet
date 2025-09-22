<?php

namespace App\Jobs;

use App\Services\PHPMailerService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPHPMailerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to, $subject, $htmlContent, $from, $fromName;

    /**
     * Create a new job instance.
     */
    public function __construct($to, $subject, $htmlContent, $from = null, $fromName = null)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->htmlContent = $htmlContent;
        $this->from = $from;
        $this->fromName = $fromName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mailer = new PHPMailerService();
        $mailer->send($this->to, $this->subject, $this->htmlContent, $this->from, $this->fromName);
    }
}
