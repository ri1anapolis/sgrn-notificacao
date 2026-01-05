<?php

namespace App\Data;

use App\Models\PublicNotice;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PublicNoticeData extends Data
{
    public function __construct(
        public int $id,
        public string $publication_organ,
        public ?int $days_between_email_and_notice,

        #[DataCollectionOf(PublicNoticePublicationData::class)]
        public ?DataCollection $publications,
    ) {}

    public static function fromModel(PublicNotice $publicNotice): self
    {
        $publicNotice->loadMissing('publications');

        return new self(
            id: $publicNotice->id,
            publication_organ: $publicNotice->publication_organ,
            days_between_email_and_notice: $publicNotice->days_between_email_and_notice,
            publications: new DataCollection(
                PublicNoticePublicationData::class,
                $publicNotice->publications
            ),
        );
    }
}
