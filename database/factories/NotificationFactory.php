<?php

namespace Database\Factories;

use App\Enums\NotificationStatus;
use App\Models\Adjudication;
use App\Models\AdversePossession;
use App\Models\AlienationMovableProperty;
use App\Models\AlienationRealEstate;
use App\Models\Notification;
use App\Models\Other;
use App\Models\PurchaseAndSaleIncorporation;
use App\Models\PurchaseAndSaleSubdivision;
use App\Models\RetificationArea;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        $notifiableTypes = [
            Adjudication::class,
            AdversePossession::class,
            AlienationMovableProperty::class,
            AlienationRealEstate::class,
            Other::class,
            PurchaseAndSaleIncorporation::class,
            PurchaseAndSaleSubdivision::class,
            RetificationArea::class,
            null,
        ];

        $notifiableType = $this->faker->randomElement($notifiableTypes);

        $notifiable = $notifiableType::factory()->create();

        return [
            'protocol' => $this->faker->unique()->numerify('######'),
            'status' => $this->faker->randomElement(NotificationStatus::class),
            'notifiable_id' => $notifiable->id,
            'notifiable_type' => $notifiableType,
        ];
    }

    public function forNotifiable(Model $model): static
    {
        return $this->state(fn () => [
            'notifiable_id' => $model->getKey(),
            'notifiable_type' => $model::class,
        ]);
    }
}
