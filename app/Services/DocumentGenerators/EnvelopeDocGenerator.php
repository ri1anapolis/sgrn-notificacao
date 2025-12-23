<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class EnvelopeDocGenerator implements DocumentGeneratorInterface
{
    public function generate(Notification $notification): string
    {
        $templatePath = storage_path('app/templates/envelope.docx');

        if (! file_exists($templatePath)) {
            throw new Exception("Modelo de documento não encontrado: {$templatePath}");
        }

        $template = new TemplateProcessor($templatePath);

        $people = $notification->notifiedPeople;
        $peopleCount = $people->count();
        $globalAddresses = $notification->addresses;

        $template->cloneBlock('BLOCK_ENVELOPE', $peopleCount, true, true);

        $notifiable = $notification->notifiable;
        $notificationAddressesText =
            $notifiable->guarantee_property_address ??
            $notifiable->guarantee_movable_property_description ??
            $notifiable->committed_property_identification ??
            $notifiable->rectifying_property_identification ??
            $notifiable->adjudicated_property_identification ??
            $notifiable->adverse_possession_property_identification ??
            '';

        $officeValue = $notifiable->office ?? null;
        $officeFormatted = '';
        if ($officeValue) {
            if (is_numeric($officeValue)) {
                $officeFormatted = number_format((float) $officeValue, 0, ',', '.');
            } else {
                $officeFormatted = $officeValue;
            }
        }

        $protocolValue = $notification->protocol;
        if (is_numeric($protocolValue)) {
            $protocolValue = number_format((float) $protocolValue, 0, ',', '.');
        }

        $nature = match (class_basename($notification->notifiable_type)) {
            'AlienationRealEstate' => 'Alienação Fiduciária de Bem Imóvel',
            'AlienationMovableProperty' => 'Alienação Fiduciária de Bem Móvel',
            'PurchaseAndSaleSubdivision' => 'Compromisso de Compra e Venda Loteamento',
            'PurchaseAndSaleIncorporation' => 'Compromisso de Compra e Venda Incorporação',
            'RetificationArea' => 'Retificação de Área',
            'Adjudication' => 'Adjudicação',
            'AdversePossession' => 'Usucapião',
            'Other' => 'Diversos',
            default => 'Notificação',
        };

        $notificationAddressesText =
            $notifiable->guarantee_property_address ??
            $notifiable->guarantee_movable_property_description ??
            $notifiable->committed_property_identification ??
            $notifiable->rectifying_property_identification ??
            $notifiable->adjudicated_property_identification ??
            $notifiable->adverse_possession_property_identification ??
            '';

        $peopleList = [];
        foreach ($people as $p) {
            $peopleList[] = mb_strtoupper($p->name);
        }
        $indent = '                               ';
        $peopleString = implode("\n".$indent, $peopleList);

        foreach ($people as $index => $mainPerson) {
            $i = $index + 1;

            $template->setValue("office#{$i}", $officeFormatted);
            $template->setValue("year#{$i}", date('Y'));
            $template->setValue("protocol#{$i}", $protocolValue);
            $template->setValue("nature#{$i}", $nature);
            $template->setValue("clause#{$i}", $notifiable->contractual_clause);
            $template->setValue("count_people#{$i}", $peopleCount);
            $template->setValue("current_page#{$i}", $i);
            $template->setValue("address_notification#{$i}", $notificationAddressesText.', Anápolis/GO');

            $template->setValue("people_list#{$i}", $peopleString);

            $allAddressesString = $globalAddresses
                ->map(fn ($a) => $a->address.', Anápolis/GO')
                ->implode("\n");

            $template->setValue("address_person#{$i}", $allAddressesString);
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'envelope_');
        $template->saveAs($tempFile);

        return $tempFile;
    }
}
