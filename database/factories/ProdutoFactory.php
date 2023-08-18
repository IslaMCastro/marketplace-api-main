<?php

namespace Database\Factories;

use App\Models\Tipo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
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
            'nome' => "" . $this->faker->word . " " .
                $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'descricao' => $this->faker->sentence(),
            'preco' => $this->faker->randomFloat(2, 10, 1000),
            'estoque' => $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'tipo_id' => function () {
                return Tipo::factory()->create()->id;
            }


        ];
    }
}