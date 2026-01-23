<?php

namespace App\Services\DocumentGenerators;

use App\Services\TemplateResolver;

class CertificateStandardDocGenerator extends BaseCertificateDocGenerator
{
    protected function getTemplatePath(): string
    {
        return app(TemplateResolver::class)->resolve('certificate_standard');
    }

    /**
     * Uses default fillAdditionalData() from base class (does nothing)
     * Standard certificate doesn't need edital data
     */
}
