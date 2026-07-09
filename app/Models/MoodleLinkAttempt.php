<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MoodleLinkAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'laravel_user_id',
        'moodle_site_id',
        'lookup_type',
        'lookup_value_hash',
        'lookup_value_masked',
        'moodle_user_id',
        'moodle_email_masked',
        'verification_code_hash',
        'expires_at',
        'consumed_at',
        'attempts_count',
        'status',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'consumed_at' => 'datetime',
        ];
    }

    public function laravelUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laravel_user_id');
    }

    public function moodleSite(): BelongsTo
    {
        return $this->belongsTo(MoodleSite::class);
    }
}
