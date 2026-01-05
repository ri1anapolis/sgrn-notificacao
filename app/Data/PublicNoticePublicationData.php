<?php

namespace App\Data;

use DateTime;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PublicNoticePublicationData extends Data
{
    public function __construct(
        public int $id,
        public int $publication_order,
        public ?string $edition,
        public ?string $notice_number,
        public ?DateTime $publication_date,
    ) {}
}
