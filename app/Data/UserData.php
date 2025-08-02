<?php

namespace App\Data;

use App\Enums\UserRole;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UserData extends Data
{
    public function __construct(
        public string $hash,
        public string $name,
        public string $email,
        public UserRole $role,
    ) {}
}
