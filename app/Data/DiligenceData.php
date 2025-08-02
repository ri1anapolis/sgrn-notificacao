<?php

namespace App\Data;

use App\Enums\DiligenceResult;
use DateTime;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DiligenceData extends Data
{
    public function __construct(
        public string $hash,
        public int $visitNumber,
        public DiligenceResult $diligenceResult,
        public string $observations,
        public DateTime $date,
    ) {}
}
