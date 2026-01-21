<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\DocumentGenerators\Traits\DocumentFormatterTrait;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class AdversePossessionNotificationPrivateDocGenerator implements DocumentGeneratorInterface
{
    use DocumentFormatterTrait;

    public function generate(Notification $notification): string
    {
        $notification->load(['notifiable', 'notifiedPeople', 'addresses']);

        $templatePath = storage_path('app/templates/adverse_possession_notification_private.docx');
        if (! file_exists($templatePath)) {
            throw new Exception("Modelo de documento não encontrado: {$templatePath}");
        }

        $template = new TemplateProcessor($templatePath);

        $this->fillBasicData($template, $notification);
        $this->fillPersonData($template, $notification);
        $this->fillSpecificData($template, $notification);
        $this->fillSignatureBlock($template, $notification);

        $tempFile = tempnam(sys_get_temp_dir(), 'usucapiao_notif_priv_');
        $template->saveAs($tempFile);

        return $tempFile;
    }

    private function fillBasicData(TemplateProcessor $template, Notification $notification): void
    {
        $protocolValue = $notification->protocol;

        if (is_numeric($protocolValue)) {
            $protocolValue = number_format((float) $protocolValue, 0, ',', '.');
        }

        $template->setValue('protocol', $protocolValue);

        $now = Carbon::now();
        $template->setValue('year', $now->year);
        $dateString = $now->day.' de '.$now->translatedFormat('F').' de '.$now->year;
        $template->setValue('date', $dateString);

        $people = $notification->notifiedPeople;
        $peopleCount = $people->count();

        $hasMale = $people->contains(function ($person) {
            $rawGender = $person->gender instanceof \BackedEnum ? $person->gender->value : $person->gender;
            $g = strtolower(trim((string) $rawGender));

            return in_array($g, ['masculine', 'male', 'm', 'masculino']);
        });

        if ($peopleCount > 1) {
            $vocative = $hasMale ? 'Senhores,' : 'Senhoras,';
            $verbManifest = 'manifestem';
            $pronounTreatment = 'Vossas Senhorias';
            $notifiablePeoplePrefix = $hasMale ? 'os senhores ' : 'as senhoras ';
        } else {
            $person = $people->first();
            $isMale = $this->isMale($person);

            $vocative = $isMale ? 'Senhor,' : 'Senhora,';
            $verbManifest = 'manifeste';
            $pronounTreatment = 'Vossa Senhoria';
            $notifiablePeoplePrefix = $isMale ? 'o senhor ' : 'a senhora ';
        }

        $notifiablePeopleList = $people->map(function ($person) {
            return mb_strtoupper($person->name).', CPF nº '.$person->document;
        })->join(', ', ' e ');

        $notifiablePeople = $notifiablePeoplePrefix.$notifiablePeopleList;

        $template->setValue('vocative', $vocative);
        $template->setValue('verb_manifest', $verbManifest);
        $template->setValue('pronoun_treatment', $pronounTreatment);
        $template->setValue('notifiable_people', $notifiablePeople);
    }

    private function fillPersonData(TemplateProcessor $template, Notification $notification): void
    {
        $people = $notification->notifiedPeople;
        $count = $people->count();

        $template->cloneBlock('BLOCK_PEOPLE', $count, true, true);

        foreach ($people as $index => $person) {
            $i = $index + 1;
            $line = mb_strtoupper($person->name).', CPF nº '.$person->document;
            $template->setValue("line_qualification#{$i}", $line);
        }
    }

    private function fillSpecificData(TemplateProcessor $template, Notification $notification): void
    {
        $data = $notification->notifiable;

        if (! $data) {
            $template->setValue('office', '____');
            $template->setValue('registry_number', '[NÚMERO DA MATRÍCULA]');

            return;
        }

        $officeValue = $data->office ?? null;
        $officeFormatted = '____';
        if ($officeValue) {
            if (is_numeric($officeValue)) {
                $officeFormatted = number_format((float) $officeValue, 0, ',', '.');
            } else {
                $officeFormatted = $officeValue;
            }
        }
        $template->setValue('office', $officeFormatted);

        $template->setValue('registry_number', $data->adverse_possession_property_registration ?? '[NÚMERO DA MATRÍCULA]');
    }

    private function fillSignatureBlock(TemplateProcessor $template, Notification $notification): void
    {
        $people = $notification->notifiedPeople;
        $count = $people->count();

        $template->cloneBlock('BLOCK_SIGNATURE', $count, true, true);

        foreach ($people as $index => $person) {
            $i = $index + 1;
            $template->setValue("signature_name#{$i}", mb_strtoupper($person->name));
            $template->setValue("signature_doc#{$i}", $person->document);
        }
    }
}
