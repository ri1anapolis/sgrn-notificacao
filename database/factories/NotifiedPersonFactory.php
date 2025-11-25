<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\NotifiedPerson;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotifiedPersonFactory extends Factory
{
    protected $model = NotifiedPerson::class;

    public function definition(): array
    {
        return [
            'notification_id' => Notification::factory(),
            'name' => $this->faker->name,
            'document' => $this->faker->numerify('###.###.###-##'),
            'gender' => $this->faker->randomElement(['masculine', 'feminine']),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
