<?php

namespace App\Http\Controllers\Notifications;

use App\Data\AddressData;
use App\Data\DiligenceResultData;
use App\Data\NotificationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notifications\Diligence\StoreDiligenceRequest;
use App\Models\Address;
use App\Models\Diligence;
use App\Models\DiligenceResult;
use App\Models\Notification;
use App\Service\DiligenceHistoryService;
use Inertia\Inertia;

class NotificationDiligenceController extends Controller
{
    public function show(Notification $notification, Address $address)
    {
        $address->load([
            'diligences' => function ($query) {
                $query->with([
                    'diligenceResult',
                    'user',
                    'history.user',
                    'history.oldResult',
                    'history.newResult',
                ]);
            },
            'notifiedPeople',
        ]);

        $diligenceResults = DiligenceResult::active()->orderBy('group')->orderBy('order')->get();

        return Inertia::render('Notifications/Diligence/Show', [
            'notification' => NotificationData::from(
                $notification->load('notifiedPeople')
            ),
            'address' => AddressData::from($address),
            'diligenceResults' => DiligenceResultData::collect($diligenceResults),
        ]);
    }

    public function store(StoreDiligenceRequest $request, Notification $notification, Address $address)
    {
        $validated = $request->validated();

        $address->diligences()->create([
            'user_id' => request()->user()->id,
            'visit_number' => $validated['visit_number'],
            'diligence_result_id' => $validated['diligence_result_id'],
            'observations' => $validated['observations'],
            'date' => $validated['date'],
        ]);

        return redirect()->route('notifications.diligence.show', [
            'notification' => $notification,
            'address' => $address,
        ]);
    }

    public function update(
        StoreDiligenceRequest $request,
        Notification $notification,
        Address $address,
        Diligence $diligence,
        DiligenceHistoryService $service
    ) {

        $service->updateDiligence($diligence, $request->validated());

        $diligence->update($request->validated());

        return redirect()->route('notifications.diligence.show', [
            'notification' => $notification,
            'address' => $address,
        ]);
    }
}
