<?php

namespace App\Services\DocumentGenerators;

use App\Services\TemplateResolver;

class CertificateRetificationDocGenerator extends BaseCertificateDocGenerator
{
    protected function getTemplatePath(): string
    {
        return app(TemplateResolver::class)->resolve('certificate_retification');
    }

    /**
     * Uses default fillAdditionalData() from base class
     * Retification has different legal text in template but same data structure
     */
}
