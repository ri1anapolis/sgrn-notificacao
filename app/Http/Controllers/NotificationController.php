<?php

namespace App\Http\Controllers;

use App\Data\NotificationData;
use App\Http\Requests\IndexNotificationRequest;
use App\Models\Notification;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function show(IndexNotificationRequest $request, Notification $notification)
    {
        return Inertia::render('Notifications/Show', [
            'notification' => NotificationData::from(
                $notification->load('notifiedPeople.addresses')
            ),
        ]);
    }
}
