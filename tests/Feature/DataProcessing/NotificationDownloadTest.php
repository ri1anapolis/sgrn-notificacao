<?php

use App\Models\Address;
use App\Models\Adjudication;
use App\Models\AlienationRealEstate;
use App\Models\Notification;
use App\Models\NotifiedPerson;
use App\Models\PurchaseAndSaleIncorporation;
use App\Models\PurchaseAndSaleSubdivision;
use App\Models\RetificationArea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('downloads rectification notification with correct filename', function () {
    $user = User::factory()->create();
    actingAs($user);

    $retification = RetificationArea::create([
        'office' => 1,
        'rectifying_property_identification' => 'Lote 10',
        'rectifying_property_registration' => '12345',
    ]);

    $notification = Notification::factory()
        ->for($retification, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->has(Address::factory()->count(1))
        ->create([
            'protocol' => '999.888',
        ]);

    $response = get(route('data-processing.notification.download', $notification));

    $response->assertOk();
    $response->assertHeader('content-disposition', 'attachment; filename="Notificacao Retificacao de Area 999.888.docx"');
});

it('downloads alienation notification with correct filename', function () {
    $user = User::factory()->create();
    actingAs($user);

    $alienation = AlienationRealEstate::factory()->create();

    $notification = Notification::factory()
        ->for($alienation, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->has(Address::factory()->count(1))
        ->create([
            'protocol' => '777.666',
        ]);

    $response = get(route('data-processing.notification.download', $notification));

    $response->assertOk();
    $response->assertHeader('content-disposition', 'attachment; filename=N-777.666.docx');
});

it('downloads envelope with correct filename', function () {
    $user = User::factory()->create();
    actingAs($user);

    $retification = RetificationArea::create([
        'office' => 1,
        'rectifying_property_identification' => 'Lote Envelope',
        'rectifying_property_registration' => '12345',
    ]);

    $notification = Notification::factory()
        ->for($retification, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->has(Address::factory()->count(1))
        ->create([
            'protocol' => '555.444',
        ]);

    $response = get(route('data-processing.envelope.download', $notification));

    $response->assertOk();
    $response->assertHeader('content-disposition', 'attachment; filename=E-555.444.docx');
});

it('downloads adjudication notification with correct filename', function () {
    $user = User::factory()->create();
    actingAs($user);

    $adjudication = Adjudication::factory()->create([
        'office' => 1,
        'adjudicated_property_identification' => 'Lote 15, Quadra 10',
        'adjudicated_property_registration' => '54321',
    ]);

    $notification = Notification::factory()
        ->for($adjudication, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->has(Address::factory()->count(1))
        ->create([
            'protocol' => '123.456',
        ]);

    $response = get(route('data-processing.notification.download', $notification));

    $response->assertOk();
    $response->assertHeader('content-disposition', 'attachment; filename="Notificacao Adjudicacao Compulsoria 123.456.docx"');
});

it('downloads purchase and sale incorporation notification with correct filename', function () {
    $user = User::factory()->create();
    actingAs($user);

    $purchaseIncorporation = PurchaseAndSaleIncorporation::factory()->create([
        'creditor' => 'Construtora ABC CNPJ 12.345.678/0001-99',
        'office' => 1,
        'committed_property_registration' => '98765',
        'committed_property_identification' => 'Apartamento 101, Bloco A',
        'contract_number' => 'CONTRATO-2025-001',
        'total_amount_debt' => 150000,
        'emoluments_intimation' => 500,
    ]);

    $notification = Notification::factory()
        ->for($purchaseIncorporation, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->has(Address::factory()->count(1))
        ->create([
            'protocol' => '111.222',
        ]);

    $response = get(route('data-processing.notification.download', $notification));

    $response->assertOk();
    $response->assertHeader('content-disposition', 'attachment; filename="Notificacao Compromisso de Compra e Venda Incorporacao 111.222.docx"');
});

it('downloads purchase and sale subdivision notification with correct filename', function () {
    $user = User::factory()->create();
    actingAs($user);

    $purchaseSubdivision = PurchaseAndSaleSubdivision::factory()->create([
        'creditor' => 'Loteamento XYZ CNPJ 98.765.432/0001-11',
        'office' => 2,
        'committed_property_registration' => '54321',
        'committed_property_identification' => 'Lote 25, Quadra 5',
        'contract_number' => 'CONTRATO-2025-002',
        'total_amount_debt' => 75000,
        'emoluments_intimation' => 300,
    ]);

    $notification = Notification::factory()
        ->for($purchaseSubdivision, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->has(Address::factory()->count(1))
        ->create([
            'protocol' => '333.444',
        ]);

    $response = get(route('data-processing.notification.download', $notification));

    $response->assertOk();
    $response->assertHeader('content-disposition', 'attachment; filename="Notificacao Compromisso de Compra e Venda Loteamento 333.444.docx"');
});
