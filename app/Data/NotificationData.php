<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class NotificationData extends Data
{
    public function __construct(
        public int $id,
        public string $protocol,
        public ?Data $notifiable,
        public ?string $notifiable_type,
        #[DataCollectionOf(NotifiedPersonData::class)]
        public ?DataCollection $notified_people,
        #[DataCollectionOf(AddressData::class)]
        public ?DataCollection $addresses,
    ) {}
}
