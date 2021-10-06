<?php

namespace App\Http\Controllers;

use App\Models\Amenidad;
use App\Http\Controllers\Controller;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use App\Http\Resources\AmenidadResource;
use App\Http\Requests\AmenidadFormRequest;
use DB;

class AmenidadController extends Controller
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
        $amenidadResult = Amenidad::where([['nombre', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['descripcion', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%']])
                                        ->paginate($pagination);
        if (!count($amenidadResult)) {
            return response(['data' => '','code'=>204]);  
        }
        return response(['data'=> AmenidadResource::collection($amenidadResult),'per_page' => $amenidadResult->perPage(),'total' => $amenidadResult->total()]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AmenidadFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $amenidad = Amenidad::create($request->all());
            DB::commit();
            return response(['data'=> new AmenidadResource($amenidad),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear el amenidad','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Amenidad  $amenidad
     * @return \Illuminate\Http\Response
     */
    public function show(Amenidad $amenidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Amenidad  $amenidad
     * @return \Illuminate\Http\Response
     */
    public function update(AmenidadFormRequest $request, Amenidad $amenidad)
    {
        try 
        {
            DB::beginTransaction();
            $amenidad = Amenidad::findOrFail($request->id);
            $amenidad->update($request->all());
            DB::commit();
            return response(['data'=> new AmenidadResource($amenidad), 'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Amenidad  $amenidad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $amenidad = Amenidad::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial', $amenidad->estado],['tabla','accesorios']])->firstOrFail();
            $amenidad->estado = $estado->sts_final;
            $amenidad->update();
            DB::commit();
            return response(['data'=> new AmenidadResource($amenidad),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }
}
