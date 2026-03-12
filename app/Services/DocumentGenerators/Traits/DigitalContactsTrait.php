<?php

namespace App\Services\DocumentGenerators\Traits;

use App\Models\Notification;
use PhpOffice\PhpWord\TemplateProcessor;

trait DigitalContactsTrait
{
    /**
     * Fill digital contact data into the template.
     */
    protected function fillDigitalContactData(TemplateProcessor $template, Notification $notification): void
    {
        $digitalContactsText = $this->buildDigitalContactsText($notification);
        $template->setValue('digital_contacts_result', $digitalContactsText);

        $notice = $notification->publicNotice;

        if ($notice && $notice->days_between_email_and_notice) {
            $template->setValue('period_email', "{$notice->days_between_email_and_notice} dias");
        } else {
            $template->setValue('period_email', '___ dias');
        }
    }

    /**
     * Build the summary text for digital contact attempts.
     */
    protected function buildDigitalContactsText(Notification $notification): string
    {
        $parts = [];

        foreach ($notification->notifiedPeople as $person) {
            $contact = $notification->digitalContacts
                ->where('notified_person_id', $person->id)
                ->first();

            if (! $contact) {
                continue;
            }

            $personName = mb_strtoupper($person->name);
            $contactParts = [];

            if ($contact->whatsapp_result) {
                $contactParts[] = "via WhatsApp e obteve o resultado: \"{$contact->whatsapp_result}\"";
            }

            if ($contact->email_result) {
                $contactParts[] = "via e-mail e obteve o resultado: \"{$contact->email_result}\"";
            }

            if ($contact->custom_result) {
                $contactParts[] = "por outros meios e obteve o resultado: \"{$contact->custom_result}\"";
            }

            if (empty($contactParts)) {
                continue;
            }

            if (count($contactParts) === 1) {
                $contactText = $contactParts[0];
            } elseif (count($contactParts) === 2) {
                $contactText = $contactParts[0].', e também '.$contactParts[1];
            } else {
                $lastPart = array_pop($contactParts);
                $contactText = implode(', ', $contactParts).', e também '.$lastPart;
            }

            $parts[] = "Tentou-se contato com {$personName} {$contactText}";
        }

        return implode(' ', $parts) ?: 'Nenhum contato digital registrado';
    }
}
