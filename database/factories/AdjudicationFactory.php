<?php

namespace Database\Factories;

use App\Models\Adjudication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Adjudication>
 */
class AdjudicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Adjudication::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'office' => $this->faker->optional()->numberBetween(1, 10),
            'adjudicated_property_registration' => $this->faker->optional()->numerify('REG-#####-##'),
            'adjudicated_property_identification' => $this->faker->optional()->address,
            'adjudicated_property_registry_office' => $this->faker->optional()->city,
        ];
    }
}
