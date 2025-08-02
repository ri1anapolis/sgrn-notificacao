<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AddressData extends Data
{
    public function __construct(
        public string $hash,
        public string $street,
        public string $complement,
        public string $city,
        public string $zipCode,
    ) {}
}
