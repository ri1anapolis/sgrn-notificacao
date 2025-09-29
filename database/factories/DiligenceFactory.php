<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Diligence;
use App\Models\DiligenceResult;
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
            'visit_number' => 1,
            'diligence_result_id' => DiligenceResult::factory(),
            'observations' => $this->faker->optional()->sentence(),
            'date' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
