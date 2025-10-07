<?php

namespace App\Http\Controllers\DataProcessing;

use App\Data\NotificationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataProcessing\ShowDataProcessingRequest;
use App\Models\Notification;
use Inertia\Inertia;

class DataProcessingController extends Controller
{
    public function show(ShowDataProcessingRequest $request)
    {
        $protocol = $request->validated('protocol');

        $notification = Notification::firstOrNew(['protocol' => $protocol]);

        if (! $notification->exists) {
            $notification->save();
        }

        $notificationData = NotificationData::from(
            $notification->load(['notifiedPeople', 'addresses.notifiedPeople', 'notifiable'])
        );

        return Inertia::render('DataProcessing/Show', [
            'notification' => $notificationData,
            'protocol' => $protocol,
        ]);
    }
}
