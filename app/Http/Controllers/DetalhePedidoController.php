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
        $detalhePedido = DetalhePedido::all();

        //Retornar lista em formato json
        return response()->json(['data' => $detalhePedido]);
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
        $detalhePedido = DetalhePedido::create($request->all());

        // Retorne o codigo 201
        return response()->json($detalhePedido, 201);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $detalhePedido = DetalhePedido::find($id);

        if (!$detalhePedido) {
            return response()->json(['message' => 'Detalhe Pedido não encontrado!'], 404);
        }

        return response()->json($detalhePedido);
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
        $detalhePedido = DetalhePedido::find($id);

        if (!$detalhePedido) {
            return response()->json(['message' => 'Detalhe Pedido não encontrado!'], 404);
        }

        // Faça o update do tipo
        $detalhePedido->update($request->all());

        // Retorne o tipo
        return response()->json($detalhePedido);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $detalhePedido = DetalhePedido::find($id);

        if (!$detalhePedido) {
            return response()->json(['message' => 'Detalhe Pedido não encontrado!'], 404);
        }

        //Se tiver dependentes deve retornar erro

        // Delete the brand
        $detalhePedido->delete();

        return response()->json(['message' => 'Detalhe Pedido deletado com sucesso!'], 200);
        //
    }
}
