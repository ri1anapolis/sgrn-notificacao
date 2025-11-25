<?php

use App\Models\Address;
use App\Models\AlienationRealEstate;
use App\Models\Notification;
use App\Models\NotifiedPerson;
use App\Models\Other;
use App\Services\DataProcessingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(DataProcessingService::class);

    $this->notification = Notification::factory()->create();
});

describe('Notified People Sync', function () {
    it('creates new notified people', function () {
        $data = [
            'notified_people' => [
                [
                    'name' => 'John Doe',
                    'document' => '123456789',
                    'email' => 'john@test.com',
                    'phone' => '12345678',
                    'gender' => 'masculine',
                ],
            ],
            'addresses' => [],
            'notifiable' => null,
        ];

        $this->service->syncNotificationData($this->notification, $data);

        $this->assertDatabaseHas('notified_people', [
            'notification_id' => $this->notification->id,
            'name' => 'John Doe',
            'document' => '123456789',
        ]);
    });

    it('updates existing notified people and deletes missing ones', function () {
        $person1 = NotifiedPerson::factory()->create(['notification_id' => $this->notification->id]);
        $person2 = NotifiedPerson::factory()->create(['notification_id' => $this->notification->id]);

        $data = [
            'notified_people' => [
                [
                    'id' => $person1->id,
                    'name' => 'Updated Name',
                    'document' => $person1->document,
                    'email' => $person1->email,
                    'phone' => $person1->phone,
                    'gender' => $person1->gender,
                ],
                [
                    'id' => null,
                    'name' => 'New Person',
                    'document' => '99999999',
                    'email' => 'new@test.com',
                    'phone' => '99999999',
                    'gender' => 'feminine',
                ],
            ],
            'addresses' => [],
            'notifiable' => null,
        ];

        $this->service->syncNotificationData($this->notification, $data);
        $this->assertDatabaseHas('notified_people', ['id' => $person1->id, 'name' => 'Updated Name']);
        $this->assertDatabaseHas('notified_people', ['name' => 'New Person']);
        $this->assertDatabaseMissing('notified_people', ['id' => $person2->id]);

    });
});

describe('Addresses Sync', function () {
    it('syncs addresses correctly (create, update, delete)', function () {
        $address = Address::factory()->create(['notification_id' => $this->notification->id, 'address' => 'Old Street']);

        $data = [
            'notified_people' => [],
            'addresses' => [
                [
                    'id' => $address->id,
                    'full_address' => 'Updated Street 123',

                ],
                [
                    'id' => null,
                    'full_address' => 'New Avenue 456',

                ],
            ],
            'notifiable' => null,
        ];

        $this->service->syncNotificationData($this->notification, $data);

        $this->assertDatabaseHas('addresses', ['id' => $address->id, 'address' => 'Updated Street 123']);
        $this->assertDatabaseHas('addresses', ['address' => 'New Avenue 456']);
        $this->assertCount(2, $this->notification->refresh()->addresses);
    });
});

describe('Notifiable (Polymorphic) Sync', function () {
    it('creates and associates a new notifiable', function () {
        $data = [
            'notified_people' => [],
            'addresses' => [],
            'notifiable' => [
                'notifiable_type' => AlienationRealEstate::class,
                'creditor' => 'Bank Test',
                'office' => '1',
                'total_amount_debt' => '1000.00',
            ],
        ];

        $this->service->syncNotificationData($this->notification, $data);

        $this->assertDatabaseHas('alienation_real_estates', [
            'creditor' => 'Bank Test',
            'office' => '1',
        ]);

        $this->notification->refresh();
        expect($this->notification->notifiable)->toBeInstanceOf(AlienationRealEstate::class)
            ->and($this->notification->notifiable->creditor)->toBe('Bank Test');
    });

    it('updates an existing notifiable', function () {
        $alienation = AlienationRealEstate::factory()->create(['creditor' => 'Old Bank']);
        $this->notification->notifiable()->associate($alienation)->save();

        $data = [
            'notified_people' => [],
            'addresses' => [],
            'notifiable' => [
                'id' => $alienation->id,
                'notifiable_type' => AlienationRealEstate::class,
                'creditor' => 'New Bank Updated',
                'office' => $alienation->office,
            ],
        ];

        $this->service->syncNotificationData($this->notification, $data);

        $this->assertDatabaseHas('alienation_real_estates', [
            'id' => $alienation->id,
            'creditor' => 'New Bank Updated',
        ]);
    });

    it('swaps notifiable type (deletes old, creates new)', function () {
        $alienation = AlienationRealEstate::factory()->create();
        $this->notification->notifiable()->associate($alienation)->save();

        $data = [
            'notified_people' => [],
            'addresses' => [],
            'notifiable' => [
                'notifiable_type' => Other::class,
                'creditor' => 'Other Creditor',
                'office' => '2',
            ],
        ];

        $this->service->syncNotificationData($this->notification, $data);

        $this->notification->refresh();
        expect($this->notification->notifiable)->toBeInstanceOf(Other::class);
    });

    it('removes notifiable when data is null', function () {
        $alienation = AlienationRealEstate::factory()->create();
        $this->notification->notifiable()->associate($alienation)->save();

        $data = [
            'notified_people' => [],
            'addresses' => [],
        ];

        $this->service->syncNotificationData($this->notification, $data);

        $this->assertDatabaseMissing('alienation_real_estates', ['id' => $alienation->id]);
        $this->notification->refresh();
        expect($this->notification->notifiable)->toBeNull();
    });

    it('throws exception if notifiable class does not exist', function () {
        $data = [
            'notified_people' => [],
            'addresses' => [],
            'notifiable' => [
                'notifiable_type' => 'App\\Models\\NonExistentModel',
            ],
        ];

        expect(fn () => $this->service->syncNotificationData($this->notification, $data))
            ->toThrow(Exception::class, "Classe polimórfica 'App\\Models\\NonExistentModel' não encontrada.");
    });
});

it('throws exception if notification has no ID (is not saved)', function () {
    $unsavedNotification = new Notification;

    $data = ['notified_people' => [], 'addresses' => []];

    expect(fn () => $this->service->syncNotificationData($unsavedNotification, $data))
        ->toThrow(Exception::class, 'A notificação deve ser salva e ter um ID para sincronizar pessoas.');
});
