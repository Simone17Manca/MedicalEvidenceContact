<?php

namespace Database\Seeders;

use App\Models\MoodleSite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class MoodleSiteSeeder extends Seeder
{
    private const CERTIFICATE_SYNC_DRIVERS = [
        'native_mod_customcert',
        'local_laravelcertsync',
        'disabled',
    ];

    /**
     * Seed one Moodle site from interactive input.
     */
    public function run(): void
    {
        $name = $this->askRequired('Nome sito Moodle');
        $baseUrl = $this->normalizeBaseUrl($this->askRequired('Base URL Moodle'));
        $token = $this->askSecretRequired('Token tecnico Moodle');
        $driver = $this->choice(
            'Driver sync attestati',
            self::CERTIFICATE_SYNC_DRIVERS,
            array_search('disabled', self::CERTIFICATE_SYNC_DRIVERS, true)
        );
        $enabled = $this->command?->confirm('Abilitare subito questo sito Moodle?', true) ?? true;

        $this->validatePayload($name, $baseUrl, $token, $driver);

        $moodleSite = MoodleSite::updateOrCreate(
            ['base_url' => $baseUrl],
            [
                'name' => $name,
                'api_token_encrypted' => $token,
                'certificate_sync_driver' => $driver,
                'enabled' => $enabled,
            ],
        );

        $this->command?->info("Sito Moodle salvato nel DB con ID {$moodleSite->id}.");
    }

    private function askRequired(string $question): string
    {
        do {
            $answer = trim((string) $this->command?->ask($question));
        } while ($answer === '');

        return $answer;
    }

    private function askSecretRequired(string $question): string
    {
        do {
            $answer = trim((string) ($this->command?->secret($question) ?? ''));
        } while ($answer === '');

        return $answer;
    }

    private function choice(string $question, array $choices, int|string|null $default = null): string
    {
        return (string) $this->command?->choice($question, $choices, $default);
    }

    private function normalizeBaseUrl(string $value): string
    {
        $value = trim($value);

        if (Str::contains($value, '/webservice/rest/server.php')) {
            $parts = parse_url($value);
            $scheme = $parts['scheme'] ?? null;
            $host = $parts['host'] ?? null;
            $port = isset($parts['port']) ? ':'.$parts['port'] : '';

            if ($scheme && $host) {
                return "{$scheme}://{$host}{$port}";
            }
        }

        return rtrim($value, '/');
    }

    private function validatePayload(string $name, string $baseUrl, string $token, string $driver): void
    {
        Validator::make([
            'name' => $name,
            'base_url' => $baseUrl,
            'api_token' => $token,
            'certificate_sync_driver' => $driver,
        ], [
            'name' => ['required', 'string', 'max:180'],
            'base_url' => ['required', 'url', 'max:255'],
            'api_token' => ['required', 'string', 'max:1000'],
            'certificate_sync_driver' => ['required', Rule::in(self::CERTIFICATE_SYNC_DRIVERS)],
        ])->validate();
    }
}
