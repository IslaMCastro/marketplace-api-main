<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero' => $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'data' => $this->faker->date(),
            'status' => $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'total' => $this->faker->randomFloat(2, 10, 1000),
            //
        ];
    }
}
