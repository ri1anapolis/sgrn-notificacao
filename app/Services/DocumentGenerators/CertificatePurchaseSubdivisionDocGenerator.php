<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\TemplateResolver;
use PhpOffice\PhpWord\TemplateProcessor;

class CertificatePurchaseSubdivisionDocGenerator extends BaseCertificateDocGenerator
{
    use Traits\EditalPublicationTrait;

    protected function getTemplatePath(): string
    {
        return app(TemplateResolver::class)->resolve('certificate_purchase_subdivision');
    }

    protected function fillAdditionalData(TemplateProcessor $template, Notification $notification): void
    {
        $people = $notification->notifiedPeople;
        $countPeople = $people->count();
        $hasMale = $people->contains(fn ($p) => $this->isMale($p));
        $hasFemale = $people->contains(fn ($p) => ! $this->isMale($p));

        if ($countPeople > 1) {
            $debtorArticle = ($hasMale && $hasFemale) ? 'dos devedores' : ($hasMale ? 'dos devedores' : 'das devedoras');
        } else {
            $debtorArticle = $this->isMale($people->first()) ? 'do devedor' : 'da devedora';
        }
        $template->setValue('debtor_article', $debtorArticle);

        $this->fillEditalPublications($template, $notification);
    }
}
