<?php

namespace App\Services\DocumentGenerators;

class CertificateRetificationDocGenerator extends BaseCertificateDocGenerator
{
    protected function getTemplatePath(): string
    {
        return storage_path('app/templates/certificate_retification.docx');
    }

    /**
     * Uses default fillAdditionalData() from base class
     * Retification has different legal text in template but same data structure
     */
}
