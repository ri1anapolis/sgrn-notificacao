<?php

namespace App\Models\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait HashableModel
{
    public function getHashAttribute(): string
    {
        return Hashids::encode($this->getKey());
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if ($field === 'hash') {
            $decoded = Hashids::decode($value);
            if (count($decoded) === 0) {
                abort(404);
            }

            return $this->whereKey($decoded[0])->firstOrFail();
        }

        return parent::resolveRouteBinding($value, $field);
    }
}
