<?php

namespace Database\Factories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Avaliacao>
 */
class AvaliacaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'descricao' => $this->faker->sentence(),
            'nota' => $this->faker->randomFloat(2, 0, 10),
            'produto_id' => function () {
                return Produto::factory()->create()->id;
            }
          ];
    }
}
