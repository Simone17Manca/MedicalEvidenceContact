<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'laravel_user_id',
        'moodle_site_id',
        'moodle_user_id',
        'moodle_customcert_id',
        'moodle_customcert_issue_id',
        'moodle_course_module_id',
        'moodle_context_id',
        'course_id',
        'course_fullname',
        'course_shortname',
        'certificate_name',
        'template_id',
        'template_name',
        'certificate_code',
        'issued_at',
        'expires_at',
        'download_url',
        'verification_url',
        'verification_is_public',
        'pdf_stored_path',
        'raw_payload_json',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'expires_at' => 'datetime',
            'verification_is_public' => 'boolean',
            'raw_payload_json' => 'array',
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
