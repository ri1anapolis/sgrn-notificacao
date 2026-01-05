<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicNoticePublication extends Model
{
    protected $fillable = [
        'public_notice_id',
        'publication_order',
        'edition',
        'notice_number',
        'publication_date',
    ];

    protected $casts = [
        'publication_order' => 'integer',
        'publication_date' => 'date',
    ];

    public function publicNotice(): BelongsTo
    {
        return $this->belongsTo(PublicNotice::class);
    }
}
