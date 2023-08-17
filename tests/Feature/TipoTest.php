<?php

namespace Tests\Feature;

use App\Models\Tipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TipoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**Listar todos os tipos
     * @return void
     */

    public function testListarTodosTipos()
    {
         //Criar 5 tipos
        //Salvar Temporario
        Tipo::factory()->count(5)->create();

        // usar metodo GET para verificar o retorno
        
        $response = $this->getJson('/api/tipos');

        //Testar ou verificar saida
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'descricao', 'created_at', 'updated_at']
                ]
            ]);
    }

    /**
     * Criar um Tipo
     */
    public function testCriarTipoSucesso(){

        //Criar o objeto
        $data = [
            "descricao" => $this->faker->word
        ];

        //Debug
        //dd($data);

        // Fazer uma requisição POST
        $response = $this->postJson('/api/tipos', $data);

        //dd($response);

        // Verifique se teve um retorno 201 - Criado com Sucesso
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'descricao', 'created_at', 'updated_at']);

    }

    
    /**
     * Teste de criação com falhas
     *
     * @return void
     */
    public function testCriacaoTipoFalha()
    {
        $data = [
            "descricao" => 'a'
        ];
         // Fazer uma requisição POST
        $response = $this->postJson('/api/tipos', $data);

        // Verifique se teve um retorno 422 - Falha no salvamento
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['descricao']);
    }

     /**
     * Teste de pesquisa de registro
     *
     * @return void
     */
    public function testPesquisaTipoSucesso()
    {
        // Criar um tipo
        $tipo = Tipo::factory()->create();

        
        // Fazer pesquisa
        $response = $this->getJson('/api/tipos/' . $tipo->id);   
        
        // Verificar saida
        $response->assertStatus(200)
            ->assertJson([
                'id' => $tipo->id,
                'descricao' => $tipo->descricao,                
            ]);
    }

    
    /**
     * Teste de pesquisa de registro com falha
     *
     * @return void
     */
    public function testPesquisaTipoComFalha()
    {
        // Fazer pesquisa com um id inexistente
        $response = $this->getJson('/api/tipos/999'); // o 999 nao pode existir

        // Veriicar a resposta
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Tipo não encontrado'
            ]);
    }

    /**
     *Teste de upgrade com sucesso
     *
     * @return void
     */
    public function testUpdateTipoSucesso()
    {
        // Crie um tipo fake
        $tipo = Tipo::factory()->create();

        // Dados para update
        $newData = [
            'descricao' => 'Tipo Descrição',            
        ];
        
        // Faça uma chamada PUT
        $response = $this->putJson('/api/tipos/' . $tipo->id, $newData);

        // Verifique a resposta
        $response->assertStatus(200)
            ->assertJson([
                'id' => $tipo->id,
                'descricao' => 'Tipo Descrição', 
            ]);
    }

    /**
     * Testando com falhas
     *
     * @return void
     */
    public function testUpdateTipoDataInvalida()
    {
        // Crie um tipo falso
        $tipo = Tipo::factory()->create();

        // Crie dados falhos
        $invalidData = [
            'descricao' => '', // Invalido: Descricao vazio
        ];

        // faça uma chamada PUT
        $response = $this->putJson('/api/tipos/' . $tipo->id, $invalidData);

        // Verificar se teve um erro 422
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['descricao']);
    }


    /**
     * Teste update de marca
     *
     * @return void
     */
    public function testUpdateTipoNaoExistente()
    {
        // Faça uma chamada para um id falho
        $response = $this->putJson('/api/tipos/999', ['descricao' => 'Tipo Descrição']); //O 999 não deve existir

        // Verificar o retorno 404
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Tipo não encontrado'
            ]);
    }

    /**
     * Teste de upgrade com os mesmos dados
     *
     * @return void
     */
    public function testUpdateTipoMesmosDados()
    {
        // Crie um tipo fake
        $tipo = Tipo::factory()->create();

        // Data para update
        $sameData = [
            'descricao' => $tipo->tipo,            
        ];

        // Faça uma chamada PUT
        $response = $this->putJson('/api/tipos/' . $tipo->id, $sameData);

        // Verifique a resposta
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['descricao']);
    }

    /**
     * Teste upgrade com nome duplicado
     *
     * @return void
     */
    public function testUpdateTipoDescricaoDuplicada()
    {
        // Crie dois tipos fakes
        $tipoExistente = Tipo::factory()->create();
        $tipoUpgrade = Tipo::factory()->create();

        // Para para upgrade
        $newData = [
            'descricao' => $tipoExistente->tipo,            
        ];

        // Faça o put 
        $response = $this->putJson('/api/tipos/' . $tipoUpgrade->id, $newData);

        // Verifique a resposta
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['descricao']);
    }


    /**
     * Teste de deletar com sucesso
     *
     * @return void
     */
    public function testDeleteTipo()
    {
        // Criar tipo fake
        $tipo = Tipo::factory()->create();

        // enviar requisição para Delete
        $response = $this->deleteJson('/api/tipos/' . $tipo->id);

        // Verifica o Detele
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Tipo deletado com sucesso!'
            ]);

        //Verifique se foi deletado do banco
        $this->assertDatabaseMissing('tipos', ['id' => $tipo->id]);
    }

    /**
     * Teste remoção de registro inexistente
     *
     * @return void
     */
    public function testDeleteTipoNaoExistente()
    {
        // enviar requisição para Delete
        $response = $this->deleteJson('/api/tipos/999');

        // Verifique a resposta
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Tipo não encontrado!'
            ]);
    }

}