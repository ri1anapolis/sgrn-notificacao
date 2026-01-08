<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use PhpOffice\PhpWord\TemplateProcessor;

class CertificateEditalDocGenerator extends BaseCertificateDocGenerator
{
    protected function getTemplatePath(): string
    {
        return storage_path('app/templates/certificate_edital.docx');
    }

    protected function fillAdditionalData(TemplateProcessor $template, Notification $notification): void
    {
        $this->fillEditalAndDigitalData($template, $notification);
    }

    private function fillEditalAndDigitalData(TemplateProcessor $template, Notification $notification): void
    {
        $genderData = $this->getGenderTerms($notification);
        $template->setValue('verb_debtors', $genderData['verb_debtors']);
        $template->setValue('verb_debtor_article', $genderData['verb_debtor_article']);

        $digitalContactsText = $this->buildDigitalContactsText($notification);
        $template->setValue('digital_contacts_result', $digitalContactsText);

        $notice = $notification->publicNotice;

        if ($notice && $notice->days_between_email_and_notice) {
            $template->setValue('period_email', "{$notice->days_between_email_and_notice} dias");
        } else {
            $template->setValue('period_email', '___ dias');
        }

        if ($notice) {
            $publications = $notice->publications->sortBy('publication_order');
            $pub1 = $publications->where('publication_order', 1)->first();
            $pub2 = $publications->where('publication_order', 2)->first();
            $pub3 = $publications->where('publication_order', 3)->first();

            $template->setValue('edital_edition_1', $pub1->edition ?? '___');
            $template->setValue('edital_num_1', $pub1->notice_number ?? '___');
            $template->setValue('edital_date_1', $pub1->publication_date ? $pub1->publication_date->format('d/m/Y') : '___');

            $pub2Text = '';
            if ($pub2) {
                $pub2Date = $pub2->publication_date ? $pub2->publication_date->format('d/m/Y') : '___';
                $pub2Text = 'A segunda publicação se deu na Edição nº '.($pub2->edition ?? '___').
                            ', edital n˚ '.($pub2->notice_number ?? '___').", em {$pub2Date}. ";
            }
            $template->setValue('publication_2_text', $pub2Text);

            $pub3Text = '';
            if ($pub3) {
                $pub3Date = $pub3->publication_date ? $pub3->publication_date->format('d/m/Y') : '___';
                $pub3Text = 'A terceira publicação se deu na Edição nº '.($pub3->edition ?? '___').
                            ', edital n˚ '.($pub3->notice_number ?? '___').", em {$pub3Date}.";
            }
            $template->setValue('publication_3_text', $pub3Text);
        } else {
            $template->setValue('edital_edition_1', '___');
            $template->setValue('edital_num_1', '___');
            $template->setValue('edital_date_1', '___');
            $template->setValue('publication_2_text', '');
            $template->setValue('publication_3_text', '');
        }
    }

    private function buildDigitalContactsText(Notification $notification): string
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

        return implode(' ', $parts) ?: 'Nenhum contato digital registrado.';
    }
}
