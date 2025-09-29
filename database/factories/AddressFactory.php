<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Notification;
use App\Models\NotifiedPerson;
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

    public function configure()
    {
        return $this->afterCreating(function (Address $address) {
            $notifiedPeople = NotifiedPerson::factory()->count(2)->create([
                'notification_id' => $address->notification_id,
            ]);

            $address->notifiedPeople()->attach($notifiedPeople);
        });
    }
}
