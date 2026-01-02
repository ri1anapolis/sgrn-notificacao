<?php

use App\Enums\UserRole;
use App\Models\DigitalContact;
use App\Models\Notification;
use App\Models\NotifiedPerson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => UserRole::Admin]);
    $this->notification = Notification::factory()->create();
    $this->notifiedPerson = NotifiedPerson::factory()->create([
        'notification_id' => $this->notification->id,
    ]);

    actingAs($this->user);
});

describe('Digital Contact Creation', function () {

    it('should create a new digital contact for a notified person', function () {
        $data = [
            'contact_date' => '2025-01-15',
            'contact_time' => '14:30',
            'whatsapp_result' => 'Message sent and read',
            'email_result' => 'Email delivered successfully',
            'custom_result' => 'Awaiting response',
        ];

        $response = post(route('notifications.digital-contacts.store', [
            'notification' => $this->notification->protocol,
            'notifiedPerson' => $this->notifiedPerson->id,
        ]), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('digital_contacts', [
            'notification_id' => $this->notification->id,
            'contact_date' => '2025-01-15',
            'contact_time' => '14:30:00',
            'whatsapp_result' => 'Message sent and read',
            'email_result' => 'Email delivered successfully',
        ]);

        $digitalContact = DigitalContact::first();
        expect($digitalContact->notifiedPerson->id)->toBe($this->notifiedPerson->id);
    });

    it('should update existing contact instead of creating new one', function () {
        $existingContact = DigitalContact::create([
            'notification_id' => $this->notification->id,
            'notified_person_id' => $this->notifiedPerson->id,
            'user_id' => $this->user->id,
            'contact_date' => '2025-01-10',
            'contact_time' => '10:00',
            'whatsapp_result' => 'Old result',
            'email_result' => 'Old email',
            'custom_result' => 'Old custom',
        ]);

        $updatedData = [
            'contact_date' => '2025-01-20',
            'contact_time' => '18:45',
            'whatsapp_result' => 'UPDATED result',
            'email_result' => 'UPDATED email',
            'custom_result' => 'UPDATED custom',
        ];

        $response = post(route('notifications.digital-contacts.store', [
            'notification' => $this->notification->protocol,
            'notifiedPerson' => $this->notifiedPerson->id,
        ]), $updatedData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        expect(DigitalContact::count())->toBe(1);

        $this->assertDatabaseHas('digital_contacts', [
            'id' => $existingContact->id,
            'contact_date' => '2025-01-20',
            'contact_time' => '18:45:00',
            'whatsapp_result' => 'UPDATED result',
            'email_result' => 'UPDATED email',
        ]);

        $this->assertDatabaseMissing('digital_contacts', [
            'whatsapp_result' => 'Old result',
        ]);
    });

    it('should create different contacts for different people', function () {
        $person2 = NotifiedPerson::factory()->create([
            'notification_id' => $this->notification->id,
        ]);

        post(route('notifications.digital-contacts.store', [
            'notification' => $this->notification->protocol,
            'notifiedPerson' => $this->notifiedPerson->id,
        ]), [
            'contact_date' => '2025-01-15',
            'contact_time' => '10:00',
            'whatsapp_result' => 'Person 1 WhatsApp',
            'email_result' => 'Person 1 Email',
            'custom_result' => null,
        ]);

        post(route('notifications.digital-contacts.store', [
            'notification' => $this->notification->protocol,
            'notifiedPerson' => $person2->id,
        ]), [
            'contact_date' => '2025-01-16',
            'contact_time' => '14:00',
            'whatsapp_result' => 'Person 2 WhatsApp',
            'email_result' => 'Person 2 Email',
            'custom_result' => null,
        ]);

        expect(DigitalContact::count())->toBe(2);

        $this->assertDatabaseHas('digital_contacts', ['whatsapp_result' => 'Person 1 WhatsApp']);
        $this->assertDatabaseHas('digital_contacts', ['whatsapp_result' => 'Person 2 WhatsApp']);

        $contact1 = $this->notifiedPerson->digitalContacts()->first();
        $contact2 = $person2->digitalContacts()->first();

        expect($contact1->whatsapp_result)->toBe('Person 1 WhatsApp');
        expect($contact2->whatsapp_result)->toBe('Person 2 WhatsApp');
    });

    it('should work with only whatsapp result provided', function () {
        $data = [
            'contact_date' => '2025-01-15',
            'contact_time' => '14:30',
            'whatsapp_result' => 'Only WhatsApp',
        ];

        $response = post(route('notifications.digital-contacts.store', [
            'notification' => $this->notification->protocol,
            'notifiedPerson' => $this->notifiedPerson->id,
        ]), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('digital_contacts', ['whatsapp_result' => 'Only WhatsApp']);
    });

    it('should work with only email result provided', function () {
        $data = [
            'contact_date' => '2025-01-15',
            'contact_time' => '14:30',
            'email_result' => 'Only Email',
        ];

        $response = post(route('notifications.digital-contacts.store', [
            'notification' => $this->notification->protocol,
            'notifiedPerson' => $this->notifiedPerson->id,
        ]), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('digital_contacts', ['email_result' => 'Only Email']);
    });

    it('should work with only custom result provided', function () {
        $data = [
            'contact_date' => '2025-01-15',
            'contact_time' => '14:30',
            'custom_result' => 'Only Custom',
        ];

        $response = post(route('notifications.digital-contacts.store', [
            'notification' => $this->notification->protocol,
            'notifiedPerson' => $this->notifiedPerson->id,
        ]), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('digital_contacts', ['custom_result' => 'Only Custom']);
    });
});

describe('Digital Contact Validation', function () {

    it('should return error if contact_date is not provided', function () {
        $data = [
            'contact_time' => '14:30',
            'whatsapp_result' => 'WhatsApp result',
            'email_result' => 'Email result',
        ];

        $response = post(route('notifications.digital-contacts.store', [
            'notification' => $this->notification->protocol,
            'notifiedPerson' => $this->notifiedPerson->id,
        ]), $data);

        $response->assertSessionHasErrors('contact_date');
    });

    it('should return error if contact_time has invalid format', function () {
        $data = [
            'contact_date' => '2025-01-15',
            'contact_time' => '25:99',
            'whatsapp_result' => 'WhatsApp result',
            'email_result' => 'Email result',
        ];

        $response = post(route('notifications.digital-contacts.store', [
            'notification' => $this->notification->protocol,
            'notifiedPerson' => $this->notifiedPerson->id,
        ]), $data);

        $response->assertSessionHasErrors('contact_time');
    });

    it('should return error if contact_date has invalid format', function () {
        $data = [
            'contact_date' => 'invalid-date',
            'contact_time' => '14:30',
            'whatsapp_result' => 'WhatsApp result',
            'email_result' => 'Email result',
        ];

        $response = post(route('notifications.digital-contacts.store', [
            'notification' => $this->notification->protocol,
            'notifiedPerson' => $this->notifiedPerson->id,
        ]), $data);

        $response->assertSessionHasErrors('contact_date');
    });

    it('should return error if all results (whatsapp, email, custom) are missing', function () {
        $data = [
            'contact_date' => '2025-01-15',
            'contact_time' => '14:30',
        ];

        $response = post(route('notifications.digital-contacts.store', [
            'notification' => $this->notification->protocol,
            'notifiedPerson' => $this->notifiedPerson->id,
        ]), $data);

        $response->assertSessionHasErrors(['whatsapp_result', 'email_result', 'custom_result']);
    });
});

describe('Digital Contact Authentication', function () {

    it('should redirect to login if not authenticated', function () {
        auth()->logout();

        $data = [
            'contact_date' => '2025-01-15',
            'contact_time' => '14:30',
            'whatsapp_result' => 'WhatsApp result',
            'email_result' => 'Email result',
        ];

        $response = post(route('notifications.digital-contacts.store', [
            'notification' => $this->notification->protocol,
            'notifiedPerson' => $this->notifiedPerson->id,
        ]), $data);

        $response->assertRedirect(route('login'));
    });

});
