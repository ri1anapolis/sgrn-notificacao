<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DiligenceResultData extends Data
{
    public function __construct(
        public int $id,
        public string $group,
        public string $code,
        public string $description,
    ) {}
}
