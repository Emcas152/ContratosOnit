<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use App\Http\Resources\ApartamentoResource;
use App\Http\Requests\ApartamentoFormRequest;
use DB;

class ApartamentoController extends Controller
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
        $apartamentoResult = Apartamento::where([['nombre', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['nivel', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orderBy('id','DESC')
                                        ->paginate($pagination);
        if (!count($apartamentoResult)) {
            return response(['data' => '','code'=>204]);  
        }
        return response(['data'=> ApartamentoResource::collection($apartamentoResult),'per_page' => $apartamentoResult->perPage(),'total' => $apartamentoResult->total()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApartamentoFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $apartamento = Apartamento::create($request->all());
            DB::commit();
            return response(['data'=> new ApartamentoResource($apartamento),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear el apartamento','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Apartamentos  $apartamentos
     * @return \Illuminate\Http\Response
     */
    public function show(Apartamentos $apartamentos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Apartamento  $apartamento
     * @return \Illuminate\Http\Response
     */
    public function update(ApartamentoFormRequest $request, Apartamento $apartamento)
    {
        try 
        {
            DB::beginTransaction();
            $apartamento = Apartamento::findOrFail($request->id);
            $apartamento->update($request->all());
            DB::commit();
            return response(['data'=> new ApartamentoResource($apartamento),'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Apartamento  $apartamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $apartamento = Apartamento::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial',$apartamento->estado],['tabla','apartamentos']])->firstOrFail();
            $apartamento->estado = $estado->sts_final;
            $apartamento->update();
            DB::commit();
            return response(['data'=> new ApartamentoResource($apartamento),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }
}
