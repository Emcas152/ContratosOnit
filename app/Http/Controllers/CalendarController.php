<?php

namespace App\Http\Controllers;

use App\Models\CalendarioAreasSociales;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use App\Http\Resources\CalendarResource;
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
                         ->whereIn('amenidades.nombre', $parametros)
                        ->get();
        } elseif ($role == 'client'){
            $calendario = CalendarioAreasSociales::join('amenidades','amenidades.id','calendario_areas_sociales.id_area')
                         ->select('calendario_areas_sociales.*','amenidades.nombre','amenidades.color')
                         ->orWhere(function ($query) use ($usuarioId){
                           $query->where('calendario_areas_sociales.id_usuario', '=', $usuarioId)
                                 ->orWhere('calendario_areas_sociales.estado','=','AUT');
                         })
                         ->whereIn('amenidades.nombre', $parametros)
                        ->get();
        }

        if (!count($calendario)) 
        {
           return response(['data' => '','code'=>204]);   
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
    public function store(Request $request)
    {
        try 
        {
            DB::beginTransaction();
            $extend = $request->get('extendedProps');
            $calendario = new CalendarioAreasSociales;
            $calendario->titulo = $request->get('title');
            $calendario->id_area = $extend['calendar'];
            $calendario->descripcion = $extend['description'];
            $calendario->comentarios = $extend['comment'];
            $calendario->id_usuario = $request->get('id_usuario');
            $calendario->fecha_reserva = date('Y-m-d H:i:s',strtotime($request->get('start')));
            $calendario->fecha_finaliza_reserva = date('Y-m-d H:i:s',strtotime($request->get('end')));
            $calendario->save();

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
    public function update(Request $request, $id)
    {
        try 
        {
            DB::beginTransaction();
            $extend = $request->get('extendedProps');
            $calendario = CalendarioAreasSociales::findOrFail($id);
            $calendario->titulo = $request->get('title');
            $calendario->id_area = $extend['calendar'];
            $calendario->descripcion = $extend['description'];
            $calendario->comentarios = $extend['comment'];
            $calendario->fecha_reserva = date('Y-m-d H:i:s',strtotime($request->get('start')));
            $calendario->fecha_finaliza_reserva = date('Y-m-d H:i:s',strtotime($request->get('end')));
            $calendario->update();

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
