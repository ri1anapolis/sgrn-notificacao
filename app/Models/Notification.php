<?php

namespace App\Models;

use App\Enums\NotificationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => NotificationStatus::class,
        'is_closed' => 'boolean',
    ];

    public function notifiedPeople(): HasMany
    {
        return $this->hasMany(NotifiedPerson::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function publicNotice(): HasOne
    {
        return $this->hasOne(PublicNotice::class);
    }

    public function digitalContacts(): HasMany
    {
        return $this->hasMany(DigitalContact::class);
    }

    public function getRouteKeyName(): string
    {
        return 'protocol';
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
