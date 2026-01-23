<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\DocumentGenerators\Traits\DocumentFormatterTrait;
use App\Services\TemplateResolver;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class PurchaseAndSaleNotificationDocGenerator implements DocumentGeneratorInterface
{
    use DocumentFormatterTrait;

    public function generate(Notification $notification): string
    {
        $notification->load(['notifiable', 'notifiedPeople', 'addresses']);

        $templatePath = app(TemplateResolver::class)->resolve('purchase_and_sale_notification');
        if (! file_exists($templatePath)) {
            throw new Exception("Modelo de documento não encontrado: {$templatePath}");
        }

        $template = new TemplateProcessor($templatePath);

        $this->fillBasicData($template, $notification);
        $this->fillPersonData($template, $notification);
        $this->fillAddressData($template, $notification);
        $this->fillSpecificData($template, $notification);
        $this->fillSignatureBlock($template, $notification);

        $tempFile = tempnam(sys_get_temp_dir(), 'notificacao_purchase_');
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
            $vocative = $hasMale ? 'Aos Senhores,' : 'Às Senhoras,';
            $verbIntimate = 'intimar-lhes';
            $verbComply = 'cumpram';
            $pronounTreatment = 'ficam Vossas Senhorias cientificadas';
        } else {
            $person = $notification->notifiedPeople->first();

            $rawGender = null;
            if ($person && $person->gender) {
                $rawGender = $person->gender instanceof \BackedEnum ? $person->gender->value : $person->gender;
            }

            $gender = strtolower(trim((string) $rawGender));
            $isMale = in_array($gender, ['masculine', 'male', 'm', 'masculino']);

            $greeting = $isMale ? 'Prezado Senhor,' : 'Prezada Senhora,';
            $vocative = $isMale ? 'Ao Senhor,' : 'À Senhora,';
            $verbIntimate = $isMale ? 'intimá-lo' : 'intimá-la';
            $verbComply = 'cumpra';
            $pronounTreatment = 'fica Vossa Senhoria cientificada';
        }

        $template->setValue('greeting', $greeting);
        $template->setValue('vocative', $vocative);
        $template->setValue('verb_intimate', $verbIntimate);
        $template->setValue('verb_comply', $verbComply);
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
