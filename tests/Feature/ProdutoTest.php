<?php

namespace Tests\Feature;

use App\Models\Produto;
use App\Models\Tipo;
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
      
          // Criar um tipo usando o factory
          $tipo = Tipo::factory()->create();

          //Criar o objeto
          $data = [
              'nome' => "" . $this->faker->word . " " .
                  $this->faker->numberBetween($int1 = 0, $int2 = 99999),
              'descricao' => $this->faker->sentence(),
              'preco' => $this->faker->randomFloat(2, 10, 1000),
              'estoque' => $this->faker->numberBetween($int1 = 0, $int2 = 99999),
              'tipo_id' => $tipo->id
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

     /**
     * Teste de criação com falhas
     *
     * @return void
     */
    public function testCriacaoProdutosFalha()
    {
        $data = [
            'nome' => 'a',
            'descricao' =>'a',
            'preco' => '',
            'estoque' => '',
            'tipo_id' => '',
        ];
         // Fazer uma requisição POST
        $response = $this->postJson('/api/produtos', $data);

        // Verifique se teve um retorno 422 - Falha no salvamento
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(422)// se quero falha é essa resposta qie eu quero
            ->assertJsonValidationErrors(['descricao']); // validacao nesse campo
    }

     /**
     * Teste de pesquisa de registro
     *
     * @return void
     */
    public function testPesquisaProdutosSucesso()
    {
        // Criar um Produto e Tipo
        $produto = Produto::factory()->create();
        
        
        // Fazer pesquisa
        $response = $this->getJson('/api/produtos/' . $produto->id);   
       
        // Verificar saida //no show(controller)
        $response->assertStatus(200)
            ->assertJson([
                'id'=> $produto->id,
                'nome' => $produto->nome,
                'descricao' => $produto->descricao,                
                'preco' => $produto->preco,                
                'estoque' => $produto->estoque                
                              
            ]);
    }

    /**
     * Teste de pesquisa de registro com falha
     *
     * @return void
     */
    public function testPesquisaProdutosComFalha()
    {
        // Fazer pesquisa com um id inexistente
        $response = $this->getJson('/api/produtos/999'); // o 999 nao pode existir

        // Veriicar a resposta
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Produto não encontrado'
            ]);
    }
    
}
