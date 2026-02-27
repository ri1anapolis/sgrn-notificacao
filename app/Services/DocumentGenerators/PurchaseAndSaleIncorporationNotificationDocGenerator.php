<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\DocumentGenerators\Traits\DocumentFormatterTrait;
use App\Services\TemplateResolver;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\TemplateProcessor;

class PurchaseAndSaleIncorporationNotificationDocGenerator implements DocumentGeneratorInterface
{
    use DocumentFormatterTrait;

    public function generate(Notification $notification): string
    {
        $notification->load(['notifiable', 'notifiedPeople', 'addresses']);

        $templatePath = app(TemplateResolver::class)->resolve('purchase_and_sale_incorporation_notification');
        if (! file_exists($templatePath)) {
            throw new Exception("Modelo de documento não encontrado: {$templatePath}");
        }

        $template = new TemplateProcessor($templatePath);

        $this->fillBasicData($template, $notification);
        $this->fillPersonData($template, $notification);
        $this->fillAddressData($template, $notification);
        $this->fillSpecificData($template, $notification);
        $this->fillEditalData($template, $notification);
        $this->fillSignatureBlock($template, $notification);

        $tempFile = tempnam(sys_get_temp_dir(), 'notificacao_purchase_incorporation_');
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
            $greeting = $hasMale ? 'Prezados Senhores,' : 'Prezadas Senhoras,';
            $vocative = $hasMale ? 'Senhores,' : 'Senhoras,';
            $verbIntimate = 'intimar-lhes';
            $verbComply = 'cumpram';
            $pronounTreatment = 'Vossas Senhorias';
            $pronounTreatmentPhrase = 'ficam Vossas Senhorias cientificadas';
            $genitiveTreatment = $hasMale ? 'dos Senhores abaixo nomeados' : 'das Senhoras abaixo nomeadas';
            $debtor_should = $hasMale ? 'os devedores deverão' : 'as devedoras deverão';
            $verb_go = 'se dirijam';
            $editalVocative = $hasMale ? 'os senhores' : 'as senhoras';
            $verbProceed = 'efetuarem';

            $guestRun = new TextRun;
            $fontStyle = ['name' => 'Times New Roman', 'size' => 12];
            $guestRun->addText($hasMale ? 'Convidados ' : 'Convidadas ', $fontStyle);
            $guestRun->addText('EM CONJUNTO', array_merge($fontStyle, ['underline' => 'single']));
            $guestRun->addText(':', $fontStyle);
            $template->setComplexValue('guest_title', $guestRun);
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
            $verbIntimate = 'intimar-lhe';
            $verbComply = 'cumpra';
            $pronounTreatment = 'Vossa Senhoria';
            $pronounTreatmentPhrase = 'fica Vossa Senhoria cientificada';
            $genitiveTreatment = $isMale ? 'do Senhor abaixo nomeado' : 'da Senhora abaixo nomeada';
            $debtor_should = $isMale ? 'o devedor deverá' : 'a devedora deverá';
            $verb_go = 'se dirija';
            $editalVocative = $isMale ? 'o senhor' : 'a senhora';
            $verbProceed = 'efetuar';

            $template->setValue('guest_title', $isMale ? 'Convidado:' : 'Convidada:');
        }

        $template->setValue('greeting', $greeting);
        $template->setValue('vocative', $vocative);
        $template->setValue('verb_intimate', $verbIntimate);
        $template->setValue('verb_comply', $verbComply);
        $template->setValue('pronoun_treatment', $pronounTreatment);
        $template->setValue('pronoun_treatment_phrase', $pronounTreatmentPhrase);
        $template->setValue('genitive_treatment', $genitiveTreatment);
        $template->setValue('debtor_should', $debtor_should);
        $template->setValue('verb_go', $verb_go);
        $template->setValue('edital_vocative', $editalVocative);
        $template->setValue('verb_proceed', $verbProceed);
    }

    private function fillPersonData(TemplateProcessor $template, Notification $notification): void
    {
        $people = $notification->notifiedPeople;
        $count = $people->count();

        $template->cloneBlock('BLOCK_PEOPLE', $count, true, true);
        $template->cloneBlock('BLOCK_PEOPLE', $count, true, true);

        foreach ($people as $index => $person) {
            $i = $index + 1;
            $line = mb_strtoupper($person->name).', CPF nº '.$person->document;
            $template->setValue("line_qualification#{$i}", $line);
        }
    }

    private function fillAddressData(TemplateProcessor $template, Notification $notification): void
    {
        $addresses = $notification->addresses;
        $count = $addresses->count();

        $template->cloneBlock('BLOCK_ADDRESSES', $count, true, true);

        foreach ($addresses as $index => $address) {
            $i = $index + 1;
            $isLast = ($index === $count - 1);
            $suffix = $isLast ? '.' : ',';
            $line = ($address->address ?? '').$suffix;
            $template->setValue("line_address#{$i}", $line);
        }
    }

    private function fillEditalData(TemplateProcessor $template, Notification $notification): void
    {
        $people = $notification->notifiedPeople;

        $textList = $people->map(function ($person) {
            $name = mb_strtoupper($person->name);
            $doc = $person->document;

            return "{$name}, CPF nº {$doc}";
        })->join(', ', ' e ');

        $template->setValue('text_list_edital', $textList);
    }

    private function fillSpecificData(TemplateProcessor $template, Notification $notification): void
    {
        $data = $notification->notifiable;

        if (! $data) {
            $fields = ['creditor', 'cnpj_number', 'office', 'contract_number', 'contract_date', 'total_amount_debt', 'total_amount_debt_written', 'emoluments_intimation', 'emoluments_intimation_written', 'property_purchase_and_sale', 'act', 'registration_number', 'default_period', 'debt_position_date'];
            foreach ($fields as $field) {
                $template->setValue($field, '');
            }

            return;
        }

        $rawCreditor = $data->creditor ?? '';
        $creditorName = $rawCreditor;
        $cnpjNumber = '';

        $parts = preg_split('/[,.-]?\s*CNPJ[:.\s]*/i', $rawCreditor);

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
        $template->setValue('registration_number', $data->committed_property_registration ?? '');
        $template->setValue('property_purchase_and_sale', $data->committed_property_identification ?? '');
        $template->setValue('default_period', $data->default_period ?? '');

        $this->formatDate($template, 'contract_date', $data->contract_date);
        $this->formatDate($template, 'debt_position_date', $data->debt_position_date);

        $totalDebtValue = (float) ($data->total_amount_debt ?? 0);
        $this->formatCurrency($template, 'total_amount_debt', $data->total_amount_debt);
        $template->setValue('total_amount_debt_written', $this->numberToWords($totalDebtValue));

        $emolumentsValue = (float) ($data->emoluments_intimation ?? 0);
        $this->formatCurrency($template, 'emoluments_intimation', $data->emoluments_intimation);
        $template->setValue('emoluments_intimation_written', $this->numberToWords($emolumentsValue));
    }

    private function fillSignatureBlock(TemplateProcessor $template, Notification $notification): void
    {
        $people = $notification->notifiedPeople;
        $count = $people->count();

        $template->cloneBlock('BLOCK_SIGNATURE', $count, true, true);

        foreach ($people as $index => $person) {
            $i = $index + 1;
            $isLast = ($index === $count - 1);
            $template->setValue("signature_name#{$i}", mb_strtoupper($person->name));

            $docValue = $person->document;
            if (! $isLast) {
                $docValue .= '</w:t><w:br/><w:t>';
            }
            $template->setValue("signature_doc#{$i}", $docValue);
        }
    }
}
