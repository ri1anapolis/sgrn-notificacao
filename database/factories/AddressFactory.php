<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'notification_id' => Notification::factory(),
            'address' => $this->faker->address,
        ];
    }
}
