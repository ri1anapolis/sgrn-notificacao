<?php

namespace App\Services\DocumentGenerators\Traits;

use App\Models\Notification;
use PhpOffice\PhpWord\TemplateProcessor;

trait EditalPublicationTrait
{
    /**
     * Fill edital publication data into the template.
     */
    protected function fillEditalPublications(TemplateProcessor $template, Notification $notification): void
    {
        $notice = $notification->publicNotice;

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
}
