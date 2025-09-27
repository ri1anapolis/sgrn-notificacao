<?php

namespace Database\Factories;

use App\Models\DiligenceResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DiligenceResultFactory extends Factory
{
    protected $model = DiligenceResult::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'group' => 'Devedor Presente – Notificação Realizada Com Sucesso',
            'code' => $this->faker->unique()->slug,
            'description' => $this->faker->sentence,
        ];
    }
}
