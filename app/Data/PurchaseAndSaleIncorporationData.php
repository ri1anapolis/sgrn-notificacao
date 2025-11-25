<?php

namespace App\Data;

use DateTime;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PurchaseAndSaleIncorporationData extends Data
{
    public function __construct(
        public int $id,
        public string $creditor,
        public ?int $office,
        public ?string $committed_property_registration,
        public ?string $committed_property_identification,
        public ?string $contract_registration_act,
        public ?string $emoluments_intimation,
        public ?string $contract_number,
        public ?DateTime $contract_date,
        public ?int $total_amount_debt,
        public ?DateTime $debt_position_date,
        public ?string $default_period,
        public bool $grace_period,
        public ?string $contractual_clause,
        public ?string $real_estate_registry_location,
    ) {}
}
