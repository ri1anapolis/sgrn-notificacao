<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }

    public function diligences(): HasMany
    {
        return $this->hasMany(Diligence::class);
    }

    public function notifiedPeople()
    {
        return $this->belongsToMany(NotifiedPerson::class, 'address_notified_person');
    }
}
