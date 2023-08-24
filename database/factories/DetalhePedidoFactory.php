<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetalhePedido>
 */
class DetalhePedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pedido_id'=> function(){
                return Pedido::factory()->create()->id;
            },
            'produto_id'=> function(){
                return Produto::factory()->create()->id;
            },
            'quantidade'=> $this->faker->numberBetween($int1 = 0, $int2 = 90000),
            'preco'=> $this->faker->numberBetween($int1 = 0, $int2 = 90000),
            'total'=> $this->faker->numberBetween($int1 = 0, $int2 = 90000),


            //
        ];
    }
}
