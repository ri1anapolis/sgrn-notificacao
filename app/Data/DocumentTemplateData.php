<?php

namespace App\Data;

use App\Models\DocumentTemplate;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DocumentTemplateData extends Data
{
    public function __construct(
        public int $id,
        public string $slug,
        public string $title,
        public ?string $description,
        public bool $is_customized,
        public ?string $updated_at,
        public ?string $updated_by,
    ) {}

    public static function fromModel(DocumentTemplate $template): self
    {
        return new self(
            id: $template->id,
            slug: $template->slug,
            title: $template->title,
            description: $template->description,
            is_customized: $template->isCustomized(),
            updated_at: $template->updated_at?->format('d/m/Y H:i'),
            updated_by: $template->updatedByUser?->name,
        );
    }
}
