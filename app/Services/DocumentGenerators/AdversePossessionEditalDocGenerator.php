<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\DocumentGenerators\Traits\DocumentFormatterTrait;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class AdversePossessionEditalDocGenerator implements DocumentGeneratorInterface
{
    use DocumentFormatterTrait;

    public function generate(Notification $notification): string
    {
        $notification->load(['notifiable', 'notifiedPeople']);

        $templatePath = storage_path('app/templates/adverse_possession_edital.docx');
        if (! file_exists($templatePath)) {
            throw new Exception("Modelo de documento não encontrado: {$templatePath}");
        }

        $template = new TemplateProcessor($templatePath);

        $this->fillData($template, $notification);

        $tempFile = tempnam(sys_get_temp_dir(), 'usucapiao_edital_');
        $template->saveAs($tempFile);

        return $tempFile;
    }

    private function fillData(TemplateProcessor $template, Notification $notification): void
    {
        $data = $notification->notifiable;

        $now = Carbon::now();
        $dateString = $now->day.' de '.$now->translatedFormat('F').' de '.$now->year;
        $template->setValue('date', $dateString);

        $notifiablePeople = $notification->notifiedPeople
            ->map(fn ($person) => mb_strtoupper($person->name).', CPF nº '.$person->document)
            ->join(', ', ' e ');
        $template->setValue('notifiable_people', $notifiablePeople);

        $template->setValue('adverse_property', $data->adverse_possession_property_identification ?? '[DESCRIÇÃO DO IMÓVEL]');
        $template->setValue('registry_number', $data->adverse_possession_property_registration ?? '[NÚMERO DA MATRÍCULA]');
    }
}
