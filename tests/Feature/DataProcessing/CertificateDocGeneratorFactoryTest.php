<?php

use App\Models\Address;
use App\Models\AlienationRealEstate;
use App\Models\Diligence;
use App\Models\DiligenceResult;
use App\Models\Notification;
use App\Models\NotifiedPerson;
use App\Models\RetificationArea;
use App\Services\DocumentGenerators\CertificateDocGeneratorFactory;
use App\Services\DocumentGenerators\CertificateEditalDocGenerator;
use App\Services\DocumentGenerators\CertificateRetificationDocGenerator;
use App\Services\DocumentGenerators\CertificateStandardDocGenerator;
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

    expect($generator)->toBeInstanceOf(CertificateStandardDocGenerator::class);
});

it('resolves CertificateEditalDocGenerator for unsuccessful notification', function () {
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

    Diligence::factory()
        ->for($address)
        ->for($unsuccessfulResult, 'diligenceResult')
        ->create(['visit_number' => 1]);

    $notification->load('addresses.diligences.diligenceResult');

    $generator = $this->factory->resolve($notification);

    expect($generator)->toBeInstanceOf(CertificateEditalDocGenerator::class);
});

it('resolves CertificateRetificationDocGenerator for RetificationArea nature regardless of success', function () {
    $successResult = DiligenceResult::create([
        'group' => 'Devedor Presente - Notificação Realizada Com Sucesso',
        'code' => 'devedor_presente_assinou_ret_test',
        'description' => 'O Devedor estava Presente e Assinou a Notificação.',
    ]);

    $retification = RetificationArea::create([
        'office' => 1,
        'rectifying_property_identification' => 'Lote 10',
        'rectifying_property_registration' => '12345',
    ]);

    $notification = Notification::factory()
        ->for($retification, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->create();

    $address = Address::factory()->for($notification)->create();

    Diligence::factory()
        ->for($address)
        ->for($successResult, 'diligenceResult')
        ->create(['visit_number' => 1]);

    $notification->load('addresses.diligences.diligenceResult');

    $generator = $this->factory->resolve($notification);

    expect($generator)->toBeInstanceOf(CertificateRetificationDocGenerator::class);
});

it('resolves CertificateEditalDocGenerator when notification has no diligences', function () {
    $alienation = AlienationRealEstate::factory()->create();

    $notification = Notification::factory()
        ->for($alienation, 'notifiable')
        ->has(NotifiedPerson::factory()->count(1))
        ->has(Address::factory()->count(1))
        ->create();

    $notification->load('addresses.diligences.diligenceResult');

    $generator = $this->factory->resolve($notification);

    expect($generator)->toBeInstanceOf(CertificateEditalDocGenerator::class);
});
