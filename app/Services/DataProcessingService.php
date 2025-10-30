<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Notification;
use App\Models\NotifiedPerson;
use Exception;
use Illuminate\Database\Eloquent\Model;

class DataProcessingService
{
    public function syncNotificationData(Notification $notification, array $data): void
    {
        $this->syncNotifiedPeople($notification, $data['notified_people']);
        $this->syncAddresses($notification, $data['addresses']);

        $this->syncNotifiable($notification, $data['notifiable'] ?? null);
    }

    private function syncNotifiedPeople(Notification $notification, array $notifiedPeople): void
    {
        $notificationId = $notification->id;

        if (! $notificationId) {
            throw new Exception('A notificação deve ser salva e ter um ID para sincronizar pessoas.');
        }

        $keptIds = [];

        foreach ($notifiedPeople as $notifiedPersonData) {
            $id = $notifiedPersonData['id'] ?? null;

            $updateValues = [
                'name' => $notifiedPersonData['name'],
                'document' => $notifiedPersonData['document'],
                'email' => $notifiedPersonData['email'],
                'phone' => $notifiedPersonData['phone'],
                'gender' => $notifiedPersonData['gender'],
            ];

            if ($id) {
                $person = NotifiedPerson::updateOrCreate(
                    [
                        'id' => $id,
                        'notification_id' => $notificationId,
                    ],
                    $updateValues,
                );
            } else {
                $creationValues = array_merge($updateValues, ['notification_id' => $notificationId]);
                $person = NotifiedPerson::create($creationValues);
            }

            $keptIds[] = $person->id;
        }

        $notification->notifiedPeople()->whereNotIn('id', $keptIds)->delete();
    }

    private function syncAddresses(Notification $notification, array $addressesData): void
    {
        $notificationId = $notification->id;

        if (! $notificationId) {
            throw new Exception('A notificação deve ser salva e ter um ID para sincronizar endereços.');
        }

        $keptIds = [];

        foreach ($addressesData as $addressData) {
            $id = $addressData['id'] ?? null;
            $updateValues = ['address' => $addressData['full_address']];

            if ($id) {
                $address = Address::updateOrCreate(
                    [
                        'id' => $id,
                        'notification_id' => $notificationId,
                    ],
                    $updateValues
                );
            } else {
                $creationValues = array_merge($updateValues, ['notification_id' => $notificationId]);
                $address = Address::create($creationValues);
            }

            $keptIds[] = $address->id;
        }

        $notification->addresses()->whereNotIn('id', $keptIds)->delete();
    }

    private function syncNotifiable(Notification $notification, ?array $notifiableData): void
    {
        $currentNotifiable = $notification->notifiable;

        if (is_null($notifiableData)) {
            if ($currentNotifiable) {
                $currentNotifiable->delete();
                $notification->notifiable()->dissociate();
                $notification->save();
            }

            return;
        }

        $notifiableType = $notifiableData['notifiable_type'];

        $notifiableId = $notifiableData['id'] ?? 0;

        if ($notifiableId === 0) {
            $notifiableId = null;
        }

        unset($notifiableData['notifiable_type'], $notifiableData['id']);

        if (! class_exists($notifiableType)) {
            throw new Exception("Classe polimórfica '{$notifiableType}' não encontrada.");
        }

        if ($currentNotifiable && get_class($currentNotifiable) !== $notifiableType) {
            $currentNotifiable->delete();
            $currentNotifiable = null;
            $notifiableId = null;
        }

        /** @var Model $modelInstance */
        $modelInstance = $notifiableType::findOrNew($notifiableId);
        $modelInstance->fill($notifiableData);
        $modelInstance->save();

        $notification->notifiable()->associate($modelInstance);
        $notification->save();
    }
}
