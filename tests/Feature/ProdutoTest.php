<?php

namespace Tests\Feature;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function Laravel\Prompts\error;

class ProdutoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase, WithFaker;

    /**Listar todos os tipos
     * @return void
     */

    public function testListarTodosProdutos()
    {
         //Criar 5 tipos
        //Salvar Temporario
        Produto::factory()->count(5)->create();

        // usar metodo GET para verificar o retorno
        
        $response = $this->getJson('/api/produtos');

        //Testar ou verificar saida
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['nome','descricao','preco', 'estoque','tipo_id', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function testCriarProdutosSucesso(){
        $produto = Produto::factory()->create();
        //Criar o objeto
        $data = [
            "nome" => $this->faker->word,
            "descricao" => $this->faker->word,
            "preco" => $this->faker->word,
            "estoque" => $this->faker->word,
            "tipo_id" => $this->faker->word,
            
        ];

        //Debug
        //dd($data);

        // Fazer uma requisição POST
        $response = $this->postJson('/api/produtos', $data);

        //dd($response);

        // Verifique se teve um retorno 201 - Criado com Sucesso
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(201)
            ->assertJsonStructure(['nome','descricao','preco', 'estoque','tipo_id', 'created_at', 'updated_at']);
    }
}
