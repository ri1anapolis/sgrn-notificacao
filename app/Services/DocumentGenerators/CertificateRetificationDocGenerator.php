<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\TemplateResolver;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\TemplateProcessor;

class CertificateRetificationDocGenerator extends BaseCertificateDocGenerator
{
    use Traits\EditalPublicationTrait;

    protected function getTemplatePath(): string
    {
        return app(TemplateResolver::class)->resolve('certificate_retification');
    }

    protected function fillAdditionalData(TemplateProcessor $template, Notification $notification): void
    {
        $people = $notification->notifiedPeople;
        $addresses = $notification->addresses;
        $countPeople = $people->count();
        $countAddresses = $addresses->count();

        $hasMale = $people->contains(fn ($p) => $this->isMale($p));
        $hasFemale = $people->contains(fn ($p) => ! $this->isMale($p));

        // ${verb_confronter}
        if ($countPeople > 1) {
            $confronter = $hasMale ? 'os confrontantes' : 'as confrontantes';
        } else {
            $confronter = $this->isMale($people->first()) ? 'o confrontante' : 'a confrontante';
        }
        $template->setValue('verb_confronter', $confronter);

        // ${verb_property}
        if ($countPeople > 1) {
            $propertyVerb = ($hasMale && $hasFemale) ? 'proprietários' : ($hasMale ? 'proprietários' : 'proprietárias');
        } else {
            $propertyVerb = $this->isMale($people->first()) ? 'proprietário' : 'proprietária';
        }
        $template->setValue('verb_property', $propertyVerb);

        // ${property_article}
        $template->setValue('property_article', $countAddresses > 1 ? 'dos imóveis' : 'do imóvel');

        // ${notified_article}
        if ($countPeople > 1) {
            $notifiedArticle = $hasMale ? 'os notificados' : 'as notificadas';
        } else {
            $notifiedArticle = $this->isMale($people->first()) ? 'o notificado' : 'a notificada';
        }
        $template->setValue('notified_article', $notifiedArticle);

        // ${verb_manifest2}
        $template->setValue('verb_manifest2', $countPeople > 1 ? 'manifestarem' : 'manifestar');

        // ${verb_to_be}
        $template->setValue('verb_to_be', $countPeople > 1 ? 'estão' : 'está');

        // ${verb_attend}
        $template->setValue('verb_attend', $countPeople > 1 ? 'tenham comparecido' : 'tenha comparecido');

        // ${addressess_list}
        $addressRun = new TextRun;
        foreach ($addresses as $index => $address) {
            if ($index > 0) {
                $addressRun->addText(', ');
            }
            $desc = $address->address;
            $addressRun->addText("{$desc} - Matrícula de n˚ ");
            $addressRun->addText('DIGITAR MATRICULA', ['color' => 'FF0000']);
        }
        $template->setComplexValue('addressess_list', $addressRun);

        // Edital Variables
        $this->fillEditalPublications($template, $notification);
    }
}
