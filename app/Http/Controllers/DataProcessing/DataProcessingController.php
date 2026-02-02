<?php

namespace App\Http\Controllers\DataProcessing;

use App\Data\NotificationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataProcessing\StoreProtocolRequest;
use App\Http\Requests\DataProcessing\UpdateNotificationRequest;
use App\Models\Notification;
use App\Services\DataProcessingService;
use App\Services\DocumentGenerators\AdversePossessionEditalDocGenerator;
use App\Services\DocumentGenerators\AdversePossessionNotificationPrivateDocGenerator;
use App\Services\DocumentGenerators\AdversePossessionNotificationPublicDocGenerator;
use App\Services\DocumentGenerators\CertificateDocGeneratorFactory;
use App\Services\DocumentGenerators\EnvelopeDocGenerator;
use App\Services\DocumentGenerators\NotificationDocGeneratorFactory;
use App\Traits\HandlesDownloads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class DataProcessingController extends Controller
{
    use HandlesDownloads;

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

    public function downloadNotification(
        Notification $notification,
        NotificationDocGeneratorFactory $factory,
        Request $request
    ) {
        try {
            if (class_basename($notification->notifiable_type) === 'AdversePossession') {
                $variant = $request->query('variant', 'private');
                $generator = $variant === 'public_entity'
                    ? app(AdversePossessionNotificationPublicDocGenerator::class)
                    : app(AdversePossessionNotificationPrivateDocGenerator::class);

                $variantSuffix = $variant === 'public_entity' ? ' Entes Publicos' : ' Particulares';
            } else {
                $generator = $factory->resolve($notification->notifiable_type);
                $variantSuffix = '';
            }

            $tempFile = $generator->generate($notification);

            $natureName = match (class_basename($notification->notifiable_type)) {
                'AlienationRealEstate' => 'Alienacao Fiduciaria Imóvel',
                'RetificationArea' => 'Retificacao de Area',
                'Adjudication' => 'Adjudicacao Compulsoria',
                'PurchaseAndSaleIncorporation' => 'Compromisso de Compra e Venda Incorporacao',
                'PurchaseAndSaleSubdivision' => 'Compromisso de Compra e Venda Loteamento',
                'AdversePossession' => 'Usucapiao',
                default => 'Geral',
            };

            $fileName = "Notificacao {$natureName}{$variantSuffix} {$notification->protocol}.docx";

            return $this->downloadFile($tempFile, $fileName);
        } catch (\Exception $e) {
            return back()->withErrors(['geral' => 'Erro ao gerar documento: '.$e->getMessage()]);
        }
    }

    public function downloadEnvelope(
        Notification $notification,
        EnvelopeDocGenerator $generator
    ) {
        try {
            $tempFile = $generator->generate($notification);
            $fileName = "Envelope Notificacao {$notification->protocol}.docx";

            return $this->downloadFile($tempFile, $fileName);
        } catch (\Exception $e) {
            return back()->withErrors(['geral' => 'Erro ao gerar envelope: '.$e->getMessage()]);
        }
    }

    public function downloadCertificate(
        Notification $notification,
        CertificateDocGeneratorFactory $factory
    ) {
        try {
            $generator = $factory->resolve($notification);
            $tempFile = $generator->generate($notification);
            $fileName = "Certidao Notificacao {$notification->protocol}.docx";

            return $this->downloadFile($tempFile, $fileName);
        } catch (\Exception $e) {
            return back()->withErrors(['geral' => 'Erro ao gerar certidao: '.$e->getMessage()]);
        }
    }

    public function downloadAdversePossessionEdital(
        Notification $notification,
        AdversePossessionEditalDocGenerator $generator
    ) {
        try {
            if (class_basename($notification->notifiable_type) !== 'AdversePossession') {
                return back()->withErrors(['geral' => 'Edital de Notificação só está disponível para Usucapião.']);
            }

            $tempFile = $generator->generate($notification);
            $fileName = "Edital de Notificacao Usucapiao {$notification->protocol}.docx";

            return $this->downloadFile($tempFile, $fileName);
        } catch (\Exception $e) {
            return back()->withErrors(['geral' => 'Erro ao gerar edital: '.$e->getMessage()]);
        }
    }
}
