<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\TemplateResolver;
use PhpOffice\PhpWord\TemplateProcessor;

class CertificatePurchaseIncorporationDocGenerator extends BaseCertificateDocGenerator
{
    use Traits\EditalPublicationTrait;

    protected function getTemplatePath(): string
    {
        return app(TemplateResolver::class)->resolve('certificate_purchase_incorporation');
    }

    protected function fillAdditionalData(TemplateProcessor $template, Notification $notification): void
    {
        $people = $notification->notifiedPeople;
        $countPeople = $people->count();
        $hasMale = $people->contains(fn ($p) => $this->isMale($p));
        $hasFemale = $people->contains(fn ($p) => ! $this->isMale($p));

        if ($countPeople > 1) {
            $vocative = $hasMale ? 'os senhores' : 'as senhoras';
        } else {
            $vocative = $this->isMale($people->first()) ? 'o senhor' : 'a senhora';
        }
        $template->setValue('vocative', $vocative);

        if ($countPeople > 1) {
            $verbNotify = 'purgarem';
            $verbIntimate = $hasMale ? 'de os intimados purgarem' : 'de as intimadas purgarem';
        } else {
            $verbNotify = 'purgar';
            $verbIntimate = $this->isMale($people->first()) ? 'de o intimado purgar' : 'de a intimada purgar';
        }
        $template->setValue('verb_notify', $verbNotify);
        $template->setValue('verb_intimate', $verbIntimate);

        if ($countPeople > 1) {
            $verbDebtors = ($hasMale && $hasFemale) ? 'os devedores' : ($hasMale ? 'os devedores' : 'as devedoras');
        } else {
            $verbDebtors = $this->isMale($people->first()) ? 'o devedor' : 'a devedora';
        }
        $template->setValue('verb_debtors', $verbDebtors);

        $template->setValue('verb_to_be', $countPeople > 1 ? 'estão' : 'está');

        if ($countPeople > 1) {
            $debtorArticle = ($hasMale && $hasFemale) ? 'dos devedores' : ($hasMale ? 'dos devedores' : 'das devedoras');
            $intimatedArticle = $hasMale ? 'os intimados' : 'as intimadas';
        } else {
            $debtorArticle = $this->isMale($people->first()) ? 'do devedor' : 'da devedora';
            $intimatedArticle = $this->isMale($people->first()) ? 'o intimado' : 'a intimada';
        }
        $template->setValue('debtor_article', $debtorArticle);
        $template->setValue('intimated_article', $intimatedArticle);

        $this->fillEditalPublications($template, $notification);
    }
}
