<?php

use App\Enums\UserRole;
use App\Models\Notification;
use App\Models\NotifiedPerson;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('shows a notification for admin', function () {
    $notification = Notification::factory()
        ->has(
            NotifiedPerson::factory()->hasAddresses(2)
        )
        ->create();

    $user = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    actingAs($user);

    $response = get(route('notifications.show', $notification));

    $response->assertOk();

    $response->assertInertia(
        fn (AssertableInertia $page) => $page->component('Notifications/Show')
            ->has(
                'notification',
                fn (AssertableInertia $prop) => $prop->where('protocol', $notification->protocol)
                    ->etc()
            )
    );
});

it('shows a notification for employee', function () {
    $user = User::factory()->create([
        'role' => UserRole::Employee,
    ]);

    $notification = Notification::factory()
        ->has(
            NotifiedPerson::factory()->hasAddresses(2)
        )
        ->create();

    actingAs($user);

    $response = get(route('notifications.show', $notification));

    $response->assertOk();

    $response->assertInertia(
        fn (AssertableInertia $page) => $page->component('Notifications/Show')
            ->has(
                'notification',
                fn (AssertableInertia $prop) => $prop->where('protocol', $notification->protocol)
                    ->etc()
            )
    );
});
