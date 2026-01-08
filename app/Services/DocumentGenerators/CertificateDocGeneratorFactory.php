<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;

class CertificateDocGeneratorFactory
{
    /**
     * Resolve the appropriate certificate generator based on notification context.
     */
    public function resolve(Notification $notification): BaseCertificateDocGenerator
    {
        if (! $notification->relationLoaded('notifiable')) {
            $notification->load('notifiable');
        }
        if (! $notification->relationLoaded('addresses')) {
            $notification->load('addresses.diligences.diligenceResult');
        }

        $nature = class_basename($notification->notifiable_type);

        if ($nature === 'RetificationArea') {
            return new CertificateRetificationDocGenerator;
        }

        if ($this->hasSuccessfulDiligence($notification)) {
            return new CertificateStandardDocGenerator;
        }

        return new CertificateEditalDocGenerator;
    }

    /**
     * Check if any diligence was successful.
     */
    private function hasSuccessfulDiligence(Notification $notification): bool
    {
        return $notification->addresses->flatMap->diligences->some(function ($d) {
            return $d->diligenceResult && $d->diligenceResult->group === 'Devedor Presente - Notificação Realizada Com Sucesso';
        });
    }
}
