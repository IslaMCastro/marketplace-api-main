<?php

namespace Tests\Feature;


use App\Models\marketplace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testListarTodosMarketplaces()
    {
        //Criar 5 marketplace
        //Salvar Temporario
        Marketplace::factory()->count(5)->create();

        // usar metodo GET para verificar o retorno
        $response = $this->getJson('/api/marketplaces');

        //Testar ou verificar saida
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['nome','descricao','url', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function testCriarMarketplaceSucesso(){
      
        // Criar um tipo usando o factory
        $tipo = Marketplace::factory()->create();

        //Criar o objeto
        $data = [
            'nome' => "" . $this->faker->word . " " .
                $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'descricao' => $this->faker->sentence(),
            'url' => "" . $this->faker->word . " " .
                $this->faker->numberBetween($int1 = 0, $int2 = 99999),

        ];


        // Fazer uma requisição POST
        $response = $this->postJson('/api/marketplaces', $data);

        //dd($response);

        // Verifique se teve um retorno 201 - Criado com Sucesso
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'nome', 'descricao', 'url', 'created_at', 'updated_at']);
    }

  public function testCriacaoMarketplaceFalha()
    
    {
        $data = [
            'nome' => 'a',
            'descricao' =>'a',
            'url' => '',            
            'marketplace_id' => '',
        ];
         // Fazer uma requisição POST
        $response = $this->postJson('/api/marketplaces', $data);

        // Verifique se teve um retorno 422 - Falha no salvamento
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(422)// se quero falha é essa resposta qie eu quero
            ->assertJsonValidationErrors(['descricao']); // validacao nesse campo
    }

    public function testPesquisaMarketplacesSucesso()
    {
        // Criar um tipo
        $marketplace = Marketplace::factory()->create();

        // Fazer pesquisa
        $response = $this->getJson('/api/marketplaces/' . $marketplace->id);

        // Verificar saida
        $response->assertStatus(200)
            ->assertJson([
                'id' => $marketplace->id,
                'nome' => $marketplace->nome,
                'descricao' => $marketplace->descricao,
                'url' => $marketplace->url,
                
                
            ]);
     }   
   
   
    public function testUpdateMarketpalceSucesso()
    {
        // Crie um marketplace fake
        $marketplace = Marketplace::factory()->create();

        // Dados para update
        $newData = [
            'nome' => 'New name',
            'descricao' => 'New name',
            'url' => 'new url',
           

        ];

        // Faça uma chamada PUT
        $response = $this->putJson('/api/marketplaces/' . $marketplace->id, $newData);

        // Verifique a resposta
        $response->assertStatus(200)
            ->assertJson([
                'id' => $marketplace->id,
                'nome' => 'New name',
                'descricao' => 'New name',
                'url' => 'new url',
               
            ]);
    }

    public function testUpdateMarketplacesDataInvalida()
    {
        // Crie um tipo falso
        $marketplace = Marketplace::factory()->create();

        // Crie dados falhos
        $invalidData = [ //data=dados (lembrando que para criar uma variavel usa o $ na frente)
            'nome' => '', // Invalido: Descricao vazio
            'descricao' => '', // Invalido: Descricao vazio
            'url' => '', // Invalido: Descricao vazio         
            
        ];

        // faça uma chamada PUT
        $response = $this->putJson('/api/marketplaces/' . $marketplace->id, $invalidData);

        // Verificar se teve um erro 422
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['descricao']);// erro nessa informação
    }

    public function testUpdateMarketplaceNaoExistente()
    {

        // Criar um tipo usando o factory
        $marketplace = Marketplace::factory()->create();


        // Dados para update
        $newData = [ 
            'nome' => 'New name',
            'descricao' => 'New name',
            'url' => 'new url',
           

        ];
        // Faça uma chamada PUT
        $response = $this->putJson('/api/marketplaces/9999', $newData);

        // Verificar o retorno 404
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'marketplace não encontrado'
            ]);
    }

    public function testUpdateMarketplaceMesmoNome()
    {
        // Crie um tipo fake
        $marketplace = Marketplace::factory()->create();

        // Data para update
        $sameData = [
            'nome' => $marketplace->nome,
            'descricao' => $marketplace->descricao,
            'url' => $marketplace->url,           
            
        ];

        // Faça uma chamada PUT
        $response = $this->putJson('/api/marketplaces/' . $marketplace->id, $sameData);

        // Verifique a resposta
        $response->assertStatus(200)
            ->assertJson([
                'id' => $marketplace->id,
                'nome' => $marketplace->nome,
                'descricao' => $marketplace->descricao,
                'url' => $marketplace->url,           
                
            ]);
    }

    public function testUpdateMarketplaceNomeDuplicado()
    {
        // Crie um tipo fake
        $marketplace = Marketplace::factory()->create();
        $atualizar = Marketplace::factory()->create();

        // Data para update
        $sameData = [
            'nome' => $marketplace->nome,
            'descricao' => $marketplace->descricao,
            'url' => $marketplace->url,           
            
        ];

        // Faça uma chamada PUT
        $response = $this->putJson('/api/marketplaces/' . $atualizar->id, $sameData);

        // Verifique a resposta
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome']);
    }

    public function testDeletemarketplace()
    {
        // Criar marketplace fake
        $marketplace = Marketplace::factory()->create();

        // enviar requisição para Delete
        $response = $this->deleteJson('/api/marketplaces/' . $marketplace->id);

        // Verifica o Delete
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'marketplace deletado com sucesso!'
            ]);

        //Verifique se foi deletado do banco
        $this->assertDatabaseMissing('marketplaces', ['id' => $marketplace->id]);
    }

    public function testDeleteMarketplaceNaoExistente()
    {
        // enviar requisição para Delete
        $response = $this->deleteJson('/api/marketplaces/999');

        // Verifique a resposta
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'marketplace não encontrado!'
            ]);
    }

}