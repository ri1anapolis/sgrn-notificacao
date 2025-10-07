<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AlienationRealEstateData extends Data
{
    public function __construct(
        public int $id,
        public string $creditor,
        public ?int $office,
        public ?string $guarantee_property_registration,
        public ?string $guarantee_property_address,
        public ?string $contract_registration_act,
        public ?string $emoluments_intimation,
        public ?string $contract_number,
        public ?Carbon $contract_date,
        public ?int $total_amount_debt,
        public ?Carbon $debt_position_date,
        public ?string $default_period,
        public bool $grace_period,
        public ?string $contractual_clause,
        public ?string $real_estate_registry_location,
    ) {}
}
