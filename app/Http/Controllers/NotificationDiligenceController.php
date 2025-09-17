<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationDiligenceController extends Controller
{
    public function show(IndexNotificationRequest $request, Notification $notification)
    {
        return Inertia::render('Notifications/Diligence/Show', [
            'notification' => NotificationData::from(
                $notification->load('notifiedPeople.addresses')
            ),
        ]);
    }
}
