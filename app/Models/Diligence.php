<?php

namespace App\Models;

use App\Enums\DiligenceResult;
use App\Models\Traits\HashableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diligence extends Model
{
    use HasFactory, HashableModel;

    protected $casts = [
        'date' => 'datetime',
        'diligence_result' => DiligenceResult::class,
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
