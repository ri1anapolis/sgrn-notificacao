<?php

namespace App\Models;

use App\Models\Traits\HashableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory, HashableModel;

    public function notifiedPerson(): BelongsTo
    {
        return $this->belongsTo(NotifiedPerson::class);
    }

    public function diligences(): HasMany
    {
        return $this->hasMany(Diligence::class);
    }
}
