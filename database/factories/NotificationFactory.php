<?php

namespace Database\Factories;

use App\Enums\NotificationStatus;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'protocol' => $this->faker->uuid,
            'nature' => $this->faker->name(),
            'status' => $this->faker->randomElement([
                NotificationStatus::InProgress->value,
                NotificationStatus::Completed->value,
            ]),
        ];
    }
}
