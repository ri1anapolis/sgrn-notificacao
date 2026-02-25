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
        $notification->loadMissing([
            'notifiable',
            'addresses.diligences.diligenceResult',
            'publicNotice',
        ]);

        // if (! $this->canDownloadCertificate($notification)) {
        //     throw new \Exception('A certidão só pode ser emitida se houver uma notificação de sucesso ou se todos os endereços tiverem 3 visitas realizadas.');
        // }

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
     * Check if a certificate can be downloaded.
     */
    // private function canDownloadCertificate(Notification $notification): bool
    // {
    //     $hasSuccess = $this->hasSuccessfulDiligence($notification);

    //     $hasThreeVisitsPerAddress = $notification->addresses->count() > 0 &&
    //         $notification->addresses->every(fn ($address) => $address->diligences->count() >= 3);

    //     return $hasSuccess || $hasThreeVisitsPerAddress;
    // }

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
