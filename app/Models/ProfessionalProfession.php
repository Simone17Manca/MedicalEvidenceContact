<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionalProfession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profession',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}