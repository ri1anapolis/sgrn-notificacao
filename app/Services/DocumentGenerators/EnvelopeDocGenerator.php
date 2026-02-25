<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\TemplateResolver;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class EnvelopeDocGenerator implements DocumentGeneratorInterface
{
    public function generate(Notification $notification): string
    {
        $templatePath = app(TemplateResolver::class)->resolve('envelope');

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

        $indent = str_repeat("\u{00A0}", 36);
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
            $addrNotifRun = new \PhpOffice\PhpWord\Element\TextRun;
            $fontStyle = ['name' => 'Times New Roman', 'size' => 12, 'bold' => false];
            $addrNotifRun->addText($notificationAddressesText, $fontStyle);
            $template->setComplexValue("address_notification#{$i}", $addrNotifRun);

            $template->setValue("people_list#{$i}", $peopleString);

            $addressRun = new \PhpOffice\PhpWord\Element\TextRun;
            $addressString = $globalAddresses->map(fn ($a) => $a->address)->implode("\n");
            $addressRun->addText($addressString, $fontStyle);
            $template->setComplexValue("address_person#{$i}", $addressRun);

        }

        $tempFile = tempnam(sys_get_temp_dir(), 'envelope_');
        $template->saveAs($tempFile);

        return $tempFile;
    }
}
