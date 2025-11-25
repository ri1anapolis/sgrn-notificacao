<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AdjudicationData extends Data
{
    public function __construct(
        public int $id,
        public ?int $office,
        public ?string $adjudicated_property_registration,
        public ?string $adjudicated_property_identification,
        public ?string $adjudicated_property_registry_office,
    ) {}
}
