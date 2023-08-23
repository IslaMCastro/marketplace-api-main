<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use App\Http\Requests\StoreMarketplaceRequest;
use App\Http\Requests\UpdateMarketplaceRequest;

class MarketplaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marketplace = Marketplace::all();

        //Retornar lista em formato json
        return response()->json(['data' => $marketplace]);
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
    public function store(StoreMarketplaceRequest $request)
    {
        
        $marketplace = Marketplace::create($request->all());

         // // Retorne o tipo e o code 201
         return response()->json($marketplace, 201);
         //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marketplace = Marketplace::find($id);

        if (!$marketplace) {
            return response()->json(['message' => 'Marketpalce não encontrado'], 404);
        }

        return response()->json($marketplace);
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marketplace $marketplace)
    {
        
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMarketplaceRequest $request, $id)
    {
        $marketplace = Marketplace::find($id);

        if (!$marketplace) {
            return response()->json(['message' => 'marketplace não encontrado'], 404);
        }

        // Faça o update do tipo
        $marketplace->update($request->all());

        // Retorne o tipo
        return response()->json($marketplace);
    }
        //
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $marketplace = Marketplace::find($id);

        if (!$marketplace) { 
            return response()->json(['message' => 'marketplace não encontrado!'], 404);
        }  
        //sempre verificar se existe e se há classes dependentes, se sim, retornar erro.
           
        // Delete the brand
        $marketplace->delete();

        return response()->json(['message' => 'marketplace deletado com sucesso!'], 200);
        //
    }
}
