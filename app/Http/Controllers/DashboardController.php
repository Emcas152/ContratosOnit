<?php

namespace App\Http\Controllers;

use App\Models\Visitas;
use App\Models\Amenidad;
use App\Models\SolicitudAccesorio;
use App\Models\CalendarioAreasSociales;
use App\Models\ViewPresupuestoEjecucion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function visitsInput(Request $request)
    {
        $role = $request->role;
        $usuarioId = $request->usuario_id;
        $condominio = $request->id_condominio;
        $fechaInicio = $request->start;
        $fechaFinal = $request->end;

        $visitasIngreso = Visitas::join('users','users.id','visitas.id_usuario_creo')
                                ->select('visitas.*')
                                ->where([['visitas.estado','=','ING'],['users.id_condominio', '=', $condominio]]);
        if($fechaInicio != null)
        {
            $fecha_inicio =  date('Y-m-d',strtotime($fechaInicio));
            $fecha_final = date('Y-m-d',strtotime($fechaFinal));
            $visitasIngreso->whereBetween(DB::raw("CAST(visitas.fecha_visita AS DATE)"),[$fecha_inicio,$fecha_final]);
        } else {
            $visitasIngreso->where([[DB::raw('CAST(visitas.fecha_visita AS DATE)'),'=',Carbon::now()->format('Y-m-d')]]);
        }
        $resultado = $visitasIngreso->count();

        return response(['data'=> $resultado,'code' => 200]); 
    }

    public function visitsActive(Request $request)
    {
        $role = $request->role;
        $usuarioId = $request->usuario_id;
        $condominio = $request->id_condominio;
        $fechaInicio = $request->start;
        $fechaFinal = $request->end;

        $visitasActivas = Visitas::join('users','users.id','visitas.id_usuario_creo')
                                ->select('visitas.*')
                                ->where([['visitas.estado','=','ACT'],['users.id_condominio', '=', $condominio]]);
        if($fechaInicio != null) 
        {
            $fecha_inicio =  date('Y-m-d',strtotime($fechaInicio));
            $fecha_final = date('Y-m-d',strtotime($fechaFinal));
            $visitasActivas->whereBetween(DB::raw("CAST(visitas.fecha_visita AS DATE)"),[$fecha_inicio,$fecha_final]);
        } else {
            $visitasActivas->where([[DB::raw('CAST(visitas.fecha_visita AS DATE)'),'=',Carbon::now()->format('Y-m-d')]]);
        }
        $resultado = $visitasActivas->count();

        return response(['data'=> $resultado,'code' => 200]); 
    }

    public function amenitiesAuthorize(Request $request)
    {
        $role = $request->role;
        $usuarioId = $request->usuario_id;
        $condominio = $request->id_condominio;
        $fechaInicio = $request->start;
        $fechaFinal = $request->end;

        $amenidadesAutorizadas = CalendarioAreasSociales::join('amenidades','amenidades.id','calendario_areas_sociales.id_area')
                                    ->select('calendario_areas_sociales.*','amenidades.nombre','amenidades.color')
                                    ->where([['amenidades.id_condominio','=', $condominio], ['calendario_areas_sociales.estado', '=', 'AUT']]);
        if($fechaInicio != null)
        {
            $fecha_inicio =  date('Y-m-d',strtotime($fechaInicio));
            $fecha_final = date('Y-m-d',strtotime($fechaFinal));
            $amenidadesAutorizadas->whereBetween(DB::raw("CAST(calendario_areas_sociales.fecha_reserva AS DATE)"),[$fecha_inicio,$fecha_final]);
        } else {
            $amenidadesAutorizadas->where([[DB::raw('CAST(calendario_areas_sociales.fecha_reserva AS DATE)'),'=',Carbon::now()->format('Y-m-d')]]);
        }
        $resultado = $amenidadesAutorizadas->count();

        return response(['data'=> $resultado,'code' => 200]); 
    }

    public function amenitiesAvailable(Request $request)
    {
        $role = $request->role;
        $usuarioId = $request->usuario_id;
        $condominio = $request->id_condominio;
        $fechaInicio = $request->start;
        $fechaFinal = $request->end;

        $amenidadesAutorizadas = CalendarioAreasSociales::join('amenidades','amenidades.id','calendario_areas_sociales.id_area')
                                    ->select('calendario_areas_sociales.*','amenidades.nombre','amenidades.color')
                                    ->where([['amenidades.id_condominio','=', $condominio], ['calendario_areas_sociales.estado', '=', 'AUT']]);
        if($fechaInicio != null)
        {
            $fecha_inicio =  date('Y-m-d',strtotime($fechaInicio));
            $fecha_final = date('Y-m-d',strtotime($fechaFinal));
            $amenidadesAutorizadas->whereBetween(DB::raw("CAST(calendario_areas_sociales.fecha_reserva AS DATE)"),[$fecha_inicio,$fecha_final]);
        } else {
            $amenidadesAutorizadas->where([[DB::raw('CAST(calendario_areas_sociales.fecha_reserva AS DATE)'),'=',Carbon::now()->format('Y-m-d')]]);
        }

        $amenidadesReservadas = $amenidadesAutorizadas->pluck('id_area');
        
        $resultado = $amenidadesDisponiples = Amenidad::where([['estado', '=', 'ACT'],['id_condominio', '=', $condominio]])
                                    ->whereNotIn('id', $amenidadesReservadas)->count();

        return response(['data'=> $resultado,'code' => 200]);
    }

    public function amenitiesMaintenance(Request $request)
    {
        $role = $request->role;
        $usuarioId = $request->usuario_id;
        $condominio = $request->id_condominio;
        $fechaInicio = $request->start;
        $fechaFinal = $request->end;

        $amenidadesAutorizadas = CalendarioAreasSociales::join('amenidades','amenidades.id','calendario_areas_sociales.id_area')
                                    ->select('calendario_areas_sociales.*','amenidades.nombre','amenidades.color')
                                    ->where([['amenidades.id_condominio','=', $condominio], ['calendario_areas_sociales.estado', '=', 'AUT']]);
        if($fechaInicio != null)
        {
            $fecha_inicio =  date('Y-m-d',strtotime($fechaInicio));
            $fecha_final = date('Y-m-d',strtotime($fechaFinal));
            $amenidadesAutorizadas->whereBetween(DB::raw("CAST(calendario_areas_sociales.fecha_reserva AS DATE)"),[$fecha_inicio,$fecha_final]);
        } else {
            $amenidadesAutorizadas->where([[DB::raw('CAST(calendario_areas_sociales.fecha_reserva AS DATE)'),'=',Carbon::now()->format('Y-m-d')]]);
        }

        $amenidadesReservadas = $amenidadesAutorizadas->pluck('id_area');
        
        $resultado = $amenidadesMantenimiento = Amenidad::where([['estado', '=', 'ANU'],['id_condominio', '=', $condominio]])
                                    ->whereNotIn('id', $amenidadesReservadas)->count();

        return response(['data'=> $resultado,'code' => 200]);
    }


    public function accessoryRequest(Request $request)
    {
        $role = $request->role;
        $usuarioId = $request->usuario_id;
        $condominio = $request->id_condominio;
        $fechaInicio = $request->start;
        $fechaFinal = $request->end;

        $accesoriosPrestados = SolicitudAccesorio::join('accesorios','accesorios.id','solicitudes.id_accesorio')
                                    ->select('solicitudes.id_accesorio','solicitudes.estado')
                                    ->where([['accesorios.id_condominio','=', $condominio], ['solicitudes.estado', '=', 'ENTG']]);

        $resultado = $accesoriosPrestados->distinct()->count();

        return response(['data'=> $resultado,'code' => 200]);
    }

    public function visitsWeek(Request $request)
    {
        $role = $request->role;
        $usuarioId = $request->usuario_id;
        $condominio = $request->id_condominio;
        $fechaInicio = $request->start;
        $fechaFinal = $request->end;

        /* Ingresos */
        $visitasIngreso = Visitas::join('users','users.id','visitas.id_usuario_creo')
                                ->select('visitas.*')
                                ->where([['visitas.estado','=','FNZ'],['users.id_condominio', '=', $condominio]])
                                ->whereNotNull('visitas.fecha_ingreso');
        
        if($fechaInicio != null)
        {
            $fecha_inicio =  date('Y-m-d',strtotime($fechaInicio));
            $fecha_final = date('Y-m-d',strtotime($fechaFinal));
            $visitasIngreso->whereBetween(DB::raw("CAST(visitas.fecha_visita AS DATE)"),[$fecha_inicio,$fecha_final]);
        } else {
            $fecha_inicio =  Carbon::yesterday()->format('Y-m-d');
            $fecha_final = Carbon::today()->subDays(8)->format('Y-m-d');
            $visitasIngreso->whereBetween(DB::raw("CAST(visitas.fecha_visita AS DATE)"),[$fecha_final, $fecha_inicio]);
        }
        $resultado = $visitasIngreso->count();

        $data[] = [
            'icon' => 'CircleIcon',
            'iconColor' => "text-info",
            'result' => $resultado,
            'text' => "Ingresos"
        ];

        /* Finalizados */
        $visitasFinalizadas = Visitas::join('users','users.id','visitas.id_usuario_creo')
                                ->select('visitas.*')
                                ->where([['visitas.estado','=','FNZ'],['users.id_condominio', '=', $condominio]])
                                ->whereNotNull('visitas.fecha_ingreso')
                                ->whereNotNull('visitas.fecha_egreso');
        
        if($fechaInicio != null)
        {
            $fecha_inicio =  date('Y-m-d',strtotime($fechaInicio));
            $fecha_final = date('Y-m-d',strtotime($fechaFinal));
            $visitasFinalizadas->whereBetween(DB::raw("CAST(visitas.fecha_visita AS DATE)"),[$fecha_inicio,$fecha_final]);
        } else {
            $fecha_inicio =  Carbon::yesterday()->format('Y-m-d');
            $fecha_final = Carbon::today()->subDays(8)->format('Y-m-d');
            $visitasFinalizadas->whereBetween(DB::raw("CAST(visitas.fecha_visita AS DATE)"),[$fecha_final, $fecha_inicio]);
        }
        $resultado2 = $visitasFinalizadas->count();

        $data[] = [
            'icon' => 'CircleIcon',
            'iconColor' => "text-danger",
            'result' => $resultado2,
            'text' => "Finalizadas"
        ];

        /* Pendientes */
        $visitasPendientes = Visitas::join('users','users.id','visitas.id_usuario_creo')
                                ->select('visitas.*')
                                ->where([['visitas.estado','=','ACT'],['users.id_condominio', '=', $condominio]])
                                ->whereNull('visitas.fecha_ingreso')
                                ->whereNull('visitas.fecha_egreso');
        
        if($fechaInicio != null)
        {
            $fecha_inicio =  date('Y-m-d',strtotime($fechaInicio));
            $fecha_final = date('Y-m-d',strtotime($fechaFinal));
            $visitasPendientes->whereBetween(DB::raw("CAST(visitas.fecha_visita AS DATE)"),[$fecha_inicio,$fecha_final]);
        } else {
            $fecha_inicio =  Carbon::yesterday()->format('Y-m-d');
            $fecha_final = Carbon::today()->subDays(8)->format('Y-m-d');
            $visitasPendientes->whereBetween(DB::raw("CAST(visitas.fecha_visita AS DATE)"),[$fecha_final, $fecha_inicio]);
        }
        $resultado3 = $visitasPendientes->count();

        $data[] = [
            'icon' => 'CircleIcon',
            'iconColor' => "text-warning",
            'result' => $resultado3,
            'text' => "Pendientes"
        ];

        return response(['data'=> $data,'code' => 200]); 
    }


    public function amenitiesAuthorizeMaintenance(Request $request)
    {
        $role = $request->role;
        $usuarioId = $request->usuario_id;
        $condominio = $request->id_condominio;
        $fechaInicio = $request->start;
        $fechaFinal = $request->end;

        $amenidadesAutorizadas = CalendarioAreasSociales::join('amenidades','amenidades.id','calendario_areas_sociales.id_area')
                                    ->select('calendario_areas_sociales.*','amenidades.nombre','amenidades.color',DB::raw('COUNT(*) as cantidad'))
                                    ->where([['amenidades.id_condominio','=', $condominio], ['calendario_areas_sociales.estado', '=', 'AUT']]);
        if($fechaInicio != null)
        {
            $fecha_inicio =  date('Y-m-d',strtotime($fechaInicio));
            $fecha_final = date('Y-m-d',strtotime($fechaFinal));
            $amenidadesAutorizadas->whereBetween(DB::raw("CAST(calendario_areas_sociales.fecha_reserva AS DATE)"),[$fecha_inicio,$fecha_final]);
        } else {
            $amenidadesAutorizadas->where([[DB::raw('CAST(calendario_areas_sociales.fecha_reserva AS DATE)'),'=',Carbon::now()->format('Y-m-d')]]);
        }
        $amenidadesAutorizadas->groupBy('calendario_areas_sociales.id_area');
        $amenidadesAutorizadas->orderBy('amenidades.nombre');

        $valoresSerie = $amenidadesAutorizadas->pluck('cantidad');
        $valoresNegativos = [];
        foreach ($valoresSerie as $key => $value) {
            $valoresNegativos[] = -1 * rand(0, 2);
        }
        $categorias = $amenidadesAutorizadas->pluck('amenidades.nombre');

        $data[] = [
            'name' => "Reservas",
            'data' => $valoresSerie
        ];

        $data[] = [
            'name' => "Mantenimientos",
            'data' => $valoresNegativos
        ];

        return response(['data'=> ['series' => $data, 'categories' => $categorias],'code' => 200]); 
    }

    public function presupuestoEjecucion(Request $request)
    {
        $presupuesto = ViewPresupuestoEjecucion::where([['id_condominio','=',$request->id_condominio],['nombre_presupuesto', '=', $request->year]])->get()->toArray();
        if (!count($presupuesto)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> ['series' => [100, $presupuesto[0]['porcentaje']*1], 'values' => [$presupuesto[0]['monto_presupuesto']*1,$presupuesto[0]['monto_ejecutado']*1]],'code' => 200]); 
    }
}
