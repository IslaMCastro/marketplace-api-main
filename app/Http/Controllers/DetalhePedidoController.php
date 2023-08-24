<?php

namespace App\Http\Controllers;

use App\Models\DetalhePedido;
use App\Http\Requests\StoreDetalhePedidoRequest;
use App\Http\Requests\UpdateDetalhePedidoRequest;

class DetalhePedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detalhe = DetalhePedido::all();

        //Retornar lista em formato json
        return response()->json(['data' => $detalhe]);
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDetalhePedidoRequest $request)
    {
        $detalhe = DetalhePedido::create($request->all());

        // Retorne o codigo 201
        return response()->json($detalhe, 201);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $detalhe = DetalhePedido::find($id);

        if (!$detalhe) {
            return response()->json(['message' => 'Detalhe Pedido não encontrado'], 404);
        }

        return response()->json($detalhe);
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DetalhePedido $detalhePedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDetalhePedidoRequest $request, $id)
    {
        $detalhe = DetalhePedido::find($id);

        if (!$detalhe) {
            return response()->json(['message' => 'Detalhe Pedido não encontrado'], 404);
        }

        // Faça o update do tipo
        $detalhe->update($request->all());

        // Retorne o tipo
        return response()->json($detalhe);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetalhePedido $id)
    {
        $detalhe = DetalhePedido::find($id);

        if (!$detalhe) {
            return response()->json(['message' => 'Detalhe Pedido não encontrada!'], 404);
        }

        //Se tiver dependentes deve retornar erro

        // Delete the brand
        $detalhe->delete();

        return response()->json(['message' => 'Detalhe Pedido deletada com sucesso!'], 200);
        //
    }
}
