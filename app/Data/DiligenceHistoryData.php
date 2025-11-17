<?php

namespace App\Data;

use DateTime;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DiligenceHistoryData extends Data
{
    public function __construct(
        public int $id,
        public DateTime $created_at,
        public ?int $user_id,
        public ?UserData $user,
        public ?int $old_diligence_result_id,
        public ?DiligenceResultData $oldResult,
        public ?int $new_diligence_result_id,
        public ?DiligenceResultData $newResult,
        public ?string $old_observations,
        public ?string $new_observations,
    ) {}
}
