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
         //Criar 5 Pedidos
         //Salvar Temporario
         Pedido::factory()->count(5)->create();
 
         // usar metodo GET para verificar o retorno
         $response = $this->getJson('/api/pedidos/');
 
         //Testar ou verificar saida
         $response->assertStatus(200)
             ->assertJsonCount(5, 'data')
             ->assertJsonStructure([
                 'data' => [
                     '*' => ['id', 'numero', 'data', 'status', 'total', 'created_at', 'updated_at']
                 ]
             ]);
     }
 
     /**
      * Criar um Pedido
      */
     public function testCriarPedidosucesso()
     {
 
         //Criar o objeto
         $data = [
             'numero' => $this->faker->numberBetween($min = 1, $max = 9000),
             'data' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
             'status' => $this->faker->numberBetween($min = 1, $max = 5),
             'total' => $this->faker->numberBetween($min = 1, $max = 9000),
         ];
 
         //Debug
         //dd($data);
 
         // Fazer uma requisição POST
         $response = $this->postJson('/api/pedidos/', $data);
 
         //dd($response);
 
         // Verifique se teve um retorno 201 - Criado com Sucesso
         // e se a estrutura do JSON Corresponde
         $response->assertStatus(201)
             ->assertJsonStructure(['id', 'numero', 'data', 'status', 'total', 'created_at', 'updated_at']);
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
     public function testPesquisaPedidosucesso()
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
 
 
     /**
      * Teste de pesquisa de registro com falha
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
 
     /**
      *Teste de upgrade com sucesso
      *
      * @return void
      */
     public function testUpdatePedidosucesso()
     {
         // Crie um Pedido fake
         $pedido = Pedido::factory()->create();
 
         // Dados para update
         $newData = [
             'numero' => 9,
             'data' => "2023-08-24",
             'status' => 1,
             'total' => 150,
         ];
 
         // Faça uma chamada PUT
         $response = $this->putJson('/api/pedidos/' . $pedido->id, $newData);
 
         // Verifique a resposta
         $response->assertStatus(200)
             ->assertJson([
                 'id' => $pedido->id,
                 'numero' => 9,
                 'data' => "2023-08-24",
                 'status' => 1,
                 'total' => 150,
             ]);
     }
 
     /**
      * Testando com falhas
      *
      * @return void
      */
     public function testUpdatePedidoDataInvalida()
     {
         // Crie um Pedido falso
         $pedido = Pedido::factory()->create();
 
         // Crie dados falhos
         $invalidData = [
             'numero' => "a",
             'data' => "",
             'status' => "",
             'total' => "",
         ];
 
         // faça uma chamada PUT
         $response = $this->putJson('/api/pedidos/' . $pedido->id, $invalidData);
 
         // Verificar se teve um erro 422
         $response->assertStatus(422)
             ->assertJsonValidationErrors(['numero', 'data', 'status', 'total']);
     }
 
     /**
      * Teste update de Pedido
      *
      * @return void
      */
     public function testUpdatePedidoNaoExistente()
     {
         //criar dados
 
         // Dados para update
         $newData = [
             'numero' => 9,
             'data' => "2023-08-24",
             'status' => 1,
             'total' => 150,
         ];
 
         // Faça uma chamada para um id falho
         $response = $this->putJson('/api/pedidos/999', $newData); //O 999 não deve existir
 
         // Verificar o retorno 404
         $response->assertStatus(404)
             ->assertJson([
                 'message' => 'Pedido não encontrado'
             ]);
     }
 
     /**
      * Teste de upgrade com os mesmos dados
      *
      * @return void
      */
     public function testUpdatePedidoMesmosDados()
     {
         // Crie um Pedido fake
         $pedido = Pedido::factory()->create();
 
         // Data para update
         $sameData = [
             'numero' => $pedido->numero,
             'data' => $pedido->data,
             'status' => $pedido->status,
             'total' => $pedido->total,
         ];
 
         // Faça uma chamada PUT
         $response = $this->putJson('/api/pedidos/' . $pedido->id, $sameData);
 
         // Verifique a resposta
         $response->assertStatus(200)
             ->assertJson([
                 'id' => $pedido->id,
                 'numero' => $pedido->numero,
                 'data' => $pedido->data,
                 'status' => $pedido->status,
                 'total' => $pedido->total,
             ]);
     }
 
     /**
      * Teste upgrade com nome duplicado
      *
      * @return void
      */
     public function testUpdatePedidoNumeroDuplicada()
     {
         // Crie dois Pedidos fakes
         $pedidoExistente = Pedido::factory()->create();
         $pedidoUpgrade = Pedido::factory()->create();
 
         // Para para upgrade
         $newData = [
             'numero' => $pedidoExistente->numero,
             'data' => $pedidoExistente->data,
             'status' => $pedidoExistente->status,
             'total' => $pedidoExistente->total,
         ];
 
         // Faça o put 
         $response = $this->putJson('/api/pedidos/' . $pedidoUpgrade->id, $newData);
 
         // Verifique a resposta
         $response->assertStatus(422)
             ->assertJsonValidationErrors(['numero']);
     }
 
 
     /**
      * Teste de deletar com sucesso
      *
      * @return void
      */
     public function testDeletePedido()
     {
         // Criar Pedido fake
         $pedido = Pedido::factory()->create();
 
         // enviar requisição para Delete
         $response = $this->deleteJson('/api/pedidos/' . $pedido->id);
 
         // Verifica o Detele
         $response->assertStatus(200)
             ->assertJson([
                 'message' => 'Pedido deletado com sucesso!'
             ]);
 
         //Verifique se foi deletado do banco
         $this->assertDatabaseMissing('Pedidos', ['id' => $pedido->id]);
     }
 
     /**
      * Teste remoção de registro inexistente
      *
      * @return void
      */
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