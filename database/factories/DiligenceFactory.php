<?php

namespace Database\Factories;

use App\Enums\DiligenceResult;
use App\Models\Address;
use App\Models\Diligence;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiligenceFactory extends Factory
{
    protected $model = Diligence::class;

    public function definition(): array
    {
        return [
            'address_id' => Address::factory(),
            'user_id' => User::factory(),
            'visit_number' => $this->faker->numberBetween(1, 3),
            'diligence_result' => $this->faker->randomElement([DiligenceResult::NotFound->value]),
            'observations' => $this->faker->optional()->sentence(),
            'date' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
