<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Http\Requests\StoreAvaliacaoRequest;
use App\Http\Requests\UpdateAvaliacaoRequest;

class AvaliacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $avaliacoes = Avaliacao::all();

        //Retornar lista em formato json
        return response()->json(['data' => $avaliacoes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAvaliacaoRequest $request)
    {
        // Crie um novo Tipo
        $avaliacao = Avaliacao::create($request->all());

        // Retorne o codigo 201
        return response()->json($avaliacao, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Encontre um tipo pelo ID
        $avaliacao = Avaliacao::find($id);

        if (!$avaliacao) {
            return response()->json(['message' => 'Avaliação não encontrada!'], 404);
        }

        //Se tiver dependentes deve retornar erro

        // Delete the brand
        $avaliacao->delete();

        return response()->json(['message' => 'Avaliação deletada com sucesso!'], 200);
    }
}
