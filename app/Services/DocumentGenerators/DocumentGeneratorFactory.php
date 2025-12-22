<?php

namespace App\Services\DocumentGenerators;

use InvalidArgumentException;

class DocumentGeneratorFactory
{
    protected array $generators = [
        'alienation_real_estate' => AlienationRealEstateDocGenerator::class,
        'retification_area' => RectificationNotificationDocGenerator::class,
    ];

    public function resolve(string $notifiableType): DocumentGeneratorInterface
    {
        $key = $this->normalizeType($notifiableType);

        if (! isset($this->generators[$key])) {
            throw new InvalidArgumentException(
                "Gerador de documento não encontrado para a natureza: {$notifiableType}"
            );
        }

        return app($this->generators[$key]);
    }

    private function normalizeType(string $type): string
    {
        $className = class_basename($type);

        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className));
    }
}
