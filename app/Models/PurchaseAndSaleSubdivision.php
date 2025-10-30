<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class PurchaseAndSaleSubdivision extends Model
{
    use HasFactory;

    protected $casts = [
        'contract_date' => 'datetime',
        'debt_position_date' => 'datetime',
        'grace_period' => 'boolean',
    ];

    public function notification(): MorphOne
    {
        return $this->morphOne(Notification::class, 'notifiable');
    }
}
