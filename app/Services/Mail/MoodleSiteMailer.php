<?php

namespace App\Services\Mail;

use App\Models\MoodleSite;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;
use RuntimeException;

class MoodleSiteMailer
{
    /**
     * @throws PHPMailerException
     */
    public function sendMoodleAccountLinkCode(string $recipientEmail, string $code, MoodleSite $moodleSite): void
    {
        $this->assertSmtpConfigurationIsComplete($moodleSite);

        $mail = new PHPMailer(true);
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->isSMTP();
        $mail->Host = (string) $moodleSite->mail_host;
        $mail->Port = (int) $moodleSite->mail_port;
        $mail->SMTPAuth = true;
        $mail->Username = (string) $moodleSite->mail_username;
        $mail->Password = (string) $moodleSite->mail_password_encrypted;

        if ($moodleSite->mail_encryption === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($moodleSite->mail_encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }

        $mail->setFrom((string) $moodleSite->mail_from_address, $moodleSite->mail_from_name ?: $moodleSite->name);
        $mail->addAddress($recipientEmail);
        $mail->Subject = 'Codice per collegare il tuo account Moodle';
        $mail->isHTML(true);
        $mail->Body = view('emails.moodle-account-link-code', [
            'code' => $code,
            'moodleSite' => $moodleSite,
        ])->render();
        $mail->AltBody = "Hai richiesto di collegare questo account Moodle al portale Medical Evidence Contact.\n\n".
            "Il tuo codice e: {$code}\n\n".
            "Il codice scade tra 15 minuti.\n\n".
            "Se non hai richiesto tu questa operazione, ignora questa email.";

        $mail->send();
    }

    private function assertSmtpConfigurationIsComplete(MoodleSite $moodleSite): void
    {
        if (($moodleSite->mail_mailer ?: 'smtp') !== 'smtp') {
            throw new RuntimeException("Il sito Moodle {$moodleSite->id} usa un mailer non supportato da PHPMailer.");
        }

        $requiredFields = [
            'mail_host' => $moodleSite->mail_host,
            'mail_port' => $moodleSite->mail_port,
            'mail_from_address' => $moodleSite->mail_from_address,
            'mail_username' => $moodleSite->mail_username,
            'mail_password_encrypted' => $moodleSite->mail_password_encrypted,
        ];

        foreach ($requiredFields as $field => $value) {
            if (blank($value)) {
                throw new RuntimeException("Configurazione email incompleta per il sito Moodle {$moodleSite->id}: campo {$field} mancante.");
            }
        }

        if (! in_array($moodleSite->mail_encryption, [null, 'tls', 'ssl'], true)) {
            throw new RuntimeException("Configurazione email non valida per il sito Moodle {$moodleSite->id}: mail_encryption deve essere tls, ssl o null.");
        }
    }
}