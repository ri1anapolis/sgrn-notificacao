<?php

namespace App\Services\DocumentGenerators;

class CertificateStandardDocGenerator extends BaseCertificateDocGenerator
{
    protected function getTemplatePath(): string
    {
        return storage_path('app/templates/certificate_standard.docx');
    }

    /**
     * Uses default fillAdditionalData() from base class (does nothing)
     * Standard certificate doesn't need edital data
     */
}
