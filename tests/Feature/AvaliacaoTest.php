<?php

namespace Tests\Feature;

use App\Models\Avaliacao;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AvaliacaoTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    public function testListarTodosAvaliacaos()
    {
        //Criar 5 Avaliacaos
        //Salvar Temporario
        Avaliacao::factory()->count(5)->create();

        // usar metodo GET para verificar o retorno
        $response = $this->getJson('/api/avaliacaos');

        //Testar ou verificar saida
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'descricao','nota','produto_id', 'created_at', 'updated_at']
                ]
            ]);
    }

    /**
     * Criar um Avaliacao
     */
    public function testCriarAvaliacaoSucesso()
    {

        //Criar produto
        $produto = Produto::factory()->create();
        //Criar o objeto
        $data = [
            'descricao' => $this->faker->sentence(),
            'nota' => $this->faker->randomFloat(2, 0, 10),
            'produto_id' => $produto->id
        ];

        //Debug
        //dd($data);

        // Fazer uma requisição POST
        $response = $this->postJson('/api/avaliacaos', $data);

        //dd($response);

        // Verifique se teve um retorno 201 - Criado com Sucesso
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'descricao','nota','produto_id', 'created_at', 'updated_at']);
    }

     /**
     * Criar um Avaliacao com falha
     */
    public function testCriarAvaliacaoFalha()
    {
        //Criar produto
        $produto = Produto::factory()->create();
        //Criar o objeto
        $data = [
            'descricao' => "a",
            'nota' => 11,
            'produto_id' => " "
        ];

        //Debug
        //dd($data);

        // Fazer uma requisição POST
        $response = $this->postJson('/api/avaliacaos', $data);

        //dd($response);

        // Verifique se teve um retorno 201 - Criado com Sucesso
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(422)
            ->assertJsonValidationErrors([ 'descricao','nota','produto_id']);
    }

    
    /**
     * Teste de deletar com sucesso
     *
     * @return void
     */
    public function testDeleteavaliacao()
    {
        // Criar avaliacao fake
        $avaliacao = Avaliacao::factory()->create();

        // enviar requisição para Delete
        $response = $this->deleteJson('/api/avaliacaos/' . $avaliacao->id);

        // Verifica o Detele
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Avaliação deletada com sucesso!'
            ]);

        //Verifique se foi deletado do banco
        $this->assertDatabaseMissing('avaliacaos', ['id' => $avaliacao->id]);
    }

    /**
     * Teste remoção de registro inexistente
     *
     * @return void
     */
    public function testDeleteavaliacaoNaoExistente()
    {
        // enviar requisição para Delete
        $response = $this->deleteJson('/api/avaliacaos/999');

        // Verifique a resposta
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Avaliação não encontrada!'
            ]);
    }


}
