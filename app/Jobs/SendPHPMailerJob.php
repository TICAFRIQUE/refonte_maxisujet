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
        $envoye = $mailer->send($this->to, $this->subject, $this->htmlContent, $this->from, $this->fromName);

        if (!$envoye) {
            // Fait échouer le job (visible dans failed_jobs, réessayé selon la config de
            // retry) au lieu de le marquer "traité" silencieusement malgré l'échec d'envoi.
            throw new \RuntimeException("Échec de l'envoi de l'email à " . (is_array($this->to) ? implode(',', $this->to) : $this->to));
        }
    }
}
