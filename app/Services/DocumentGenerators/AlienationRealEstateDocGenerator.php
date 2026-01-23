<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\DocumentGenerators\Traits\DocumentFormatterTrait;
use App\Services\TemplateResolver;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class AlienationRealEstateDocGenerator implements DocumentGeneratorInterface
{
    use DocumentFormatterTrait;

    public function generate(Notification $notification): string
    {
        $notification->load(['notifiable', 'notifiedPeople', 'addresses']);

        $templatePath = app(TemplateResolver::class)->resolve('alienation_real_estate_notification');
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

        $tempFile = tempnam(sys_get_temp_dir(), 'notificacao_n_');
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
            $vocative = $hasMale ? 'Senhores,' : 'Senhoras,';
            $verbIntimate = 'intimar-lhes';
            $verbComply = 'cumpram';
            $verbManifest = 'manifestem';
            $pronounTreatment = 'Vossas Senhorias';
            $verbProceed = 'efetuarem';
            $verbNotifiedPassive = 'ficam Vossas Senhorias cientificadas';
            $editalVocative = $hasMale ? 'os senhores' : 'as senhoras';
            $verbGo = 'se dirijam';
            $guestTitle = $hasMale ? 'Convidados EM CONJUNTO:' : 'Convidadas EM CONJUNTO:';
            $guestRequestPhrase = $hasMale ? 'dos senhores abaixo nomeados' : 'das senhoras abaixo nomeadas';
        } else {
            $person = $notification->notifiedPeople->first();

            $rawGender = null;
            if ($person && $person->gender) {
                $rawGender = $person->gender instanceof \BackedEnum ? $person->gender->value : $person->gender;
            }

            $gender = strtolower(trim((string) $rawGender));
            $isMale = in_array($gender, ['masculine', 'male', 'm', 'masculino']);

            $greeting = $isMale ? 'Prezado Senhor,' : 'Prezada Senhora,';
            $vocative = $isMale ? 'Senhor,' : 'Senhora,';
            $verbIntimate = $isMale ? 'intimá-lo' : 'intimá-la';
            $verbComply = 'cumpra';
            $verbManifest = 'manifeste';
            $pronounTreatment = 'Vossa Senhoria';
            $verbProceed = 'efetuar';
            $termCientificado = $isMale ? 'cientificado' : 'cientificada';
            $verbNotifiedPassive = "fica Vossa Senhoria {$termCientificado}";
            $editalVocative = $isMale ? 'o senhor' : 'a senhora';
            $verbGo = 'se dirija';
            $guestTitle = $isMale ? 'Convidado:' : 'Convidada:';
            $guestRequestPhrase = $isMale ? 'do senhor abaixo nomeado' : 'da senhora abaixo nomeada';
        }

        $template->setValue('greeting', $greeting);
        $template->setValue('vocative', $vocative);
        $template->setValue('verb_intimate', $verbIntimate);
        $template->setValue('texto_intimar_verb', $verbIntimate);
        $template->setValue('verb_comply', $verbComply);
        $template->setValue('verb_manifest', $verbManifest);
        $template->setValue('pronoun_treatment', $pronounTreatment);
        $template->setValue('termo_vossas_senhorias', $pronounTreatment);
        $template->setValue('verb_proceed', $verbProceed);
        $template->setValue('verb_notified_passive', $verbNotifiedPassive);
        $template->setValue('edital_vocative', $editalVocative);
        $template->setValue('verb_go', $verbGo);
        $template->setValue('guest_title', $guestTitle);
        $template->setValue('guest_request_phrase', $guestRequestPhrase);
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

        $firstPerson = $people->first();
        $address = $notification->addresses->first();
        $template->setValue('nome_devedor', $firstPerson->name ?? '');
        $template->setValue('documento_devedor', $firstPerson->document ?? '');
        $template->setValue('endereco_devedor', $address->address ?? '');
    }

    private function fillSpecificData(TemplateProcessor $template, Notification $notification): void
    {
        $data = $notification->notifiable;

        $template->setValue('nature', 'Alienação Fiduciária de Bem Imóvel');

        if (! $data) {
            $fields = ['credor', 'cnpj_number', 'office', 'contract_number', 'contract_date', 'total_amount_debt', 'emoluments_intimation', 'guarantee_property_registration', 'guarantee_property_address', 'contract_registration_act', 'default_period', 'debt_position_date'];
            foreach ($fields as $field) {
                $template->setValue($field, '');
            }

            return;
        }

        $rawCreditor = $data->credor ?? $data->creditor ?? '';
        $creditorName = $rawCreditor;
        $cnpjNumber = '';

        $parts = preg_split('/[,.-]?\s*CNPJ[:.\\s]*/i', $rawCreditor);

        if (count($parts) > 1) {
            $creditorName = trim($parts[0]);
            $cnpjNumber = trim($parts[1]);
        }

        $template->setValue('creditor', mb_strtoupper($creditorName));
        $template->setValue('cnpj_number', $cnpjNumber);

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

        $template->setValue('contract_number', $data->contract_number ?? '');
        $template->setValue('act', $data->contract_registration_act ?? '');
        $template->setValue('registration_number', $data->guarantee_property_registration ?? '');
        $template->setValue('full_address', $data->guarantee_property_address ?? '');
        $template->setValue('default_period', $data->default_period ?? '');

        $this->formatDate($template, 'contract_date', $data->contract_date);
        $this->formatDate($template, 'debt_position_date', $data->debt_position_date);

        $this->formatCurrency($template, 'total_amount_debt', $data->total_amount_debt);
        $this->formatCurrency($template, 'emoluments_intimation', $data->emoluments_intimation);
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

    private function fillEditalData(TemplateProcessor $template, Notification $notification): void
    {
        $people = $notification->notifiedPeople;

        $textList = $people->map(function ($person) {
            $name = mb_strtoupper($person->name);
            $doc = $person->document;

            return "{$name}, inscrito no CPF nº {$doc}";
        })->join(', ', ' e ');

        $template->setValue('text_list_edital', $textList);
    }
}
