<?php

namespace App\Data;

use App\Enums\NotificationNature;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class NotificationData extends Data
{
    public function __construct(
        public string $hash,
        public string $protocol,
        public NotificationNature $nature,
        #[DataCollectionOf(NotificationPersonData::class)]
        public DataCollection $notified_people,
    ) {}
}
