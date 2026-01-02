<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notifications\PublicNotice\StorePublicNoticeRequest;
use App\Models\Notification;

class PublicNoticeController extends Controller
{
    public function store(StorePublicNoticeRequest $request, Notification $notification)
    {
        $validated = $request->validated();

        $publicNotice = $notification->publicNotice()->updateOrCreate(
            ['notification_id' => $notification->id],
            [
                'publication_organ' => $validated['publication_organ'],
                'days_between_email_and_notice' => $validated['days_between_email_and_notice'] ?? null,
            ]
        );

        if (isset($validated['publications'])) {
            $publicNotice->publications()->delete();

            foreach ($validated['publications'] as $pubData) {
                if ($pubData['edition'] || $pubData['notice_number'] || $pubData['publication_date']) {
                    $publicNotice->publications()->create($pubData);
                }
            }
        }

        return redirect()->back()->with('success', 'Dados do edital salvos com sucesso.');
    }
}
