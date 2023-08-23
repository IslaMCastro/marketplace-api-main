<?php

namespace Tests\Feature;

use App\Models\Marketplace;
use App\Models\Produto;
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
                    '*' => ['nome','descricao','url', 'produto_id', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function testCriarMarketplacesSucesso(){
      
        // Criar um tipo usando o factory
        $produto = Produto::factory()->create();

        //Criar o objeto
        $data = [
            'nome' =>"". $this->faker->word." " .
            $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'descricao' =>"". $this->faker->word." " .
            $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'url' => $this->faker->url(),
            'produto_id' => $produto->id
        ];


      //Debug
      //dd($data);

      // Fazer uma requisição POST
      $response = $this->postJson('/api/marketplaces', $data);

      //dd($response);

      // Verifique se teve um retorno 201 - Criado com Sucesso
      // e se a estrutura do JSON Corresponde
      $response->assertStatus(201)
          ->assertJsonStructure(['nome','descricao','url','produto_id', 'created_at', 'updated_at']);
  }

  public function testCriacaoMarketplacesFalha()
    
    {
        $data = [
            'nome' => 'a',
            'descricao' =>'a',
            'url' => '',            
            'produto_id' => '',
        ];
         // Fazer uma requisição POST
        $response = $this->postJson('/api/marketplaces', $data);

        // Verifique se teve um retorno 422 - Falha no salvamento
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(422)// se quero falha é essa resposta qie eu quero
            ->assertJsonValidationErrors(['descricao']); // validacao nesse campo
    }

    

   



}
