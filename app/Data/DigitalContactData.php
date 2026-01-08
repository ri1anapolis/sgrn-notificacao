<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DigitalContactData extends Data
{
    public function __construct(
        public int $id,
        public ?string $contact_date,
        public ?string $contact_time,
        public ?string $whatsapp_result,
        public ?string $email_result,
        public ?string $custom_result,
    ) {}
}
