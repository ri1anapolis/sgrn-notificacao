<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\NotifiedPerson;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'notified_person_id' => NotifiedPerson::factory(),
            'street' => $this->faker->streetName,
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->optional()->text(20),
            'city' => $this->faker->city,
            'zip_code' => $this->faker->postcode,
        ];
    }
}
