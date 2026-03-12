<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\TemplateResolver;
use PhpOffice\PhpWord\TemplateProcessor;

class CertificateMovableAlienationDocGenerator extends BaseCertificateDocGenerator
{
    use Traits\DigitalContactsTrait;
    use Traits\EditalPublicationTrait;

    protected function getTemplatePath(): string
    {
        return app(TemplateResolver::class)->resolve('certificate_movable_alienation');
    }

    protected function fillAdditionalData(TemplateProcessor $template, Notification $notification): void
    {
        $data = $notification->notifiable;
        $people = $notification->notifiedPeople;
        $countPeople = $people->count();
        $hasMale = $people->contains(fn ($p) => $this->isMale($p));
        $hasFemale = $people->contains(fn ($p) => ! $this->isMale($p));

        if ($countPeople > 1) {
            $vocativeArticle = $hasMale ? 'os notificados' : 'as notificadas';
        } else {
            $vocativeArticle = $this->isMale($people->first()) ? 'o notificado' : 'a notificada';
        }
        $template->setValue('vocative_article', $vocativeArticle);

        $template->setValue('verb_notify', $countPeople > 1 ? 'purgarem' : 'purgar');

        $template->setValue('contract_object', $data->guarantee_movable_property_description ?? '');

        if ($countPeople > 1) {
            $debtorArticle = ($hasMale && $hasFemale) ? 'os devedores' : ($hasMale ? 'os devedores' : 'as devedoras');
        } else {
            $debtorArticle = $this->isMale($people->first()) ? 'o devedor' : 'a devedora';
        }
        $template->setValue('verb_debtor_article', $debtorArticle);

        $template->setValue('verb_to_be', $countPeople > 1 ? 'estão' : 'está');

        if ($data && $data->contract_date) {
            $template->setValue('contract_date', $data->contract_date->format('d/m/Y'));
        } else {
            $template->setValue('contract_date', '__/__/____');
        }

        $this->fillDigitalContactData($template, $notification);
        $this->fillEditalPublications($template, $notification);
    }
}
