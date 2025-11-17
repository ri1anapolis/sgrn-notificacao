<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diligence extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function diligenceResult(): BelongsTo
    {
        return $this->belongsTo(DiligenceResult::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(DiligenceHistory::class)->orderBy('created_at', 'desc');
    }
}
