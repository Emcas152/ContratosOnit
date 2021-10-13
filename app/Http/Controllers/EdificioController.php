<?php

namespace App\Http\Controllers;

use App\Models\Edificio;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use App\Http\Resources\EdificioResource;
use App\Http\Resources\EdificioSelectResource;
use App\Http\Requests\EdificioFormRequest;
use DB;

class EdificioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $queryUrl = trim($request->searchText);
        $pagination = $request->paginate;
        $edificioResult = Edificio::where([['nombre', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['descripcion', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['niveles', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orderBy('id','DESC')
                                        ->paginate($pagination);
        if (!count($edificioResult)) {
            return response(['data' => '','code'=>204]);  
        }
        return response(['data'=> EdificioResource::collection($edificioResult),'per_page' => $edificioResult->perPage(),'total' => $edificioResult->total()]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EdificioFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $edificio = Edificio::create($request->all());
            DB::commit();
            return response(['data'=> new EdificioResource($edificio),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear el edificio','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Edificio  $edificio
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $edificios = Edificio::all();

        if (!count($edificios)) 
        {
           return response(['data' => '','code'=>204]);   
        }
        return response(['data'=> EdificioSelectResource::collection($edificios),'code' => 200]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Edificio  $edificio
     * @return \Illuminate\Http\Response
     */
    public function update(EdificioFormRequest $request, Edificio $edificio)
    {
        try 
        {
            DB::beginTransaction();
            $edificio = Edificio::findOrFail($request->id);
            $edificio->update($request->all());
            DB::commit();
            return response(['data'=> new EdificioResource($edificio),'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Edificio  $edificio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $edificio = Edificio::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial',$edificio->estado],['tabla','edificios']])->firstOrFail();
            $edificio->estado = $estado->sts_final;
            $edificio->update();
            DB::commit();
            return response(['data'=> new EdificioResource($edificio),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }
}
