<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MoodleSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'base_url',
        'api_token_encrypted',
        'mail_from_address',
        'mail_from_name',
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password_encrypted',
        'mail_encryption',
        'certificate_sync_driver',
        'enabled',
        'last_user_sync_at',
        'last_certificate_sync_at',
    ];

    protected function casts(): array
    {
        return [
            'api_token_encrypted' => 'encrypted',
            'mail_password_encrypted' => 'encrypted',
            'mail_port' => 'integer',
            'enabled' => 'boolean',
            'last_user_sync_at' => 'datetime',
            'last_certificate_sync_at' => 'datetime',
        ];
    }

    public function userLinks(): HasMany
    {
        return $this->hasMany(MoodleUserLink::class);
    }

    public function linkAttempts(): HasMany
    {
        return $this->hasMany(MoodleLinkAttempt::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(UserCertificate::class);
    }
}