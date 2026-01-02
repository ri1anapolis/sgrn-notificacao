<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notifications\DigitalContact\StoreDigitalContactRequest;
use App\Models\DigitalContact;
use App\Models\Notification;
use App\Models\NotifiedPerson;

class DigitalContactController extends Controller
{
    public function index(Notification $notification)
    {
        $notifiedPeople = $notification->notifiedPeople()
            ->with('digitalContacts')
            ->get();

        return response()->json([
            'notifiedPeople' => $notifiedPeople,
        ]);
    }

    public function store(StoreDigitalContactRequest $request, Notification $notification, NotifiedPerson $notifiedPerson)
    {
        $validated = $request->validated();

        DigitalContact::updateOrCreate(
            [
                'notification_id' => $notification->id,
                'notified_person_id' => $notifiedPerson->id,
            ],
            [
                'user_id' => request()->user()->id,
                'contact_date' => $validated['contact_date'],
                'contact_time' => $validated['contact_time'],
                'whatsapp_result' => $validated['whatsapp_result'] ?? null,
                'email_result' => $validated['email_result'] ?? null,
                'custom_result' => $validated['custom_result'] ?? null,
            ]
        );

        return redirect()->back()->with('success', 'Contato digital registrado com sucesso.');
    }
}
