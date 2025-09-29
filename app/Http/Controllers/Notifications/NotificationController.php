<?php

namespace App\Http\Controllers\Notifications;

use App\Data\AddressData;
use App\Data\NotificationData;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function show(Notification $notification)
    {
        $addresses = $notification->addresses;

        return Inertia::render('Notifications/Show', [
            'notification' => NotificationData::from($notification->load('notifiedPeople')),
            'addresses' => AddressData::collect(
                $addresses->load('notifiedPeople'),
            ),
        ]);
    }
}
