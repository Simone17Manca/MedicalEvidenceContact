<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MoodleUserLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'laravel_user_id',
        'moodle_site_id',
        'moodle_user_id',
        'moodle_idnumber',
        'moodle_username',
        'moodle_email',
        'linked_via',
        'linked_at',
        'last_verified_at',
        'last_certificate_sync_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'linked_at' => 'datetime',
            'last_verified_at' => 'datetime',
            'last_certificate_sync_at' => 'datetime',
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

    public function certificates(): HasMany
    {
        return $this->hasMany(UserCertificate::class, 'laravel_user_id', 'laravel_user_id')
            ->where('moodle_site_id', $this->moodle_site_id)
            ->where('moodle_user_id', $this->moodle_user_id);
    }
}
