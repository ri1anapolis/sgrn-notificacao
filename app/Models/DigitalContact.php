<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalContact extends Model
{
    protected $fillable = [
        'notification_id',
        'notified_person_id',
        'user_id',
        'contact_date',
        'contact_time',
        'whatsapp_result',
        'email_result',
        'custom_result',
    ];

    protected $casts = [
        'contact_date' => 'date',
    ];

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }

    public function notifiedPerson(): BelongsTo
    {
        return $this->belongsTo(NotifiedPerson::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
