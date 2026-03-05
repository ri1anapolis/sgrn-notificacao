<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class BrlCurrencyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value ? (float) $value : null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_null($value)) {
            return null;
        }

        if (is_numeric($value)) {
            return number_format((float) $value, 2, '.', '');
        }

        $cleanValue = str_replace(['R$', ' ', '.'], '', $value);

        $cleanValue = str_replace(',', '.', $cleanValue);

        return number_format((float) $cleanValue, 2, '.', '');
    }
}
