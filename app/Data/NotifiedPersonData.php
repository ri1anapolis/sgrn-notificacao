<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class NotifiedPersonData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        #[DataCollectionOf(AddressData::class)]
        public DataCollection $addresses,
    ) {}
}
