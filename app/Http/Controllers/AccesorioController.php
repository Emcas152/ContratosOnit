<?php

namespace App\Http\Controllers;

use App\Models\Accesorio;
use App\Http\Controllers\Controller;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use App\Http\Resources\AccesorioResource;
use App\Http\Requests\AccesorioFormRequest;
use DB;

class AccesorioController extends Controller
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
        $accesorioResult = Accesorio::where([['nombre', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['descripcion', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['categoria', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orderBy('id','DESC')
                                        ->paginate($pagination);
        if (!count($accesorioResult)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> AccesorioResource::collection($accesorioResult),'per_page' => $accesorioResult->perPage(),'total' => $accesorioResult->total()]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccesorioFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $accesorio = Accesorio::create($request->all());
            DB::commit();
            return response(['data'=> new AccesorioResource($accesorio),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear el accesorio','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accesorio  $accesorio
     * @return \Illuminate\Http\Response
     */
    public function show(Accesorio $accesorio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accesorio  $accesorio
     * @return \Illuminate\Http\Response
     */
    public function update(AccesorioFormRequest $request, Accesorio $accesorio)
    {
        try 
        {
            DB::beginTransaction();
            $accesorio = Accesorio::findOrFail($request->id);
            $accesorio->update($request->all());
            DB::commit();
            return response(['data'=> new AccesorioResource($accesorio), 'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accesorio  $accesorio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $accesorio = Accesorio::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial', $accesorio->estado],['tabla','accesorios']])->firstOrFail();
            $accesorio->estado = $estado->sts_final;
            $accesorio->update();
            DB::commit();
            return response(['data'=> new AccesorioResource($accesorio),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }
}
