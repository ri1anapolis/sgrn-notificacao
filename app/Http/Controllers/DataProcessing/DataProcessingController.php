<?php

namespace App\Http\Controllers\DataProcessing;

use App\Data\NotificationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataProcessing\ShowDataProcessingRequest;
use App\Http\Requests\DataProcessing\UpdateNotificationRequest;
use App\Models\Notification;
use App\Services\DataProcessingService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class DataProcessingController extends Controller
{
    public function show(ShowDataProcessingRequest $request)
    {
        $protocol = $request->validated('protocol');

        $notification = Notification::firstOrNew(['protocol' => $protocol]);

        if (! $notification->exists) {
            $notification->protocol = $protocol;
            $notification->save();
        }

        $notificationData = NotificationData::from($notification);

        return Inertia::render('DataProcessing/Show', [
            'notification' => $notificationData,
        ]);
    }

    public function update(
        UpdateNotificationRequest $request,
        Notification $notification,
        DataProcessingService $dataProcessingService,
    ) {
        try {
            DB::transaction(
                fn() => $dataProcessingService->syncNotificationData(
                    $notification,
                    $request->validated()
                )
            );
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }

        return back()->with('success', 'Dados salvos com sucesso!');
    }
}
