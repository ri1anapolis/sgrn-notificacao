<?php

namespace App\Models;

use App\Models\Traits\HashableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotifiedPerson extends Model
{
    use HasFactory, HashableModel;

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
