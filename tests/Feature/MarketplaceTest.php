<?php

namespace Tests\Feature;

use App\Models\Marketplace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testListarTodosMarketplaces()
    {
        //Criar 5 Avaliacaos
        //Salvar Temporario
        Marketplace::factory()->count(5)->create();

        // usar metodo GET para verificar o retorno
        $response = $this->getJson('/api/marketplaces');

        //Testar ou verificar saida
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'nome','descricao','url', 'produto_id', 'created_at', 'updated_at']
                ]
            ]);
    }
   


    
}
