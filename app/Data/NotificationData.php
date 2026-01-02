<?php

namespace App\Data;

use App\Models\Notification;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class NotificationData extends Data
{
    private const NOTIFIABLE_TYPE_MAP = [
        'App\Models\AlienationRealEstate' => AlienationRealEstateData::class,
        'App\Models\AlienationMovableProperty' => AlienationMovablePropertyData::class,
        'App\Models\PurchaseAndSaleSubdivision' => PurchaseAndSaleSubdivisionData::class,
        'App\Models\PurchaseAndSaleIncorporation' => PurchaseAndSaleIncorporationData::class,
        'App\Models\RetificationArea' => RetificationAreaData::class,
        'App\Models\Adjudication' => AdjudicationData::class,
        'App\Models\AdversePossession' => AdversePossessionData::class,
        'App\Models\Other' => OtherData::class,
    ];

    public function __construct(
        public int $id,
        public string $protocol,
        public ?Data $notifiable,
        public ?string $notifiable_type,

        #[DataCollectionOf(NotifiedPersonData::class)]
        public ?DataCollection $notified_people,
        #[DataCollectionOf(AddressData::class)]
        public ?DataCollection $addresses,

        public ?PublicNoticeData $public_notice,
    ) {}

    public static function fromModel(Notification $notification): self
    {
        $notification->loadMissing(['notifiedPeople', 'addresses', 'notifiable', 'publicNotice.publications']);

        $notifiableData = null;

        if ($notification->notifiable) {
            $typeClass = self::NOTIFIABLE_TYPE_MAP[$notification->notifiable_type] ?? null;

            if ($typeClass) {
                $notifiableData = $typeClass::from($notification->notifiable);
            }
        }

        $notifiedPeopleData = $notification->notifiedPeople->map(
            fn ($person) => NotifiedPersonData::fromModel($person, $notification->id)
        );
        $notifiedPeopleCollection = new DataCollection(NotifiedPersonData::class, $notifiedPeopleData->toArray());
        $addressesCollection = new DataCollection(AddressData::class, $notification->addresses);

        $publicNoticeData = $notification->publicNotice ? PublicNoticeData::fromModel($notification->publicNotice) : null;

        return new self(
            id: $notification->id,
            protocol: $notification->protocol,
            notifiable: $notifiableData,
            notifiable_type: $notification->notifiable_type,

            notified_people: $notifiedPeopleCollection,
            addresses: $addressesCollection,
            public_notice: $publicNoticeData,
        );
    }
}
