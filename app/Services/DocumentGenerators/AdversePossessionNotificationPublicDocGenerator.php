<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\DocumentGenerators\Traits\DocumentFormatterTrait;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class AdversePossessionNotificationPublicDocGenerator implements DocumentGeneratorInterface
{
    use DocumentFormatterTrait;

    public function generate(Notification $notification): string
    {
        $notification->load(['notifiable', 'notifiedPeople', 'addresses']);

        $templatePath = storage_path('app/templates/adverse_possession_notification_public.docx');
        if (! file_exists($templatePath)) {
            throw new Exception("Modelo de documento não encontrado: {$templatePath}");
        }

        $template = new TemplateProcessor($templatePath);

        $this->fillBasicData($template, $notification);
        $this->fillSpecificData($template, $notification);

        $tempFile = tempnam(sys_get_temp_dir(), 'usucapiao_notif_pub_');
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

        $template->setValue('vocative', 'Senhor,');
        $template->setValue('pronoum_treatment', 'Vossa Senhoria');
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
}
