<?php

namespace App\Services;

use App\Models\DocumentTemplate;

class TemplateResolver
{
    public function resolve(string $slug): string
    {
        $template = DocumentTemplate::where('slug', $slug)->first();

        if ($template) {
            return $template->getActivePath();
        }

        $customPath = storage_path("app/templates/custom/{$slug}.docx");
        if (file_exists($customPath)) {
            return $customPath;
        }

        $resourcePath = resource_path("templates/default/{$slug}.docx");
        if (file_exists($resourcePath)) {
            return $resourcePath;
        }

        return storage_path("app/templates/default/{$slug}.docx");
    }
}
