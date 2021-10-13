<?php

namespace App\Http\Controllers;

use App\Models\Visitantes;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use App\Http\Resources\VisitantesResource;
use App\Http\Resources\VisitantesSelectResource;
use App\Http\Requests\VisitanteFormRequest;
use DB;

class VisitantesController extends Controller
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
        $visitanteResult = Visitantes::where([['dpi_visita', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['nombre_visita', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%']])
                                        ->paginate($pagination);
        if (!count($visitanteResult)) {
            return response(['data' => '','code'=>204]);  
        }
        return response(['data'=> VisitantesResource::collection($visitanteResult),'per_page' => $visitanteResult->perPage(),'total' => $visitanteResult->total()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VisitanteFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $visitante = Visitantes::create($request->all());
            DB::commit();
            return response(['data'=> new VisitantesResource($visitante),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear el visitante','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Visitantes  $visitantes
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $visitantes = Visitantes::all();

        if (!count($visitantes)) 
        {
           return response(['data' => '','code'=>204]);   
        }
        return response(['data'=> VisitantesSelectResource::collection($visitantes),'code' => 200]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visitantes  $visitantes
     * @return \Illuminate\Http\Response
     */
    public function update(VisitanteFormRequest $request, Visitantes $visitantes)
    {
        try 
        {
            DB::beginTransaction();
            $visitante = Visitantes::findOrFail($request->id);
            $visitante->update($request->all());
            DB::commit();
            return response(['data'=> new VisitantesResource($visitante),'code' => 200]);
        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Visitantes  $visitantes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $visitante = Visitantes::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial',$visitante->estado],['tabla','visitantes']])->firstOrFail();
            $visitante->estado = $estado->sts_final;
            $visitante->update();
            DB::commit();
            return response(['data'=> new VisitantesResource($visitante),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }
}
