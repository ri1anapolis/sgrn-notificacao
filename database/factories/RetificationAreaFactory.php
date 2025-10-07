<?php

namespace Database\Factories;

use App\Models\RetificationArea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RetificationArea>
 */
class RetificationAreaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RetificationArea::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'office' => $this->faker->optional()->numberBetween(1, 10),
            'rectifying_property_registration' => $this->faker->optional()->numerify('REG-#####-##'),
            'rectifying_property_identification' => $this->faker->optional()->address,
            'rectifying_property_registry_office' => $this->faker->optional()->city,
        ];
    }
}
