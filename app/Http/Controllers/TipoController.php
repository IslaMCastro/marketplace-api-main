<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use App\Http\Requests\StoreTipoRequest;
use App\Http\Requests\UpdateTipoRequest;

class TipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Pegar a lista do banco
        $tipos = Tipo::all();

        //Retornar lista em formato json
        return response()->json(['data' => $tipos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    //public function create()
   // {
        //

    //}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoRequest $request)
    {
        // Crie um novo Tipo
        $tipo = Tipo::create($request->all());

        // // Retorne o tipo e o code 201
        return response()->json($tipo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // procure tipo por id
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo não encontrado'], 404);
        }

        return response()->json($tipo);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoRequest $request, $id)
    {
        // Procure o tipo pela id
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo não encontrado'], 404);
        }

        // Faça o update do tipo
        $tipo->update($request->all());

        // Retorne o tipo
        return response()->json($tipo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
          // Encontre um tipo pelo ID
          $tipo = Tipo::find($id);

          if (!$tipo) { 
              return response()->json(['message' => 'Tipo não encontrado!'], 404);
          }  
          //sempre verificar se existe e se há classes dependentes, se sim, retornar erro.
          //

    
          // Delete the brand
          $tipo->delete();
  
          return response()->json(['message' => 'Tipo deletado com sucesso!'], 200);
    }
}