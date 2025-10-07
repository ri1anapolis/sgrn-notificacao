<?php

namespace Database\Factories;

use App\Models\Other;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Other>
 */
class OtherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Other::class;

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
            'guarantee_property_registration' => $this->faker->optional()->numerify('REG-#####-##'),
            'guarantee_property_address' => $this->faker->optional()->address,
            'contract_registration_act' => $this->faker->optional()->numerify('ACT-###'),
            'emoluments_intimation' => $this->faker->optional()->numerify('EMOL-#####'),
            'contract_number' => $this->faker->optional()->numerify('CONTRACT-####'),
            'contract_date' => $this->faker->optional()->date(),
            'total_amount_debt' => $this->faker->optional()->numberBetween(10000, 100000),
            'debt_position_date' => $this->faker->optional()->date(),
            'default_period' => $this->faker->optional()->randomElement(['15 days', '30 days', '45 days']),
            'grace_period' => $this->faker->boolean,
            'contractual_clause' => $this->faker->optional()->paragraph,
            'real_estate_registry_location' => $this->faker->optional()->city,
        ];
    }
}
