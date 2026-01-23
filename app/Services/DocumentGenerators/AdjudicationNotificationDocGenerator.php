<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\TemplateResolver;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class AdjudicationNotificationDocGenerator implements DocumentGeneratorInterface
{
    public function generate(Notification $notification): string
    {
        $notification->load(['notifiable', 'notifiedPeople', 'addresses']);

        $templatePath = app(TemplateResolver::class)->resolve('adjudication_notification');
        if (! file_exists($templatePath)) {
            throw new Exception("Modelo de documento não encontrado: {$templatePath}");
        }

        $template = new TemplateProcessor($templatePath);

        $this->fillBasicData($template, $notification);
        $this->fillPersonData($template, $notification);
        $this->fillSpecificData($template, $notification);
        $this->fillSignatureBlock($template, $notification);

        $tempFile = tempnam(sys_get_temp_dir(), 'notificacao_adjud_');
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
        $dateString = $now->day.' de '.$now->translatedFormat('F').' de '.$now->year.'.';
        $template->setValue('date', $dateString);

        $peopleCount = $notification->notifiedPeople->count();

        $hasMale = $notification->notifiedPeople->contains(function ($person) {
            $rawGender = $person->gender instanceof \BackedEnum ? $person->gender->value : $person->gender;
            $g = strtolower(trim((string) $rawGender));

            return in_array($g, ['masculine', 'male', 'm', 'masculino']);
        });

        if ($peopleCount > 1) {
            $vocative = $hasMale ? 'Senhores' : 'Senhoras';
            $verbNotify = $hasMale ? 'os senhores' : 'as senhoras';
            $pronounTreatment = 'Vossas Senhorias';
        } else {
            $person = $notification->notifiedPeople->first();

            $rawGender = null;
            if ($person && $person->gender) {
                $rawGender = $person->gender instanceof \BackedEnum ? $person->gender->value : $person->gender;
            }

            $gender = strtolower(trim((string) $rawGender));
            $isMale = in_array($gender, ['masculine', 'male', 'm', 'masculino']);

            $vocative = $isMale ? 'Senhor' : 'Senhora';
            $verbNotify = $isMale ? 'o senhor' : 'a senhora';
            $pronounTreatment = 'Vossa Senhoria';
        }

        $template->setValue('vocative', $vocative);
        $template->setValue('verb_notify', $verbNotify);
        $template->setValue('pronoun_treatment', $pronounTreatment);
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
            $template->setValue('adjudicated_property', '');

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

        $template->setValue('adjudicated_property', $data->adjudicated_property_identification ?? '');
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
