<?php

namespace Tests\Unit;

use App\Models\MoodleSite;
use App\Services\Mail\MoodleSiteMailer;
use RuntimeException;
use Tests\TestCase;

class MoodleSiteMailerTest extends TestCase
{
    public function test_it_does_not_fall_back_to_global_mail_configuration_when_site_smtp_is_incomplete(): void
    {
        $site = new MoodleSite([
            'id' => 10,
            'name' => 'Formazione OSS',
            'mail_mailer' => 'smtp',
            'mail_from_address' => 'formazioneoss@mei.it',
            'mail_username' => 'formazioneoss@mei.it',
            'mail_password_encrypted' => 'secret',
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('mail_host');

        app(MoodleSiteMailer::class)->sendMoodleAccountLinkCode('utente@example.test', '123456', $site);
    }

    public function test_it_accepts_only_smtp_site_mailer_for_phpmailer_delivery(): void
    {
        $site = new MoodleSite([
            'id' => 11,
            'name' => 'Formazione OSS',
            'mail_mailer' => 'array',
            'mail_host' => 'mail.example.test',
            'mail_port' => 587,
            'mail_from_address' => 'formazioneoss@mei.it',
            'mail_username' => 'formazioneoss@mei.it',
            'mail_password_encrypted' => 'secret',
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('mailer non supportato');

        app(MoodleSiteMailer::class)->sendMoodleAccountLinkCode('utente@example.test', '123456', $site);
    }
}