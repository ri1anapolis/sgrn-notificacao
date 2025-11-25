<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class RetificationAreaData extends Data
{
    public function __construct(
        public int $id,
        public ?int $office,
        public ?string $rectifying_property_registration,
        public ?string $rectifying_property_identification,
        public ?string $rectifying_property_registry_office,
    ) {}
}
