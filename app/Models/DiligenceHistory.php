<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiligenceHistory extends Model
{
    public function diligence(): BelongsTo
    {
        return $this->belongsTo(Diligence::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function oldResult(): BelongsTo
    {
        return $this->belongsTo(DiligenceResult::class, 'old_diligence_result_id');
    }

    public function newResult(): BelongsTo
    {
        return $this->belongsTo(DiligenceResult::class, 'new_diligence_result_id');
    }
}
