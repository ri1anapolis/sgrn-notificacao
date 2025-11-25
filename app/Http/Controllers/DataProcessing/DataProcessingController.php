<?php

namespace App\Http\Controllers\DataProcessing;

use App\Data\NotificationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataProcessing\StoreProtocolRequest;
use App\Http\Requests\DataProcessing\UpdateNotificationRequest;
use App\Models\Notification;
use App\Services\DataProcessingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class DataProcessingController extends Controller
{
    public function check(string $protocol): JsonResponse
    {
        $exists = Notification::where('protocol', $protocol)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function store(StoreProtocolRequest $request)
    {
        $notification = Notification::firstOrCreate($request->validated());

        return to_route('data-processing.show', $notification)
            ->with('success', 'Protocolo criado com sucesso!');
    }

    public function show(Notification $notification)
    {
        return Inertia::render('DataProcessing/Show', [
            'notification' => NotificationData::from($notification),
        ]);
    }

    public function update(
        UpdateNotificationRequest $request,
        Notification $notification,
        DataProcessingService $dataProcessingService,
    ) {
        try {
            DB::transaction(
                fn () => $dataProcessingService->syncNotificationData(
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
