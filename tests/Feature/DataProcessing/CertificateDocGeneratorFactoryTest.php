<?php

use App\Models\Address;
use App\Models\AlienationRealEstate;
use App\Models\Diligence;
use App\Models\DiligenceResult;
use App\Models\Notification;
use App\Models\NotifiedPerson;
use App\Services\DocumentGenerators\CertificateDocGeneratorFactory;
use App\Services\DocumentGenerators\CertificateEditalDocGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->factory = new CertificateDocGeneratorFactory;

    if (! file_exists(storage_path('app/templates'))) {
        mkdir(storage_path('app/templates'), 0755, true);
    }
});

it('resolves CertificateStandardDocGenerator for successful notification', function () {
    $successResult = DiligenceResult::create([
        'group' => 'Devedor Presente - Notificação Realizada Com Sucesso',
        'code' => 'devedor_presente_assinou_test1',
        'description' => 'O Devedor estava Presente e Assinou a Notificação.',
    ]);

    $alienation = AlienationRealEstate::factory()->create();

    $notification = Notification::factory()
        ->for($alienation, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->create();

    $address = Address::factory()->for($notification)->create();

    Diligence::factory()
        ->for($address)
        ->for($successResult, 'diligenceResult')
        ->create(['visit_number' => 1]);

    $notification->load('addresses.diligences.diligenceResult');

    $generator = $this->factory->resolve($notification);

    expect($generator)->toBeInstanceOf(CertificateEditalDocGenerator::class);
});

it('resolves CertificateEditalDocGenerator for unsuccessful notification with public notice', function () {
    $unsuccessfulResult = DiligenceResult::create([
        'group' => 'Devedor Ausente. Sem Qualquer Contato.',
        'code' => 'ausente_vizinhos_confirmam_test',
        'description' => 'Vizinhos Afirmam que a Parte Mora do Local.',
    ]);

    $alienation = AlienationRealEstate::factory()->create();

    $notification = Notification::factory()
        ->for($alienation, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->create();

    $address = Address::factory()->for($notification)->create();

    for ($i = 1; $i <= 3; $i++) {
        Diligence::factory()
            ->for($address)
            ->for($unsuccessfulResult, 'diligenceResult')
            ->create(['visit_number' => $i]);
    }

    \App\Models\PublicNotice::create([
        'notification_id' => $notification->id,
        'publication_organ' => 'Diário Oficial',
    ]);

    $notification->load(['addresses.diligences.diligenceResult', 'publicNotice']);

    $generator = $this->factory->resolve($notification);

    expect($generator)->toBeInstanceOf(CertificateEditalDocGenerator::class);
});

it('normalizes split placeholders and replaces values in certificate edital', function () {
    $unsuccessfulResult = DiligenceResult::create([
        'group' => 'Devedor Ausente. Sem Qualquer Contato.',
        'code' => 'ausente_vizinhos_confirmam_test_3',
        'description' => 'Vizinhos Afirmam que a Parte Mora do Local.',
    ]);

    $alienation = AlienationRealEstate::factory()->create();

    $notification = Notification::factory()
        ->for($alienation, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->create();

    $address = Address::factory()->for($notification)->create();

    for ($i = 1; $i <= 3; $i++) {
        Diligence::factory()
            ->for($address)
            ->for($unsuccessfulResult, 'diligenceResult')
            ->create(['visit_number' => $i]);
    }

    $publicNotice = \App\Models\PublicNotice::create([
        'notification_id' => $notification->id,
        'publication_organ' => 'Diário Oficial',
        'days_between_email_and_notice' => 10,
    ]);

    $publicNotice->publications()->create([
        'publication_order' => 1,
        'edition' => '1234',
        'notice_number' => '45/2026',
        'publication_date' => '2026-03-03',
    ]);

    $notification->load(['addresses.diligences.diligenceResult', 'publicNotice']);

    $generator = $this->factory->resolve($notification);
    $filePath = $generator->generate($notification);

    $xml = file_get_contents("zip://{$filePath}#word/document.xml");

    expect(str_contains($xml, '${period_email}'))->toBeFalse();
    expect(str_contains($xml, '${digital_contacts_result}'))->toBeFalse();
    expect(str_contains($xml, '${edital_edition_1}'))->toBeFalse();
    expect(str_contains($xml, '${edital_num_1}'))->toBeFalse();
    expect(str_contains($xml, '${edital_date_1}'))->toBeFalse();
    expect(str_contains($xml, '${publication_2_text}'))->toBeFalse();
    expect(str_contains($xml, '${publication_3_text}'))->toBeFalse();
    expect(str_contains($xml, '${contractual_clause}'))->toBeFalse();
});

it('resolves CertificateEditalDocGenerator for unsuccessful notification without public notice', function () {
    $unsuccessfulResult = DiligenceResult::create([
        'group' => 'Devedor Ausente. Sem Qualquer Contato.',
        'code' => 'ausente_vizinhos_confirmam_test_2',
        'description' => 'Vizinhos Afirmam que a Parte Mora do Local.',
    ]);

    $alienation = AlienationRealEstate::factory()->create();

    $notification = Notification::factory()
        ->for($alienation, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->create();

    $address = Address::factory()->for($notification)->create();

    for ($i = 1; $i <= 3; $i++) {
        Diligence::factory()
            ->for($address)
            ->for($unsuccessfulResult, 'diligenceResult')
            ->create(['visit_number' => $i]);
    }

    $notification->load(['addresses.diligences.diligenceResult', 'publicNotice']);

    $generator = $this->factory->resolve($notification);

    expect($generator)->toBeInstanceOf(CertificateEditalDocGenerator::class);
});
