<?php

namespace App\Models;

use App\Enums\NotificationNature;
use App\Enums\NotificationStatus;
use App\Models\Traits\HashableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    use HasFactory, HashableModel;

    protected $casts = [
        'status' => NotificationStatus::class,
        'nature' => NotificationNature::class,
    ];

    public function notifiedPeople(): HasMany
    {
        return $this->hasMany(NotifiedPerson::class);
    }

    public function getRouteKeyName(): string
    {
        return 'protocol';
    }
}
