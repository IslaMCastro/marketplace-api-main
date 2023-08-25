<?php

namespace Tests\Feature;

use App\Models\DetalhePedido;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DetalhePedidoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testListarTodosDetalhePedidos()
    {

        //Criar 5 tipos
        //Salvar Temporario
        DetalhePedido::factory()->count(5)->create();


        // usar metodo GET para verificar o retorno

        $response = $this->getJson('/api/detalhepedidos');

        //Testar ou verificar saida
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['pedido_id', 'produto_id', 'quantidade', 'preco', 'total', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function testCriarDetalhePedidosSucesso()
    {

        // Criar um tipo usando o factory
        $pedido = Pedido::factory()->create();
        $produto = Produto::factory()->create();

        //Criar o objeto
        $data = [
            'pedido_id' => $pedido->id,
            'produto_id' => $produto->id,
            'quantidade' =>$this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'preco' => $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'total' => $this->faker->numberBetween($int1 = 0, $int2 = 99999),
        ];


        // Fazer uma requisição POST
        $response = $this->postJson('/api/detalhepedidos/', $data);

        //dd($response);

        // Verifique se teve um retorno 201 - Criado com Sucesso
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'pedido_id','produto_id','quantidade','preco','total', 'created_at', 'updated_at']);
    }

    public function testCriacaoDetalhePedidosFalha()
    {
        $data = [
            'pedido_id' => "",
            'produto_id' => "",
            'quantidade' =>"",
            'preco' => "",
            'total' => "",
        ];
        // Fazer uma requisição POST
        $response = $this->postJson('/api/detalhepedidos/', $data);

        // Verifique se teve um retorno 422 - Falha no salvamento
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['pedido_id','produto_id','quantidade','preco','total']);    }

    /**
     * Teste de pesquisa de registro
     *
     * @return void
     */
    public function testPesquisaDetalhePedidosSucesso()
    {
        // Criar um tipo
        $detalhePedido = DetalhePedido::factory()->create();

        // Fazer pesquisa
        $response = $this->getJson('/api/detalhepedidos/' . $detalhePedido->id);

        // Verificar saida
        $response->assertStatus(200)
            ->assertJson([
                'pedido_id' => $detalhePedido->pedido_id,
                'produto_id' => $detalhePedido->produto_id,
                'quantidade' => $detalhePedido->quantidade,
                'preco' => $detalhePedido->preco,
                'total' => $detalhePedido->total,
            ]);
    }


    /**
     * Teste de pesquisa de registro com falha
     *
     * @return void
     */
    public function testPesquisaDetalhePedidosComFalha()
    {
        // Fazer pesquisa com um id inexistente
        $response = $this->getJson('/api/detalhepedidos/999'); // o 999 nao pode existir

        // Veriicar a resposta
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Detalhe Pedido não encontrado!'
            ]);
    }

    /**
     *Teste de upgrade com sucesso
     *
     * @return void
     */
    public function testUpdateDetalhePedidosSucesso()
    {
        // Crie um produto fake
        $detalhePedido = DetalhePedido::factory()->create();

        // Dados para update
        $newData = [
            'pedido_id' => $detalhePedido->pedido_id,
            'produto_id' => $detalhePedido->produto_id,
            'quantidade' => 3,
            'preco' => 6,
            'total' => 18,

        ];

        // Faça uma chamada PUT
        $response = $this->putJson('/api/detalhepedidos/' . $detalhePedido->id, $newData);

        // Verifique a resposta
        $response->assertStatus(200)
            ->assertJson([
                'pedido_id' => $detalhePedido->pedido_id,
                'produto_id' => $detalhePedido->produto_id,
                'quantidade' => 3,
                'preco' => 6,
                'total' => 18,
            ]);
    }

    /**
     *Teste de upgrade com falhas
     *
     * @return void
     */
    public function testUpdateDetalhePedidosComFalhas()
    {
        // Crie um produto fake
        $detalhePedido = DetalhePedido::factory()->create();

        // Dados para update      
        $invalidData = [
            'pedido_id' => 9999,
            'produto_id' => 9999,            
            'quantidade' => "",
            'preco' => "",
            'total' => "", 

        ];
        // Faça uma chamada PUT
        $response = $this->putJson('/api/detalhepedidos/' . $detalhePedido->id, $invalidData);

        // Verificar se teve um erro 422
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['pedido_id','produto_id', 'quantidade','preco','total']);
    }

    /**
     * Teste update de produto
     *
     * @return void
     */
    public function testUpdateDetalhePedidosNaoExistente()
    {

        // Criar um tipo usando o factory
        $detalhePedido = DetalhePedido::factory()->create();


        // Dados para update
        $newData = [ 
            'pedido_id' => $detalhePedido->pedido_id,
            'produto_id' => $detalhePedido->produto_id,            
            'quantidade' => 3,
            'preco' => 12.50,
            'total' => 37.50, 

        ];
        // Faça uma chamada PUT
        $response = $this->putJson('/api/detalhepedidos/9999', $newData);

        // Verificar o retorno 404
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Detalhe Pedido não encontrado!'
            ]);
    }


    /**
     * Teste de upgrade com os mesmos nome
     *
     * @return void
     */
    public function testUpdateDetalhePedidosMesmoNome()
    {
        // Crie um tipo fake
        $detalhePedido = DetalhePedido::factory()->create();

        // Data para update
        $sameData = [
            'pedido_id' => $detalhePedido->pedido_id,
            'produto_id' => $detalhePedido->produto_id,
            'quantidade' => $detalhePedido->quantidade,
            'preco' => $detalhePedido->preco,
            'total' => $detalhePedido->total,
           
        ];

        // Faça uma chamada PUT
        $response = $this->putJson('/api/detalhepedidos/' . $detalhePedido->id, $sameData);

        // Verifique a resposta
        $response->assertStatus(200)
            ->assertJson([
                'id' => $detalhePedido->id,
                'pedido_id' => $detalhePedido->pedido_id,
                'produto_id' => $detalhePedido->produto_id,
                'quantidade' => $detalhePedido->quantidade,
                'preco' => $detalhePedido->preco,
                'total' => $detalhePedido->total,
           
            ]);
    }

   

    /**
     * Teste de deletar com sucesso
     *
     * @return void
     */
    public function testDeleteDetalhePedidos()
    {
        // Criar produto fake
        $detalhePedido = DetalhePedido::factory()->create();

        // enviar requisição para Delete
        $response = $this->deleteJson('/api/detalhepedidos/' . $detalhePedido->id);

        // Verifica o Delete
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Detalhe Pedido deletado com sucesso!'
            ]);

        //Verifique se foi deletado do banco
        $this->assertDatabaseMissing('detalhe_pedidos', ['id' => $detalhePedido->id]);
    }

    /**
     * Teste remoção de registro inexistente
     *
     * @return void
     */
    public function testDeleteDetalhePedidosNaoExistente()
    {
        $response = $this->deleteJson('/api/detalhepedidos/999');

        // Verifique a resposta
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Detalhe Pedido não encontrado!'
            ]);
    }

    
}
