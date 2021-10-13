<?php

namespace App\Http\Controllers;

use App\Models\Condominio;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use App\Http\Resources\CondominioResource;
use App\Http\Requests\CondominioFormRequest;
use DB;

class CondominioController extends Controller
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
        $condominioResult = Condominio::where([['nombre', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['parqueo_visitantes', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orderBy('id','DESC')
                                        ->paginate($pagination);
        if (!count($condominioResult)) {
            return response(['data' => '','code'=>204]);  
        }
        return response(['data'=> CondominioResource::collection($condominioResult),'per_page' => $condominioResult->perPage(),'total' => $condominioResult->total()]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CondominioFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $condominio = Condominio::create($request->all());
            DB::commit();
            return response(['data'=> new CondominioResource($condominio),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear el condominio','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Condominio  $condominio
     * @return \Illuminate\Http\Response
     */
    public function show(Condominio $condominio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Condominio  $condominio
     * @return \Illuminate\Http\Response
     */
    public function update(CondominioFormRequest $request, Condominio $condominio)
    {
        try 
        {
            DB::beginTransaction();
            $condominio = Condominio::findOrFail($request->id);
            $condominio->update($request->all());
            DB::commit();
            return response(['data'=> new CondominioResource($condominio),'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Condominio  $condominio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $condominio = Condominio::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial',$condominio->estado],['tabla','condominios']])->firstOrFail();
            $condominio->estado = $estado->sts_final;
            $condominio->update();
            DB::commit();
            return response(['data'=> new CondominioResource($condominio),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }
}
