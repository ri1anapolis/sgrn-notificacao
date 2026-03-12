<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\TemplateResolver;
use PhpOffice\PhpWord\TemplateProcessor;

class CertificateEditalDocGenerator extends BaseCertificateDocGenerator
{
    use Traits\DigitalContactsTrait;
    use Traits\EditalPublicationTrait;

    protected function getTemplatePath(): string
    {
        return app(TemplateResolver::class)->resolve('certificate_edital');
    }

    protected function fillAdditionalData(TemplateProcessor $template, Notification $notification): void
    {
        $genderData = $this->getGenderTerms($notification);
        $template->setValue('verb_debtors', $genderData['verb_debtors']);
        $template->setValue('verb_debtor_article', $genderData['verb_debtor_article']);

        $this->fillDigitalContactData($template, $notification);
        $this->fillEditalPublications($template, $notification);
    }
}
