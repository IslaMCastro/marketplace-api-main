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
            'numero' => $this->faker->numberBetween($min = 1, $max = 9000),
            'data' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'status' => $this->faker->numberBetween($min = 1, $max = 5),
            'total' => $this->faker->numberBetween($min = 1, $max = 9000),
            //
        ];
    }
}
