<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;
use App\Services\DocumentGenerators\Traits\DocumentFormatterTrait;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;

abstract class BaseCertificateDocGenerator implements DocumentGeneratorInterface
{
    use DocumentFormatterTrait;
    use Traits\NatureFieldMapperTrait;

    /**
     * Get the path to the template file.
     */
    abstract protected function getTemplatePath(): string;

    /**
     * Generate the certificate document.
     */
    public function generate(Notification $notification): string
    {
        $notification->load([
            'notifiable',
            'notifiedPeople',
            'addresses.diligences.diligenceResult',
            'publicNotice.publications',
            'digitalContacts.notifiedPerson',
        ]);

        $templatePath = $this->getTemplatePath();
        if (! file_exists($templatePath)) {
            throw new Exception("Modelo de documento não encontrado: {$templatePath}");
        }

        $template = new TemplateProcessor($templatePath);

        $this->fillBasicData($template, $notification);
        $this->fillPersonData($template, $notification);
        $this->fillVisitsData($template, $notification);
        $this->fillAdditionalData($template, $notification);

        $tempFile = tempnam(sys_get_temp_dir(), 'certidao_');
        $template->saveAs($tempFile);

        return $tempFile;
    }

    /**
     * Hook method for child classes to add additional data.
     * Default implementation does nothing.
     */
    protected function fillAdditionalData(TemplateProcessor $template, Notification $notification): void {}

    protected function fillBasicData(TemplateProcessor $template, Notification $notification): void
    {
        $data = $notification->notifiable;

        $protocolValue = $notification->protocol;
        if (is_numeric($protocolValue)) {
            $protocolValue = number_format((float) $protocolValue, 0, ',', '.');
        }
        $template->setValue('protocol', $protocolValue);

        $now = Carbon::now();
        $template->setValue('date_short', $now->format('d/m/Y'));
        $template->setValue('date', $now->day.' de '.$now->translatedFormat('F').' de '.$now->year);

        if (! $data) {
            return;
        }

        $template->setValue('nature', $this->getNatureName($notification->notifiable_type));
        $template->setValue('property_notified', $this->getPropertyAddress($data));
        $template->setValue('property_registry', $this->getPropertyRegistry($data, $notification->notifiable_type));
        $template->setValue('registry_place', $this->getRegistryOffice($data, $notification->notifiable_type));
        $template->setValue('contract_number', $this->getContractNumber($data, $notification->notifiable_type));
        $template->setValue('act_registry', $this->getContractRegistrationAct($data, $notification->notifiable_type));

        $this->formatCurrency($template, 'value_debt', $this->getTotalAmountDebt($data, $notification->notifiable_type));
        $this->formatDateShort($template, 'debt_date', $this->getDebtPositionDate($data, $notification->notifiable_type));

        $creditor = $data->credor ?? $data->creditor ?? '';
        $cnpj = '';
        if (preg_match('/CNPJ[:.\s]*([0-9.\-\/]+)/i', $creditor, $matches)) {
            $cnpj = $matches[1];
            $creditor = trim(preg_replace('/[,.-]?\s*CNPJ[:.\s]*[0-9.\-\/]+/i', '', $creditor));
        }
        $template->setValue('creditor', mb_strtoupper($creditor));
        $template->setValue('cnpj_number', $cnpj);
    }

    protected function fillPersonData(TemplateProcessor $template, Notification $notification): void
    {
        $people = $notification->notifiedPeople;

        $genderData = $this->getGenderTerms($notification);

        $template->setValue('vocative', $genderData['vocative']);
        $template->setValue('verb_notify', $genderData['verb_notify']);
        $template->setValue('verb_debtors', $genderData['verb_debtors']);
        $template->setValue('verb_debtor_article', $genderData['verb_debtor_article']);

        $personParts = [];
        foreach ($people as $person) {
            $personParts[] = mb_strtoupper($person->name).', CPF n˚ '.$person->document;
        }

        if (count($personParts) > 1) {
            $lastPerson = array_pop($personParts);
            $personListText = implode(', ', $personParts).' e '.$lastPerson;
        } else {
            $personListText = $personParts[0] ?? '';
        }

        $template->setValue('people_list', $personListText);

        $template->setValue('list_number_notified_people', $people->pluck('phone')->filter()->join(', '));
        $template->setValue('list_email_notified_people', $people->pluck('email')->filter()->join(', '));
    }

    protected function fillVisitsData(TemplateProcessor $template, Notification $notification): void
    {
        $addresses = $notification->addresses;
        $allVisits = [];

        foreach ($addresses as $addrIndex => $address) {
            foreach ($address->diligences->sortBy('visit_number') as $diligence) {
                $allVisits[] = [
                    'visit_number' => $diligence->visit_number,
                    'address' => $address->address,
                    'date' => $diligence->date ? $diligence->date->format('d/m/Y') : '',
                    'hour' => $diligence->date ? $diligence->date->format('H:i') : '',
                    'result' => preg_replace('/^\[.*?\]\s*/', '', $diligence->diligenceResult->description ?? ''),
                    'observations' => $diligence->observations,
                    'addr_index' => $addrIndex,
                    'is_success' => $diligence->diligenceResult?->isSuccess() ?? false,
                ];
            }
        }

        if ($notification->is_closed) {
            $successVisits = array_filter($allVisits, fn ($v) => $v['is_success']);
            if (! empty($successVisits)) {
                $lastSuccess = end($successVisits);
                $allVisits = [$lastSuccess];
            }
        }

        usort($allVisits, function ($a, $b) {
            if ($a['visit_number'] == $b['visit_number']) {
                return $a['addr_index'] <=> $b['addr_index'];
            }

            return $a['visit_number'] <=> $b['visit_number'];
        });

        $countTotal = count($allVisits);
        $maxVisit = collect($allVisits)->max('visit_number') ?: 0;

        $visitParts = [];
        foreach ($allVisits as $index => $visit) {
            $num = $visit['visit_number'];

            $textNum = $notification->is_closed
                ? 'primeira visita'
                : match ($num) {
                    1 => ($maxVisit > 1) ? 'primeira visita' : 'visita única',
                    2 => 'segunda visita',
                    3 => 'terceira visita',
                    default => "{$num}ª visita",
                };

            if ($index === 0) {
                $adverb = 'Em';
            } elseif ($index === $countTotal - 1) {
                $adverb = 'Por último, em';
            } else {
                $adverb = 'Também em';
            }

            $visitText = "{$adverb} {$textNum} ao {$visit['address']}, realizada em {$visit['date']} às {$visit['hour']}{$visit['result']}";

            if (! empty($visit['observations'])) {
                $visitText .= " (Observação: {$visit['observations']})";
            }

            $visitParts[] = $visitText;
        }

        $visitsText = implode("\n\n", $visitParts);
        $template->setValue('visits_list', $visitsText);
    }

    protected function getGenderTerms(Notification $notification): array
    {
        $people = $notification->notifiedPeople;
        $count = $people->count();
        $hasMale = $people->contains(fn ($p) => $this->isMale($p));
        $hasFemale = $people->contains(fn ($p) => ! $this->isMale($p));

        if ($count > 1) {
            return [
                'vocative' => $hasMale ? 'os senhores' : 'as senhoras',
                'verb_notify' => 'de os notificados purgarem',
                'verb_debtors' => ($hasMale && $hasFemale) ? 'os devedores estão' : ($hasMale ? 'os devedores estão' : 'as devedoras estão'),
                'verb_debtor_article' => ($hasMale && $hasFemale) ? 'os devedores' : ($hasMale ? 'os devedores' : 'as devedoras'),
            ];
        }

        $isMale = $this->isMale($people->first());

        return [
            'vocative' => $isMale ? 'o senhor' : 'a senhora',
            'verb_notify' => $isMale ? 'de o notificado purgar' : 'de a notificada purgar',
            'verb_debtors' => $isMale ? 'o devedor está' : 'a devedora está',
            'verb_debtor_article' => $isMale ? 'o devedor' : 'a devedora',
        ];
    }

    /**
     * Check if any diligence was successful.
     */
    protected function hasSuccessfulDiligence(Notification $notification): bool
    {
        return $notification->addresses->flatMap->diligences->some(function ($d) {
            return $d->diligenceResult && $d->diligenceResult->group === 'Devedor Presente - Notificação Realizada Com Sucesso';
        });
    }
}
