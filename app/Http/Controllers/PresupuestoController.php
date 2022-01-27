<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\EstadosProcesos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PresupuestoResource;
use App\Http\Requests\PresupuestoFormRequest;
use Carbon\Carbon;
use DB;

class PresupuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = $request->role;
        $condominio = $request->id_condominio;
        $query=trim($request->searchText);
        $paginacion = $request->paginate;
        $presupuestoResult = [];
        if($role == 'admin'){
            $presupuestoResult = Presupuesto::where([['descripcion', 'LIKE', '%'.$queryUrl.'%'],['id_condominio', '=', $condominio]])
                                            ->orWhere([['presupuesto', 'LIKE', '%'.$queryUrl.'%'],['id_condominio', '=', $condominio]])
                                            ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%'],['id_condominio', '=', $condominio]]);
        }
        
        if($request->start != null)
         {
           $fecha_inicio =  date('Y-m-d',strtotime($request->start));
           $fecha_final = date('Y-m-d',strtotime($request->end));
           $presupuestoResult->whereBetween(DB::raw("CAST(fecha_inicio AS DATE)"),[$fecha_inicio,$fecha_final]);
        }
        
        $presupuestoResult->orderBy('id','DESC');
        $presupuesto = $presupuestoResult->paginate($paginacion);

        if (!count($presupuesto)) 
        {
           return response(['data' => [],'code'=>204]);   
        }
        return response(['data'=> PresupuestoResource::collection($presupuesto),'per_page' => $presupuesto->perPage(),'total' => $presupuesto->total()]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PresupuestoFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $presupuesto = Presupuesto::create($request->all());
            DB::commit();
            return response(['data'=> new PresupuestoResource($presupuesto),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Presupuesto  $presupuesto
     * @return \Illuminate\Http\Response
     */
    public function show(Presupuesto $presupuesto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Presupuesto  $presupuesto
     * @return \Illuminate\Http\Response
     */
    public function update(PresupuestoFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $presupuesto = Presupuesto::findOrFail($request->id);
            $presupuesto->fecha_inicio = date('Y-m-d H:i:s',strtotime($request->fecha_inicio));
            $presupuesto->fecha_vencimiento = date('Y-m-d H:i:s',strtotime($request->fecha_vencimiento));
            $presupuesto->descripcion = $request->descripcion;
            $presupuesto->presupuesto = $request->presupuesto;
            $presupuesto->update();
            DB::commit();
            return response(['data'=> new PresupuestoResource($presupuesto),'code' => 200]);
        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]);  
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Presupuesto  $presupuesto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $presupuesto = Presupuesto::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial', $presupuesto->estado],['tabla','presupuesto_enc']])->firstOrFail();
            $presupuesto->estado = $estado->sts_final;
            $presupuesto->update();
            DB::commit();
            return response(['data'=> new PresupuestoResource($presupuesto),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }
}
