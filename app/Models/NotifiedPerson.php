<?php

namespace App\Models;

use App\Enums\NotifiedPersonGender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotifiedPerson extends Model
{
    use HasFactory;

    protected $casts = [
        'gender' => NotifiedPersonGender::class,
    ];

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }

    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'address_notified_person');
    }

    public function digitalContacts(): HasMany
    {
        return $this->hasMany(DigitalContact::class);
    }
}
