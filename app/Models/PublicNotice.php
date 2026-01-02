<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PublicNotice extends Model
{
    protected $fillable = [
        'notification_id',
        'publication_organ',
        'days_between_email_and_notice',
    ];

    protected $casts = [
        'days_between_email_and_notice' => 'integer',
    ];

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(PublicNoticePublication::class);
    }
}
