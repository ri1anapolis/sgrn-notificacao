<?php

namespace App\Data;

use App\Enums\NotifiedPersonGender;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class NotifiedPersonData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $document,
        public ?string $email,
        public ?string $phone,
        public NotifiedPersonGender $gender,
    ) {}
}
