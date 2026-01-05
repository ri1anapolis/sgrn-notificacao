<?php

use App\Enums\UserRole;
use App\Models\Notification;
use App\Models\PublicNotice;
use App\Models\PublicNoticePublication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => UserRole::Admin]);
    $this->notification = Notification::factory()->create();

    actingAs($this->user);
});

describe('Public Notice Creation', function () {

    it('should create a new public notice with valid data', function () {
        $data = [
            'publication_organ' => 'ONR - Operador Nacional do Sistema de Registro Eletrônico de Imóveis (www.registrodeimoveis.org.br)',
            'days_between_email_and_notice' => 15,
            'publications' => [
                [
                    'publication_order' => 1,
                    'edition' => '001',
                    'notice_number' => '123',
                    'publication_date' => '2025-01-15',
                ],
            ],
        ];

        $response = post(route('notifications.public-notice.store', $this->notification->protocol), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('public_notices', [
            'notification_id' => $this->notification->id,
            'publication_organ' => $data['publication_organ'],
            'days_between_email_and_notice' => 15,
        ]);

        $this->assertDatabaseHas('public_notice_publications', [
            'publication_order' => 1,
            'edition' => '001',
            'notice_number' => '123',
        ]);
    });

    it('should update existing public notice instead of creating new one', function () {
        $existingNotice = PublicNotice::create([
            'notification_id' => $this->notification->id,
            'publication_organ' => 'Old Organ',
            'days_between_email_and_notice' => 10,
        ]);

        $existingNotice->publications()->create([
            'publication_order' => 1,
            'edition' => 'OLD',
            'notice_number' => '000',
            'publication_date' => '2025-01-01',
        ]);

        $data = [
            'publication_organ' => 'NEW Organ Updated',
            'days_between_email_and_notice' => 20,
            'publications' => [
                [
                    'publication_order' => 1,
                    'edition' => 'NEW',
                    'notice_number' => '999',
                    'publication_date' => '2025-02-15',
                ],
            ],
        ];

        $response = post(route('notifications.public-notice.store', $this->notification->protocol), $data);

        $response->assertRedirect();

        expect(PublicNotice::count())->toBe(1);

        $this->assertDatabaseHas('public_notices', [
            'id' => $existingNotice->id,
            'publication_organ' => 'NEW Organ Updated',
            'days_between_email_and_notice' => 20,
        ]);

        $this->assertDatabaseHas('public_notice_publications', [
            'edition' => 'NEW',
            'notice_number' => '999',
        ]);

        $this->assertDatabaseMissing('public_notice_publications', [
            'edition' => 'OLD',
        ]);
    });

    it('should create multiple publications for the same notice', function () {
        $data = [
            'publication_organ' => 'Test Organ',
            'days_between_email_and_notice' => 15,
            'publications' => [
                [
                    'publication_order' => 1,
                    'edition' => '001',
                    'notice_number' => '100',
                    'publication_date' => '2025-01-10',
                ],
                [
                    'publication_order' => 2,
                    'edition' => '002',
                    'notice_number' => '101',
                    'publication_date' => '2025-01-20',
                ],
                [
                    'publication_order' => 3,
                    'edition' => '003',
                    'notice_number' => '102',
                    'publication_date' => '2025-01-30',
                ],
            ],
        ];

        $response = post(route('notifications.public-notice.store', $this->notification->protocol), $data);

        $response->assertRedirect();

        expect(PublicNoticePublication::count())->toBe(3);

        $this->assertDatabaseHas('public_notice_publications', ['publication_order' => 1, 'edition' => '001']);
        $this->assertDatabaseHas('public_notice_publications', ['publication_order' => 2, 'edition' => '002']);
        $this->assertDatabaseHas('public_notice_publications', ['publication_order' => 3, 'edition' => '003']);
    });

    it('should save custom publication organ', function () {
        $customOrgan = 'Custom Registry Office - www.custom.com';

        $data = [
            'publication_organ' => $customOrgan,
            'days_between_email_and_notice' => null,
            'publications' => [
                [
                    'publication_order' => 1,
                    'edition' => '001',
                    'notice_number' => '123',
                    'publication_date' => '2025-01-15',
                ],
            ],
        ];

        $response = post(route('notifications.public-notice.store', $this->notification->protocol), $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('public_notices', [
            'notification_id' => $this->notification->id,
            'publication_organ' => $customOrgan,
        ]);
    });

});

describe('Public Notice Validation', function () {

    it('should return error if publication_organ is not provided', function () {
        $data = [
            'days_between_email_and_notice' => 15,
            'publications' => [],
        ];

        $response = post(route('notifications.public-notice.store', $this->notification->protocol), $data);

        $response->assertSessionHasErrors('publication_organ');
    });

    it('should accept days_between_email_and_notice as null', function () {
        $data = [
            'publication_organ' => 'Test Organ',
            'days_between_email_and_notice' => null,
            'publications' => [],
        ];

        $response = post(route('notifications.public-notice.store', $this->notification->protocol), $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('public_notices', [
            'notification_id' => $this->notification->id,
            'days_between_email_and_notice' => null,
        ]);
    });

});

describe('Public Notice Authentication', function () {

    it('should redirect to login if not authenticated', function () {
        auth()->logout();

        $data = [
            'publication_organ' => 'Test Organ',
            'days_between_email_and_notice' => 15,
            'publications' => [],
        ];

        $response = post(route('notifications.public-notice.store', $this->notification->protocol), $data);

        $response->assertRedirect(route('login'));
    });

});
