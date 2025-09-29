<?php

use App\Enums\UserRole;
use App\Models\Address;
use App\Models\DiligenceResult;
use App\Models\Notification;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('should display the diligence page', function () {
    $this->user = User::factory()->create(['role' => UserRole::Admin]);

    $this->notification = Notification::factory()
        ->has(Address::factory(), 'addresses')
        ->create();

    $this->address = $this->notification->addresses->first();

    $this->diligenceResults = DiligenceResult::factory()->count(3)->create();

    actingAs($this->user);
    $response = get(route('notifications.diligence.show', [
        'notification' => $this->notification,
        'address' => $this->address,
    ]));

    $response->assertOk();

    $response->assertInertia(
        fn (AssertableInertia $page) => $page
            ->component('Notifications/Diligence/Show')
            ->where('notification.id', $this->notification->id)
            ->where('address.id', $this->address->id)
            ->has('diligenceResults', $this->diligenceResults->count())
    );
});
