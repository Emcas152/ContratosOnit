<?php

namespace App\Http\Controllers;

use App\Models\CalendarioAreasSociales;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use App\Http\Resources\CalendarSocialResource;
use App\Http\Requests\CalendarSocialFormRequest;
use DB;

class CalendarSocialController extends Controller
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
        
        $calendario = [];
        
        $calendario = CalendarioAreasSociales::join('amenidades','amenidades.id','calendario_areas_sociales.id_area')
                         ->select('calendario_areas_sociales.*','amenidades.nombre','amenidades.color')
                         ->orWhere(function ($query) use ($usuarioId, $condominio){
                           $query->where([['calendario_areas_sociales.id_usuario', '=', $usuarioId], ['amenidades.id_condominio','=', $condominio]])
                                 ->orWhere([['calendario_areas_sociales.estado','=','AUT'],['amenidades.id_condominio','=', $condominio]]);
                         })
                        ->get();

        if (!count($calendario)) 
        {
           return response(['data' => [],'code'=>204]);   
        }
        return response(['data'=> CalendarSocialResource::collection($calendario),'code' => 200]); 
      
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
    public function store(CalendarSocialFormRequest $request)
    {
        try 
        {
            $fechaInicio = $request->start;
            $fechaFinal = $request->end;
            
            $calendar = DB::select("select * from `calendario_areas_sociales` 
                        where (`id_area` = ".$request->calendar.") 
                        and (STR_TO_DATE('$fechaInicio','%Y-%m-%d %H:%i') between fecha_reserva and fecha_finaliza_reserva 
                        or STR_TO_DATE('$fechaFinal','%Y-%m-%d %H:%i') between fecha_reserva and fecha_finaliza_reserva)");
            if (count($calendar)) 
            {
                return response(['errors' => 'La amenidad ya se encuentra reservada','code'=>204], 400);   
            }
            $invitados = $request->invitados;
            DB::beginTransaction();
            $calendario = new CalendarioAreasSociales;
            $calendario->titulo = $request->title;
            $calendario->id_area = $request->calendar;
            $calendario->descripcion = $request->description;
            $calendario->comentarios = $request->comment;
            $calendario->id_usuario = $request->id_usuario;
            $calendario->fecha_reserva = date('Y-m-d H:i:s',strtotime($fechaInicio));
            $calendario->fecha_finaliza_reserva = date('Y-m-d H:i:s',strtotime($fechaFinal));
            $calendario->save();

            foreach ($invitados as $invitado) {
                $calendario->invitados()->attach($invitado);
            }
            DB::commit();
            return response(['data'=> new CalendarSocialResource($calendario),'code' => 201]);

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
    public function show(CalendarioAreasSociales $calendarioAreasSociales)
    {
        //
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
            return response(['data'=> new CalendarSocialResource($calendario),'code' => 200]);

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
    public function update(CalendarSocialFormRequest $request, $id)
    {
        try 
        {
            $fechaInicio = $request->start;
            $fechaFinal = $request->end;
            
            $calendar = DB::select("select * from `calendario_areas_sociales` 
                        where (`id_area` = ".$request->calendar." and `id` <> $id) 
                        and (STR_TO_DATE('$fechaInicio','%Y-%m-%d %H:%i') between fecha_reserva and fecha_finaliza_reserva 
                        or STR_TO_DATE('$fechaFinal','%Y-%m-%d %H:%i') between fecha_reserva and fecha_finaliza_reserva)");
            if (count($calendar)) 
            {
                return response(['errors' => 'La amenidad ya se encuentra reservada','code'=>204], 400);   
            }
            $invitados = $request->invitados;
            DB::beginTransaction();
            $calendario = CalendarioAreasSociales::findOrFail($id);
            $calendario->titulo = $request->title;
            $calendario->id_area = $request->calendar;
            $calendario->descripcion = $request->description;
            $calendario->comentarios = $request->comment;
            $calendario->fecha_reserva = date('Y-m-d H:i:s',strtotime($fechaInicio));
            $calendario->fecha_finaliza_reserva = date('Y-m-d H:i:s',strtotime($fechaFinal));
            $calendario->update();
            $calendario->invitados()->wherePivot('id_calendario', $calendario->id)->detach();
            foreach ($invitados as $invitado) {
                $calendario->invitados()->attach($invitado);
            }
            DB::commit();
            return response(['data'=> new CalendarSocialResource($calendario),'code' => 201]);

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
            return response(['data'=> new CalendarSocialResource($calendario),'code' => 200]);

        } catch (\Throwable $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al desactivar registro','code' => 500]);   
        }
    }
}
