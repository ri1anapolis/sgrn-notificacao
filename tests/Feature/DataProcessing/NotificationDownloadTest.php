<?php

use App\Models\Address;
use App\Models\Adjudication;
use App\Models\AlienationRealEstate;
use App\Models\Notification;
use App\Models\NotifiedPerson;
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
    $response->assertHeader('content-disposition', 'attachment; filename="Notificacao Alienacao Fiduciaria Imovel 777.666.docx"; filename*=utf-8\'\'Notificacao%20Alienacao%20Fiduciaria%20Im%C3%B3vel%20777.666.docx');
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
    $response->assertHeader('content-disposition', 'attachment; filename="Envelope Notificacao 555.444.docx"');
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
