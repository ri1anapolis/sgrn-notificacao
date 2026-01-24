<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiligenceResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'code',
        'description',
        'original_description',
        'order',
        'active',
        'is_custom',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_custom' => 'boolean',
    ];

    /**
     * Codes that indicate a successful diligence (debtor present/notified).
     */
    public const SUCCESS_CODES = [
        'devedor_presente_assinou',
        'procurador_presente_assinou',
        'recusa_assinar_fica_copia',
        'recusa_assinar_sem_copia',
        'recusa_assinar_extremamente_nervoso',
        'impossibilidade_fisica',
        'analfabeto',
        'compareceu_cartorio',
        'impossibilidade_mental',
    ];

    /**
     * Scope for active options.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Check if this result indicates a successful diligence.
     */
    public function isSuccess(): bool
    {
        return in_array($this->code, self::SUCCESS_CODES);
    }
}
