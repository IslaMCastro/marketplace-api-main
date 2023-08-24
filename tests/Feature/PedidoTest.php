<?php

namespace Tests\Feature;

use App\Models\Pedido;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PedidoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**Listar todos os pedidos
     * @return void
     */

    public function testListarTodosPedidos()
    {
         //Criar 5 pedidos
        //Salvar Temporario
        Pedido::factory()->count(5)->create();

        // usar metodo GET para verificar o retorno
        
        $response = $this->getJson('/api/pedidos');

        //Testar ou verificar saida
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['numero','data','status', 'total', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function testCriarPedidoSucesso(){
      
          // Criar um pedido usando o factory
          $pedido = Pedido::factory()->create();

          //Criar o objeto
          $data = [
            'numero' => $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'data' => $this->faker->date(),
            'status' => $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'total' => $this->faker->randomFloat(2, 10, 1000),
          ];
  

        //Debug
        //dd($data);

        // Fazer uma requisição POST
        $response = $this->postJson('/api/pedidos', $data);

        //dd($response);

        // Verifique se teve um retorno 201 - Criado com Sucesso
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(201)
            ->assertJsonStructure(['numero','data','status', 'total', 'created_at', 'updated_at']);
    }

     /**
     * Teste de criação com falhas
     *
     * @return void
     */
    public function testCriacaoPedidoFalha()
    
    {
        $data = [
            'numero' => "a",
            'data' => "",
            'status' => "",
            'total' => "",
        ];
        // Fazer uma requisição POST
        $response = $this->postJson('/api/pedidos/', $data);

        // Verifique se teve um retorno 422 - Falha no salvamento
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['numero', 'data', 'status', 'total']);
    
    }

     /**
     * Teste de pesquisa de registro
     *
     * @return void
     */
    public function testPesquisaPedidoSucesso()
    {
          // Criar um Pedido
          $pedido = Pedido::factory()->create();


          // Fazer pesquisa
          $response = $this->getJson('/api/pedidos/' . $pedido->id);
  
          // Verificar saida
          $response->assertStatus(200)
              ->assertJson([
                  'id' => $pedido->id,
                  'numero' => $pedido->numero,
                  'data' => $pedido->data,
                  'status' => $pedido->status,
                  'total' => $pedido->total,
              ]);
      }
     /* Teste de pesquisa de registro com falha
     *
     * @return void
     */
    public function testPesquisaPedidoComFalha()
    {
        // Fazer pesquisa com um id inexistente
        $response = $this->getJson('/api/pedidos/999'); // o 999 nao pode existir

        // Veriicar a resposta
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Pedido não encontrado'
            ]);
    }

    public function testUpdatePedidoSucesso()
    {
        // Crie um pedido fake
        $pedido = Pedido::factory()->create();

        // Dados para update
        $newData = [
            'numero' => 00023,
            'data'=> '2013-08-24',
            'status'=> 000,
            'total'=> 12.100,
        ];

        // Faça uma chamada PUT
        $response = $this->putJson('/api/pedidos/' . $pedido->id, $newData);

        // Verifique a resposta
        $response->assertStatus(200)
            ->assertJson([
                'id' => $pedido->id,
                'numero' => 00023,
                'data' => '2013-08-24',
                'status'=> 000,
                'total'=> 12.100,
            ]);
    }
    
    
    public function testUpdatePedidoDataInvalida()
    {
        // Crie um pedido falso
        $pedido = Pedido::factory()->create();

        // Crie dados falhos
        $invalidData = [ //data=dados (lembrando que para criar uma variavel usa o $ na frente)
            'numero' => 'a',
            'data'=> '',
            'status'=> '',
            'total'=> '',
        ];

        // faça uma chamada PUT
        $response = $this->putJson('/api/pedidos/' . $pedido->id, $invalidData);

        // Verificar se teve um erro 422
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['numero', 'data', 'status', 'total']);// erro nessa informação
    }

    public function testUpdatePedidoNaoExistente()
    {

        // Criar um pedido usando o factory
        $pedido = Pedido::factory()->create();


        // Dados para update
        $newData = [             
            'numero' => 00023,
            'data'=> '2013-08-24',
            'status'=> 000,
            'total'=> 12.100,

        ];
        // Faça uma chamada PUT
        $response = $this->putJson('/api/pedidos/9999', $newData);

        // Verificar o retorno 404
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Pedido não encontrado'
            ]);
    }

    public function testUpdatePedidoMesmoNumero()
    {
        // Crie um pedido fake
        $pedido = Pedido::factory()->create();

        // Data para update
        $sameData = [
            'numero' => $pedido->numero,
            'data'=> $pedido->data,
            'status'=> $pedido->status,
            'total'=> $pedido->total,
           
        ];

        // Faça uma chamada PUT
        $response = $this->putJson('/api/pedidos/' . $pedido->id, $sameData);

        // Verifique a resposta
        $response->assertStatus(200)
            ->assertJson([
                'id' => $pedido->id,
                'numero' => $pedido->numero,
                'data'=> $pedido->data,
                'status'=> $pedido->status,
                'total'=> $pedido->total,
            ]);
    }

    public function testUpdatePedidoNumeroDuplicado()
    {
        // Crie um pedido fake
        $pedidoExistente = Pedido::factory()->create();
        $pedidoUpdate = Pedido::factory()->create();

        // Data para update
        $sameData = [
            'numero' => $pedidoExistente->numero,
            'data'=> $pedidoExistente->data,
            'status'=> $pedidoExistente->status,
            'total'=> $pedidoExistente->total,
        ];

        // Faça uma chamada PUT
        $response = $this->putJson('/api/pedidos/' . $pedidoUpdate->id, $sameData);

        // Verifique a resposta
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['numero']);
    }

    public function testDeletePedido()
    {
        // Criar pedido fake
        $pedido = Pedido::factory()->create();

        // enviar requisição para Delete
        $response = $this->deleteJson('/api/pedidos/' . $pedido->id);

        // Verifica o Delete
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Pedido deletado com sucesso!'
            ]);

        //Verifique se foi deletado do banco
        $this->assertDatabaseMissing('pedidos', ['id' => $pedido->id]);
    }

    public function testDeletePedidoNaoExistente()
    {
        // enviar requisição para Delete
        $response = $this->deleteJson('/api/pedidos/999');

        // Verifique a resposta
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Pedido não encontrado!'
            ]);
    }
}
