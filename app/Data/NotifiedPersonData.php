<?php

namespace App\Data;

use App\Enums\NotifiedPersonGender;
use App\Models\NotifiedPerson;
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
        public ?DigitalContactData $digital_contact = null,
    ) {}

    public static function fromModel(NotifiedPerson $notifiedPerson, int $notificationId): self
    {
        $digitalContact = $notifiedPerson->digitalContacts()
            ->where('notification_id', $notificationId)
            ->first();

        return new self(
            id: $notifiedPerson->id,
            name: $notifiedPerson->name,
            document: $notifiedPerson->document,
            email: $notifiedPerson->email,
            phone: $notifiedPerson->phone,
            gender: $notifiedPerson->gender,
            digital_contact: $digitalContact ? DigitalContactData::from($digitalContact) : null,
        );
    }
}
