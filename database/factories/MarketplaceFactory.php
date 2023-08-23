<?php

namespace Database\Factories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Marketplace>
 */
class MarketplaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' =>"". $this->faker->word." " .
            $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'descricao' =>"". $this->faker->word." " .
            $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'url' => $this->faker->url()            
            //
        ];
    }
}
