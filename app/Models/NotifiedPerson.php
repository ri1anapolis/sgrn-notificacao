<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifiedPerson extends Model
{
    use HasFactory;

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }

    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'address_notified_person');
    }
}
