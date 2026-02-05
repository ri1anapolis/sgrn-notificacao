<?php

use App\Models\Address;
use App\Models\AlienationRealEstate;
use App\Models\Diligence;
use App\Models\DiligenceResult;
use App\Models\Notification;
use App\Models\NotifiedPerson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

describe('Notification Closing Action', function () {
    it('can close a notification with successful diligence', function () {
        $user = User::factory()->create();
        actingAs($user);

        $alienation = AlienationRealEstate::factory()->create();
        $notification = Notification::factory()
            ->for($alienation, 'notifiable')
            ->has(NotifiedPerson::factory()->count(1))
            ->create();

        $address = Address::factory()->for($notification)->create();

        $successResult = DiligenceResult::factory()->create([
            'code' => 'devedor_presente_assinou',
            'group' => 'Devedor Presente - Notificação Realizada Com Sucesso',
            'description' => '[SUCESSO] Devedor presente e assinou',
        ]);

        Diligence::factory()->for($address)->for($successResult, 'diligenceResult')->create([
            'visit_number' => 1,
            'date' => now(),
        ]);

        expect($notification->fresh()->is_closed)->toBeFalse();

        $response = post(route('notifications.close', $notification));

        $response->assertRedirect();

        expect($notification->fresh()->is_closed)->toBeTrue();
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_closed' => true,
        ]);
    });

    it('cannot close a notification without successful diligence', function () {
        $user = User::factory()->create();
        actingAs($user);

        $alienation = AlienationRealEstate::factory()->create();
        $notification = Notification::factory()
            ->for($alienation, 'notifiable')
            ->has(NotifiedPerson::factory()->count(1))
            ->create();

        $address = Address::factory()->for($notification)->create();

        $failureResult = DiligenceResult::factory()->create([
            'code' => 'ausente',
            'group' => 'Devedor Ausente',
            'description' => '[AUSENTE] Ninguém foi encontrado',
        ]);

        Diligence::factory()->for($address)->for($failureResult, 'diligenceResult')->create([
            'visit_number' => 1,
            'date' => now(),
        ]);

        expect($notification->fresh()->is_closed)->toBeFalse();

        $response = post(route('notifications.close', $notification));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        expect($notification->fresh()->is_closed)->toBeFalse();
    });

});
