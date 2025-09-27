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
        public int $id,
        public int $visit_number,
        public ?string $observations,
        public DateTime $date,
        public ?int $diligence_result_id,
        public ?DiligenceResultData $diligence_result,
        public ?int $user_id,
        public ?UserData $user,
    ) {}
}
