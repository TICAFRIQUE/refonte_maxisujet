<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerService
{
    public function send($to, $subject, $htmlContent, $from = null, $fromName = null)
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'mail.maxisujets.net');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME', 'info@maxisujets.net');
            $mail->Password   = env('SMTP_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'ssl');
            $mail->Port       = env('MAIL_PORT', 465);
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($from ?? env('MAIL_FROM_ADDRESS', 'info@maxisujets.net'), $fromName ?? env('MAIL_FROM_NAME', 'MaxiSujets'));
            if (is_array($to)) {
                foreach ($to as $address) {
                    $mail->addAddress($address);
                }
            } else {
                $mail->addAddress($to);
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlContent;

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Tu peux logger l'erreur ici si besoin
            return false;

        }
    }
}