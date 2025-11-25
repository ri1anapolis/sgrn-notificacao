<?php

namespace Database\Factories;

use App\Models\AlienationMovableProperty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AlienationMovableProperty>
 */
class AlienationMovablePropertyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AlienationMovableProperty::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'creditor' => $this->faker->company,
            'office' => $this->faker->optional()->numberBetween(1, 10),
            'guarantee_movable_property_description' => $this->faker->optional()->sentence,
            'contract_registry_data' => $this->faker->optional()->numerify('CRD-#####'),
            'emoluments_intimation' => $this->faker->optional()->numerify('EMOL-#####'),
            'contract_number' => $this->faker->optional()->numerify('CONTRACT-####'),
            'contract_date' => $this->faker->optional()->date(),
            'total_amount_debt' => $this->faker->optional()->numberBetween(10000, 100000),
            'debt_position_date' => $this->faker->optional()->date(),
            'default_period' => $this->faker->optional()->randomElement(['15 days', '30 days', '45 days']),
            'grace_period' => $this->faker->boolean,
            'contractual_clause' => $this->faker->optional()->paragraph,
            'contract_registry_office' => $this->faker->optional()->city,
        ];
    }
}
