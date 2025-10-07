<?php

namespace Database\Factories;

use App\Models\AdversePossession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdversePossession>
 */
class AdversePossessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdversePossession::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'office' => $this->faker->optional()->numberBetween(1, 10),
            'adverse_possession_property_registration' => $this->faker->numerify('REG-#####-##'),
            'adverse_possession_property_identification' => $this->faker->address,
            'adverse_possession_property_registry_office' => $this->faker->city,
        ];
    }
}
