<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionalProfileItem extends Model
{
    use HasFactory;

    public const TYPE_WORK_EXPERIENCE = 'work_experience';
    public const TYPE_EDUCATION = 'education';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'duration',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}