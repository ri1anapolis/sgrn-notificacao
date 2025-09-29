<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class NotifiedPersonData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $document,
    ) {}
}
