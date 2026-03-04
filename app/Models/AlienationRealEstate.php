<?php

namespace App\Models;

use App\Casts\BrlCurrencyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class AlienationRealEstate extends Model
{
    use HasFactory;

    protected $casts = [
        'contract_date' => 'datetime',
        'debt_position_date' => 'datetime',
        'grace_period' => 'boolean',
        'total_amount_debt' => BrlCurrencyCast::class,
        'emoluments_intimation' => BrlCurrencyCast::class,
    ];

    public function notification(): MorphOne
    {
        return $this->morphOne(Notification::class, 'notifiable');
    }
}
