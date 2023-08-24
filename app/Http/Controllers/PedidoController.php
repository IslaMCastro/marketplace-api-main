<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Http\Requests\StorePedidoRequest;
use App\Http\Requests\UpdatePedidoRequest;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Pegar a lista do banco
        $pedidos = Pedido::all();

        //Retornar lista em formato json
        return response()->json(['data' => $pedidos]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePedidoRequest $request)
    {
        // Crie um novo Tipo
        $pedido = Pedido::create($request->all());

        // Retorne o codigo 201
        return response()->json($pedido, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
              // procure Pedido por id
              $pedido = Pedido::find($id);

              if (!$pedido) {
                  return response()->json(['message' => 'Pedido não encontrado'], 404);
              }
      
              return response()->json($pedido);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePedidoRequest $request, $id)
    {
        // Procure o Pedido pela id
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }

        // Faça o update do tipo
        $pedido->update($request->all());

        // Retorne o tipo
        return response()->json($pedido);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
         // Encontre um Pedido pelo ID
         $pedido = Pedido::find($id);

         if (!$pedido) {
             return response()->json(['message' => 'Pedido não encontrado!'], 404);
         }  

         //Se tiver dependentes deve retornar erro
   
         // Delete the brand
         $pedido->delete();
 
         return response()->json(['message' => 'Pedido deletado com sucesso!'], 200);
    }
}