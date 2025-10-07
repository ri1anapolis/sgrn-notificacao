<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AdversePossessionData extends Data
{
    public function __construct(
        public int $id,
        public ?int $office,
        public string $adverse_possession_property_registration,
        public string $adverse_possession_property_identification,
        public string $adverse_possession_property_registry_office,
    ) {}
}
