<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\TemplateResolver;
use PhpOffice\PhpWord\TemplateProcessor;

class CertificateAdjudicationDocGenerator extends BaseCertificateDocGenerator
{
    use Traits\EditalPublicationTrait;

    protected function getTemplatePath(): string
    {
        return app(TemplateResolver::class)->resolve('certificate_adjudication');
    }

    protected function fillAdditionalData(TemplateProcessor $template, Notification $notification): void
    {
        $people = $notification->notifiedPeople;
        $countPeople = $people->count();
        $hasMale = $people->contains(fn ($p) => $this->isMale($p));
        $hasFemale = $people->contains(fn ($p) => ! $this->isMale($p));

        if ($countPeople > 1) {
            $articleRequired = $hasMale ? 'os requeridos' : 'as requeridas';
        } else {
            $articleRequired = $this->isMale($people->first()) ? 'o requerido' : 'a requerida';
        }
        $template->setValue('article_required', $articleRequired);

        if ($countPeople > 1) {
            $notifiedArticle = $hasMale ? 'os notificados' : 'as notificadas';
        } else {
            $notifiedArticle = $this->isMale($people->first()) ? 'o notificado' : 'a notificada';
        }
        $template->setValue('notified_article', $notifiedArticle);

        $template->setValue('verb_manifest2', $countPeople > 1 ? 'manifestarem' : 'manifestar');

        $template->setValue('verb_attend', $countPeople > 1 ? 'comparecessem' : 'comparecesse');

        $this->fillEditalPublications($template, $notification);
    }
}
