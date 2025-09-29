<?php

use App\Enums\UserRole;
use App\Models\Address;
use App\Models\DiligenceResult;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Carbon;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => UserRole::Admin]);

    $this->notification = Notification::factory()
        ->has(Address::factory(), 'addresses')
        ->create();

    $this->address = $this->notification->addresses->first();

    $this->diligenceResults = DiligenceResult::factory()->count(3)->create();

    actingAs($this->user);
});

it('should store a new diligence', function () {
    $diligenceData = [
        'visit_number' => 1,
        'diligence_result_id' => $this->diligenceResults->first()->id,
        'observations' => 'Nenhuma observação.',
        'date' => Carbon::now()->format('Y-m-d'),
    ];

    $response = post(route('notifications.diligence.store', [
        'notification' => $this->notification,
        'address' => $this->address,
    ]), $diligenceData);

    $response->assertRedirectToRoute('notifications.diligence.show', [
        'notification' => $this->notification,
        'address' => $this->address,
    ]);

    $this->assertDatabaseHas('diligences', [
        'address_id' => $this->address->id,
        'user_id' => $this->user->id,
        'visit_number' => $diligenceData['visit_number'],
        'diligence_result_id' => $diligenceData['diligence_result_id'],
        'observations' => $diligenceData['observations'],
        'date' => $diligenceData['date'],
    ]);
});

it('should fail validation if visit_number is missing', function ($visitNumber) {
    post(route('notifications.diligence.store', [
        'notification' => $this->notification,
        'address' => $this->address,
    ]), ['visit_number' => $visitNumber])
        ->assertSessionHasErrors('visit_number');
})->with([
    'null' => null,
    'string' => 'abc',
    'zero' => 0,
    'greater than max' => 4,
]);

it('should fail validation if date is missing or has an invalid format', function ($date) {
    post(route('notifications.diligence.store', [
        'notification' => $this->notification,
        'address' => $this->address,
    ]), ['date' => $date])
        ->assertSessionHasErrors('date');
})->with([
    'null' => null,
]);
