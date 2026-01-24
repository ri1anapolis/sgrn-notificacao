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
        public ?string $original_description = null,
        public bool $active = true,
        public bool $is_custom = false,
        public bool $is_modified = false,
    ) {
        $this->is_modified = $this->original_description !== null
            && $this->description !== $this->original_description;
    }
}
