<?php

namespace App\Http\Controllers;

use App\Models\CalendarioAreasSociales;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use App\Http\Resources\CalendarResource;
use App\Http\Resources\CalendarSocialDashboardResource;
use App\Http\Requests\CalendarFormRequest;
use Carbon\Carbon;
use DB;


class CalendarController extends Controller
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
        $parametros = $request->get('params');
        if($parametros != null )
        {
            $parametros = explode(",",$parametros);
        }
        else
        {
            $parametros = [];
        }
      
        $calendario = [];
        
        if($role == 'admin'){
            $calendario = CalendarioAreasSociales::join('amenidades','amenidades.id','calendario_areas_sociales.id_area')
                         ->select('calendario_areas_sociales.*','amenidades.nombre','amenidades.color')
                         ->where('amenidades.id_condominio','=', $condominio)
                         ->whereIn('amenidades.nombre', $parametros)
                        ->get();
        } elseif ($role == 'client'){
            $calendario = CalendarioAreasSociales::join('amenidades','amenidades.id','calendario_areas_sociales.id_area')
                         ->select('calendario_areas_sociales.*','amenidades.nombre','amenidades.color')
                         ->orWhere(function ($query) use ($usuarioId, $condominio){
                           $query->where([['calendario_areas_sociales.id_usuario', '=', $usuarioId], ['amenidades.id_condominio','=', $condominio]])
                                 ->orWhere([['calendario_areas_sociales.estado','=','AUT'],['amenidades.id_condominio','=', $condominio]]);
                         })
                         ->whereIn('amenidades.nombre', $parametros)
                        ->get();
        }

        if (!count($calendario)) 
        {
           return response(['data' => [],'code'=>204]);   
        }
        return response(['data'=> CalendarResource::collection($calendario),'code' => 200]); 
      
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CalendarFormRequest $request)
    {
        try 
        {
            $fechaInicio = $request->start;
            $fechaFinal = $request->end;
            $extend = $request->get('extendedProps');
            $calendar = DB::select("select * from `calendario_areas_sociales` 
                        where (`id_area` = ".$extend['calendar'].") 
                        and (STR_TO_DATE('$fechaInicio','%Y-%m-%d %H:%i') between fecha_reserva and fecha_finaliza_reserva 
                        or STR_TO_DATE('$fechaFinal','%Y-%m-%d %H:%i') between fecha_reserva and fecha_finaliza_reserva)");
            if (count($calendar)) 
            {
                return response(['errors' => 'La amenidad ya se encuentra reservada','code'=>204], 400);   
            }
            $invitados = $extend['guests'];
            DB::beginTransaction();
            $calendario = new CalendarioAreasSociales;
            $calendario->titulo = $request->get('title');
            $calendario->id_area = $extend['calendar'];
            $calendario->descripcion = $extend['description'];
            $calendario->comentarios = $extend['comment'];
            $calendario->id_usuario = $request->get('id_usuario');
            $calendario->fecha_reserva = date('Y-m-d H:i:s',strtotime($request->get('start')));
            $calendario->fecha_finaliza_reserva = date('Y-m-d H:i:s',strtotime($request->get('end')));
            $calendario->save();

            foreach ($invitados as $invitado) {
                $calendario->invitados()->attach($invitado);
            }
            DB::commit();
            return response(['data'=> new CalendarResource($calendario),'code' => 201]);

        } catch (\Throwable $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CalendarioAreasSociales  $calendarioAreasSociales
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $role = $request->role;
        $usuarioId = $request->usuario_id;
        $condominio = $request->id_condominio;
        $calendario = [];
        if($role == 'admin'){
            $calendario = CalendarioAreasSociales::join('amenidades','amenidades.id','calendario_areas_sociales.id_area')
                         ->select('calendario_areas_sociales.*','amenidades.nombre','amenidades.color')
                         ->where('amenidades.id_condominio','=', $condominio)
                         ->where([[DB::raw('CAST(calendario_areas_sociales.fecha_reserva AS DATE)'),'=',Carbon::now()->format('Y-m-d')],['calendario_areas_sociales.estado','=','AUT']])
                        ->get();
        } elseif ($role == 'client'){
            $calendario = CalendarioAreasSociales::join('amenidades','amenidades.id','calendario_areas_sociales.id_area')
                         ->select('calendario_areas_sociales.*','amenidades.nombre','amenidades.color')
                         ->orWhere(function ($query) use ($usuarioId, $condominio){
                           $query->where([['calendario_areas_sociales.id_usuario', '=', $usuarioId], ['amenidades.id_condominio','=', $condominio]])
                                 ->orWhere([['calendario_areas_sociales.estado','=','AUT'],['amenidades.id_condominio','=', $condominio]]);
                         })
                         ->where([[DB::raw('CAST(calendario_areas_sociales.fecha_reserva AS DATE)'),'=',Carbon::now()->format('Y-m-d')]])
                        ->get();
        }
        if (!count($calendario)) 
        {
           return response(['data' => [],'code'=>204]);   
        }
        return response(['data'=> CalendarSocialDashboardResource::collection($calendario),'code' => 200]); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CalendarioAreasSociales  $calendarioAreasSociales
     * @return \Illuminate\Http\Response
     */
    public function edit(CalendarioAreasSociales $calendarioAreasSociales)
    {
        //
    }

    public function change_state(Request $request)
    {
        try 
        {
            DB::beginTransaction();
            $action = $request->action;
            $calendario = CalendarioAreasSociales::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial',$calendario->estado],
                                              ['proceso', $action],
                                              ['tabla','calendario_areas_sociales']])->firstOrFail();
            $calendario->estado = $estado->sts_final;
            $calendario->update();

            DB::commit();
            return response(['data'=> $calendario,'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]);   
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CalendarioAreasSociales  $calendarioAreasSociales
     * @return \Illuminate\Http\Response
     */
    public function update(CalendarFormRequest $request, $id)
    {
        try 
        {
            $fechaInicio = $request->start;
            $fechaFinal = $request->end;
            $extend = $request->get('extendedProps');
            $calendar = DB::select("select * from `calendario_areas_sociales` 
                        where (`id_area` = ".$extend['calendar']." and `id` <> $id) 
                        and (STR_TO_DATE('$fechaInicio','%Y-%m-%d %H:%i') between fecha_reserva and fecha_finaliza_reserva 
                        or STR_TO_DATE('$fechaFinal','%Y-%m-%d %H:%i') between fecha_reserva and fecha_finaliza_reserva)");
            if (count($calendar)) 
            {
                return response(['errors' => 'La amenidad ya se encuentra reservada','code'=>204], 400);   
            }
            $invitados = $extend['guests'];
            DB::beginTransaction();
            $calendario = CalendarioAreasSociales::findOrFail($id);
            $calendario->titulo = $request->get('title');
            $calendario->id_area = $extend['calendar'];
            $calendario->descripcion = $extend['description'];
            $calendario->comentarios = $extend['comment'];
            $calendario->fecha_reserva = date('Y-m-d H:i:s',strtotime($request->get('start')));
            $calendario->fecha_finaliza_reserva = date('Y-m-d H:i:s',strtotime($request->get('end')));
            $calendario->update();
            $calendario->invitados()->wherePivot('id_calendario', $calendario->id)->detach();
            foreach ($invitados as $invitado) {
                $calendario->invitados()->attach($invitado);
            }
            DB::commit();
            return response(['data'=> new CalendarResource($calendario),'code' => 201]);

        } catch (\Exception $error) 
        {
            DB::rollBack();
            return response(['data'=> $error->getMessage(),'code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CalendarioAreasSociales  $calendarioAreasSociales
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try 
        {
            DB::beginTransaction();
            $calendario = CalendarioAreasSociales::findOrFail($id);
            $estado = EstadosProcesos::where([['sts_inicial',$calendario->estado],['tabla','calendario_areas_sociales']])->firstOrFail();
            $calendario->estado = $estado->sts_final;
            $calendario->save();
            DB::commit();
            return response(['data'=> new CalendarResource($calendario),'code' => 200]);

        } catch (\Throwable $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al desactivar registro','code' => 500]);   
        }
    }
}
