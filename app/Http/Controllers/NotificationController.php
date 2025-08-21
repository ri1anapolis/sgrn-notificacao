<?php

namespace App\Http\Controllers;

use App\Data\NotificationData;
use App\Http\Requests\IndexNotificationRequest;
use App\Models\Notification;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index(IndexNotificationRequest $request, Notification $notification)
    {
        return Inertia::render('NotificationStage/Index', [
            'notification' => NotificationData::from($notification->load('notifiedPeople.addresses')),
        ]);
    }
}
