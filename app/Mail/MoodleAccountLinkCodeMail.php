<?php

namespace App\Mail;

use App\Models\MoodleSite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MoodleAccountLinkCodeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $code,
        public readonly MoodleSite $moodleSite
    ) {
    }

    public function build(): self
    {
        $mail = $this
            ->subject('Codice per collegare il tuo account Moodle')
            ->view('emails.moodle-account-link-code');

        if (filled($this->moodleSite->mail_from_address)) {
            $mail->from(
                $this->moodleSite->mail_from_address,
                $this->moodleSite->mail_from_name ?: $this->moodleSite->name
            );
        }

        return $mail;
    }
}