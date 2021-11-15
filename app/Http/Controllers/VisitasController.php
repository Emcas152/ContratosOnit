<?php

namespace App\Http\Controllers;

use App\Models\Visitas;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use App\Http\Resources\VisitasResource;
use Carbon\Carbon;
use DB;

class VisitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = $request->role;
        $usuarioId = $request->usuario_id;
        $condominio = $request->id_condominio;
        $estado = $request->estado;
        $query=trim($request->searchText);
        $paginacion = $request->paginate;
        $visitasQuery = [];
        if($role == 'admin'){
            $visitasQuery = Visitas::join('users','users.id','visitas.id_usuario_creo')
                                    ->select('visitas.*')
                                    ->where([ ['placa_vehiculo','LIKE','%'.$query.'%'],['users.id_condominio', '=', $condominio]]);
        } elseif ($role == 'client'){
            $visitasQuery = Visitas::join('users','users.id','visitas.id_usuario_creo')
                                    ->select('visitas.*')
                                    ->where([ ['id_usuario_creo', '=', $usuarioId],['placa_vehiculo','LIKE','%'.$query.'%'],['users.id_condominio', '=', $condominio]]);
        } elseif ($role == 'seguridad'){
            $visitasQuery = Visitas::join('users','users.id','visitas.id_usuario_creo')
                                    ->select('visitas.*')
                                    ->where([['placa_vehiculo','LIKE','%'.$query.'%'],['users.id_condominio', '=', $condominio]]);
        }

        $visitasQuery->whereIn('visitas.estado',$estado);
        
        if($request->get('start') != null)
         {
           $fecha_inicio =  date('Y-m-d',strtotime($request->get('start')));
           $fecha_final = date('Y-m-d',strtotime($request->get('end')));
           $visitasQuery->whereBetween(DB::raw("CAST(visitas.fecha_visita AS DATE)"),[$fecha_inicio,$fecha_final]);

        }
        
        $visitasQuery->orderBy('id','DESC');
        $visitas = $visitasQuery->paginate($paginacion);

        if (!count($visitas)) 
        {
           return response(['data' => [],'code'=>204]);   
        }
        return response(['data'=> VisitasResource::collection($visitas),'per_page' => $visitas->perPage(),'total' => $visitas->total()]);  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function change_state(Request $request)
    {
        try 
        {
            $mytime = Carbon::now();
            DB::beginTransaction();
            $action = $request->action;
            $visitas = Visitas::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial',$visitas->estado],
                                              ['proceso',$request->action],
                                              ['tabla','visitas']])->firstOrFail();
            $visitas->estado = $estado->sts_final;
            if($action == 'Ingreso')
            {
                $visitas->fecha_ingreso = $mytime->format('Y-m-d H:i:s');
            }
            elseif($action == 'Egreso')
            {
                $visitas->fecha_egreso = $mytime->format('Y-m-d H:i:s');
            }
            $visitas->update();

            DB::commit();
            return response(['data'=> new VisitasResource($visitas),'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]);   
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try 
        {
            DB::beginTransaction();
            $visitas = Visitas::create($request->all());
            DB::commit();
            return response(['data'=> new VisitasResource($visitas),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Visitas  $visitas
     * @return \Illuminate\Http\Response
     */
    public function show(Visitas $visitas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visitas  $visitas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try 
        {
            DB::beginTransaction();
            $visitas = Visitas::findOrFail($id);
            $visitas->id_usuario_creo = $request->get('id_usuario_creo');
            $visitas->fecha_visita = date('Y-m-d H:i:s',strtotime($request->get('fecha_visita')));
            $visitas->id_visitante = $request->get('id_visitante');
            $visitas->placa_vehiculo = $request->get('placa_vehiculo');
            $visitas->update();
            DB::commit();
            return response(['data'=> new VisitasResource($visitas),'code' => 200]);
        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]);  
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Visitas  $visitas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visitas $visitas)
    {
        //
    }
}
