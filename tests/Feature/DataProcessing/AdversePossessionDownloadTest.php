<?php

use App\Models\Address;
use App\Models\AdversePossession;
use App\Models\Notification;
use App\Models\NotifiedPerson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('downloads adverse possession notification for private parties with correct filename', function () {
    $user = User::factory()->create();
    actingAs($user);

    $adversePossession = AdversePossession::factory()->create([
        'office' => 1,
        'adverse_possession_property_registration' => '12345',
        'adverse_possession_property_identification' => 'Lote 10, Quadra 5',
    ]);

    $notification = Notification::factory()
        ->for($adversePossession, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->has(Address::factory()->count(1))
        ->create([
            'protocol' => '100.200',
        ]);

    $response = get(route('data-processing.notification.download', $notification).'?variant=private');

    $response->assertOk();
    $response->assertHeader('content-disposition', 'attachment; filename="Notificacao Usucapiao Particulares 100.200.docx"');
});

it('downloads adverse possession notification for public entities with correct filename', function () {
    $user = User::factory()->create();
    actingAs($user);

    $adversePossession = AdversePossession::factory()->create([
        'office' => 2,
        'adverse_possession_property_registration' => '54321',
        'adverse_possession_property_identification' => 'Área Rural, Fazenda São João',
    ]);

    $notification = Notification::factory()
        ->for($adversePossession, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->has(Address::factory()->count(1))
        ->create([
            'protocol' => '200.300',
        ]);

    $response = get(route('data-processing.notification.download', $notification).'?variant=public_entity');

    $response->assertOk();
    $response->assertHeader('content-disposition', 'attachment; filename="Notificacao Usucapiao Entes Publicos 200.300.docx"');
});

it('downloads adverse possession edital with correct filename', function () {
    $user = User::factory()->create();
    actingAs($user);

    $adversePossession = AdversePossession::factory()->create([
        'office' => 1,
        'adverse_possession_property_registration' => '99999',
        'adverse_possession_property_identification' => 'Terreno Urbano, Centro',
    ]);

    $notification = Notification::factory()
        ->for($adversePossession, 'notifiable')
        ->has(NotifiedPerson::factory()->count(2))
        ->has(Address::factory()->count(1))
        ->create([
            'protocol' => '300.400',
        ]);

    $response = get(route('data-processing.adverse-possession-edital.download', $notification));

    $response->assertOk();
    $response->assertHeader('content-disposition', 'attachment; filename="Edital de Notificacao Usucapiao 300.400.docx"');
});

it('defaults to private variant when no variant is specified for adverse possession', function () {
    $user = User::factory()->create();
    actingAs($user);

    $adversePossession = AdversePossession::factory()->create([
        'office' => 1,
        'adverse_possession_property_registration' => '11111',
        'adverse_possession_property_identification' => 'Casa, Bairro Residencial',
    ]);

    $notification = Notification::factory()
        ->for($adversePossession, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->has(Address::factory()->count(1))
        ->create([
            'protocol' => '400.500',
        ]);

    $response = get(route('data-processing.notification.download', $notification));

    $response->assertOk();
    $response->assertHeader('content-disposition', 'attachment; filename="Notificacao Usucapiao Particulares 400.500.docx"');
});

it('returns error when trying to download edital for non-adverse-possession notification', function () {
    $user = User::factory()->create();
    actingAs($user);

    $retification = \App\Models\RetificationArea::create([
        'office' => 1,
        'rectifying_property_identification' => 'Lote 10',
        'rectifying_property_registration' => '12345',
    ]);

    $notification = Notification::factory()
        ->for($retification, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->has(Address::factory()->count(1))
        ->create([
            'protocol' => '500.600',
        ]);

    $response = get(route('data-processing.adverse-possession-edital.download', $notification));

    $response->assertRedirect();
    $response->assertSessionHasErrors('geral');
});
