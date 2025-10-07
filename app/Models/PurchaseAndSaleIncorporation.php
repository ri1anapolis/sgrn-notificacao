<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class PurchaseAndSaleIncorporation extends Model
{
    use HasFactory;

    public function notification(): MorphOne
    {
        return $this->morphOne(Notification::class, 'notifiable');
    }
}
