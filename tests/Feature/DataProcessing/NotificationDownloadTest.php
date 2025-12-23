<?php

use App\Models\Address;
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

    if (! file_exists(storage_path('app/templates'))) {
        mkdir(storage_path('app/templates'), 0755, true);
    }
    $templatePath = storage_path('app/templates/rectification_notification.docx');
    $zip = new ZipArchive;
    if ($zip->open($templatePath, ZipArchive::CREATE) === true) {
        $zip->addFromString('word/document.xml', '<w:document></w:document>');
        $zip->close();
    }

    $response = get(route('data-processing.notification.download', $notification));

    $response->assertOk();

    $response->assertHeader('content-disposition', 'attachment; filename="Notificacao Retificacao de Area 999.888.docx"');

    if (file_exists($templatePath)) {
        unlink($templatePath);
    }
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

    if (! file_exists(storage_path('app/templates'))) {
        mkdir(storage_path('app/templates'), 0755, true);
    }
    $templatePath = storage_path('app/templates/alienation_real_estate_notification.docx');
    $zip = new ZipArchive;
    if ($zip->open($templatePath, ZipArchive::CREATE) === true) {
        $zip->addFromString('word/document.xml', '<w:document></w:document>');
        $zip->close();
    }

    $response = get(route('data-processing.notification.download', $notification));

    $response->assertOk();

    $response->assertHeader('content-disposition', 'attachment; filename="Notificacao Alienacao Fiduciaria Imovel 777.666.docx"; filename*=utf-8\'\'Notificacao%20Alienacao%20Fiduciaria%20Im%C3%B3vel%20777.666.docx');

    if (file_exists($templatePath)) {
        unlink($templatePath);
    }
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

    if (! file_exists(storage_path('app/templates'))) {
        mkdir(storage_path('app/templates'), 0755, true);
    }
    $templateFilename = 'envelope_test.docx';
    $templatePath = storage_path("app/templates/{$templateFilename}");

    $realPath = storage_path('app/templates/envelope.docx');
    $backupPath = storage_path('app/templates/envelope.docx.bak');
    $restored = false;

    if (file_exists($realPath)) {
        rename($realPath, $backupPath);
        $restored = true;
    }

    $zip = new ZipArchive;
    if ($zip->open($realPath, ZipArchive::CREATE) === true) {
        $zip->addFromString('word/document.xml', '<w:document></w:document>');
        $zip->close();
    }

    try {
        $response = get(route('data-processing.envelope.download', $notification));
        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename="Envelope Notificacao 555.444.docx"');
    } finally {
        if (file_exists($realPath)) {
            unlink($realPath);
        }
        if ($restored && file_exists($backupPath)) {
            rename($backupPath, $realPath);
        }
    }
});
