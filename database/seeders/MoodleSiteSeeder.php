<?php

namespace Database\Seeders;

use App\Models\MoodleSite;
use Illuminate\Database\Seeder;

class MoodleSiteSeeder extends Seeder
{
    public function run(): void
    {
        MoodleSite::updateOrCreate(
            [
                'base_url' => 'https://corso-formazioneoss.mei.it',
            ],
            [
                'name' => 'Corso Formazione OSS',

                // Laravel cifra automaticamente grazie ai cast del model
                'api_token_encrypted' => '0e94fd554eecb8153363738c26a2ed4a',

                'mail_from_address' => 'formazioneoss@mei.it',
                'mail_from_name'    => 'Formazione OSS',

                'mail_mailer'       => 'smtp',
                'mail_host'         => 'mail.metmi.it',
                'mail_port'         => 587,
                'mail_username'     => 'formazioneoss@mei.it',

                // Laravel cifra automaticamente
                'mail_password_encrypted' => '$rzTTP%25',

                'mail_encryption' => 'tls',

                'certificate_sync_driver' => 'disabled',
                'enabled' => true,
            ]
        );
    }
}
