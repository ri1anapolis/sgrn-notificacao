<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\TemplateResolver;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class RectificationNotificationDocGenerator implements DocumentGeneratorInterface
{
    public function generate(Notification $notification): string
    {
        $notification->load(['notifiable', 'notifiedPeople', 'addresses']);

        $templatePath = app(TemplateResolver::class)->resolve('rectification_notification');
        if (! file_exists($templatePath)) {
            throw new Exception("Modelo de documento não encontrado: {$templatePath}");
        }

        $template = new TemplateProcessor($templatePath);

        $this->fillBasicData($template, $notification);
        $this->fillPersonData($template, $notification);
        $this->fillSpecificData($template, $notification);
        $this->fillSignatureBlock($template, $notification);
        $this->fillGuestList($template, $notification);
        $this->fillEditalData($template, $notification);

        $tempFile = tempnam(sys_get_temp_dir(), 'notificacao_retif_');
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
        $dateString = $now->day.' de '.$now->translatedFormat('F').' de '.$now->year.'.';
        $template->setValue('date', $dateString);

        $peopleCount = $notification->notifiedPeople->count();

        $hasMale = $notification->notifiedPeople->contains(function ($person) {
            $rawGender = $person->gender instanceof \BackedEnum ? $person->gender->value : $person->gender;
            $g = strtolower(trim((string) $rawGender));

            return in_array($g, ['masculine', 'male', 'm', 'masculino']);
        });

        if ($peopleCount > 1) {
            $greeting = $hasMale ? 'Prezados Senhores,' : 'Prezadas Senhoras,';
            $vocative = $hasMale ? 'Senhores' : 'Senhoras';
            $verbNotify = 'notificar-lhes';
            $vocativeArticle = $hasMale ? 'os Senhores' : 'as Senhoras';
            $editalVocative = $hasMale ? 'os senhores' : 'as senhoras';
            $manifestVerb = 'manifestem';
            $verbNotifiedPhrase = $hasMale ? 'Ficam Vossas Senhorias NOTIFICADOS' : 'Ficam Vossas Senhorias NOTIFICADAS';
            $verbGo = 'dirijam';
            $guestRequestPhrase = $hasMale ? 'dos senhores abaixo nomeados' : 'das senhoras abaixo nomeadas';
            $guestTitle = $hasMale ? 'Convidados EM CONJUNTO:' : 'Convidadas EM CONJUNTO:';
        } else {
            $person = $notification->notifiedPeople->first();

            $rawGender = null;
            if ($person && $person->gender) {
                $rawGender = $person->gender instanceof \BackedEnum ? $person->gender->value : $person->gender;
            }

            $gender = strtolower(trim((string) $rawGender));
            $isMale = in_array($gender, ['masculine', 'male', 'm', 'masculino']);

            $greeting = $isMale ? 'Prezado Senhor,' : 'Prezada Senhora,';
            $vocative = $isMale ? 'Senhor' : 'Senhora';
            $verbNotify = $isMale ? 'notificá-lo' : 'notificá-la';
            $vocativeArticle = $isMale ? 'o Senhor' : 'a Senhora';
            $editalVocative = $isMale ? 'o senhor' : 'a senhora';
            $manifestVerb = 'manifeste';
            $termNotificado = $isMale ? 'NOTIFICADO' : 'NOTIFICADA';
            $verbNotifiedPhrase = "Fica Vossa Senhoria {$termNotificado}";
            $verbGo = 'dirija';
            $guestRequestPhrase = $isMale ? 'do senhor abaixo nomeado' : 'da senhora abaixo nomeada';
            $guestTitle = $isMale ? 'Convidado:' : 'Convidada:';
        }

        $template->setValue('greeting', $greeting);
        $template->setValue('vocative', $vocative);
        $template->setValue('verb_notify', $verbNotify);
        $template->setValue('vocative_article', $vocativeArticle);
        $template->setValue('edital_vocative', $editalVocative);
        $template->setValue('manifest_verb', $manifestVerb);
        $template->setValue('verb_notified_phrase', $verbNotifiedPhrase);
        $template->setValue('verb_go', $verbGo);
        $template->setValue('guest_request_phrase', $guestRequestPhrase);
        $template->setValue('guest_title', $guestTitle);
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
            $template->setValue('rectifying_property', '');
            $template->setValue('registry_number', '');

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

        $template->setValue('rectifying_property', $data->rectifying_property_identification ?? '');

        $template->setValue('registry_number', $data->rectifying_property_registration ?? '');
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

    private function fillGuestList(TemplateProcessor $template, Notification $notification): void
    {
        $people = $notification->notifiedPeople;
        $count = $people->count();

        $template->cloneBlock('BLOCK_GUESTS', $count, true, true);

        foreach ($people as $index => $person) {
            $i = $index + 1;
            $template->setValue("name_guest#{$i}", mb_strtoupper($person->name));
            $template->setValue("doc_guest#{$i}", $person->document);
        }
    }

    private function fillEditalData(TemplateProcessor $template, Notification $notification): void
    {
        $people = $notification->notifiedPeople;

        $hasMale = $people->contains(function ($person) {
            $rawGender = $person->gender instanceof \BackedEnum ? $person->gender->value : $person->gender;
            $g = strtolower(trim((string) $rawGender));

            return in_array($g, ['masculine', 'male', 'm', 'masculino']);
        });

        $textList = $people->map(function ($person) use ($hasMale, $people) {
            $name = mb_strtoupper($person->name);
            $doc = $person->document;
            $inscrito = ($people->count() > 1)
                ? ($hasMale ? 'inscrito' : 'inscrita')
                : (($hasMale || $this->isMale($person)) ? 'inscrito' : 'inscrita');

            return "{$name}, {$inscrito} no CPF nº {$doc}";
        })->join(', ', ' e ');

        $template->setValue('text_list_edital', $textList);
    }

    private function isMale($person): bool
    {
        $rawGender = $person->gender instanceof \BackedEnum ? $person->gender->value : $person->gender;
        $g = strtolower(trim((string) $rawGender));

        return in_array($g, ['masculine', 'male', 'm', 'masculino']);
    }
}
