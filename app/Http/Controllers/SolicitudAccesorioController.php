<?php

namespace App\Http\Controllers;

use App\Models\Accesorio;
use App\Models\SolicitudAccesorio;
use App\Models\EstadosProcesos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SolicitudAccesorioResource;
use App\Http\Requests\SolicitudAccesorioFormRequest;
use Carbon\Carbon;
use DB;

class SolicitudAccesorioController extends Controller
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
        $solicitudAccesorioResult = SolicitudAccesorio::where([['estado', 'LIKE', '%'.$queryUrl.'%']]);

        if($request->start != null)
         {
           $fecha_inicio =  date('Y-m-d',strtotime($request->start));
           $fecha_final = date('Y-m-d',strtotime($request->end));
           $solicitudAccesorioResult->whereBetween(DB::raw("CAST(solicitudes.fecha_prestamo AS DATE)"),[$fecha_inicio,$fecha_final]);
        }
        $solicitudAccesorioResult->orderBy('id','DESC');
        $solicitudes = $solicitudAccesorioResult->paginate($pagination);

        if (!count($solicitudes)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> SolicitudAccesorioResource::collection($solicitudes),'per_page' => $solicitudes->perPage(),'total' => $solicitudes->total()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SolicitudAccesorioFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $fecha_solicitud = date('Y-m-d H:i:s',strtotime($request->fecha_solicitud));
            $existeSolicitud = SolicitudAccesorio::where([['id_accesorio', '=', $request->id_accesorio], 
                                                        ['id_usuario_solicito', '=', $request->id_usuario_solicito],
                                                        ['fecha_solicitud', '=', $fecha_solicitud]])
                                                        ->get();
            if (count($existeSolicitud)) {
                return response(['data' => [],'code'=>200, 'error' =>'Ya existe una solicitud igual'],400);
            }
            $solicitudAccesorio = SolicitudAccesorio::create($request->all());
            DB::commit();
            return response(['data'=> new SolicitudAccesorioResource($solicitudAccesorio),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear la Solicitud','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SolicitudAccesorio  $solicitudAccesorio
     * @return \Illuminate\Http\Response
     */
    public function change_state(Request $request)
    {
        try 
        {
            $mytime = Carbon::now();
            DB::beginTransaction();
            $action = $request->action;
            $solicitudAccesorio = SolicitudAccesorio::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial',$solicitudAccesorio->estado],
                                              ['proceso',$action],
                                              ['tabla','solicitudes']])->firstOrFail();
            $solicitudAccesorio->estado = $estado->sts_final;
            $accesorio = Accesorio::findOrfail($solicitudAccesorio->id_accesorio);
            if($action == 'Entregado')
            {
                $solicitudAccesorio->id_autorizo = $request->id_autorizo;
                $solicitudAccesorio->fecha_prestamo = $mytime->format('Y-m-d H:i:s');
                $accesorio->estado = $solicitudAccesorio->estado;
            }
            elseif($action == 'Devuelto' || $action == 'MalEstado')
            {
                $solicitudAccesorio->fecha_devolucion = $mytime->format('Y-m-d H:i:s');
                if ($action == 'Devuelto') {
                    $accesorio->estado = 'ACT';
                } else {
                    $accesorio->estado = $solicitudAccesorio->estado;
                }
            }
            $accesorio->update();
            $solicitudAccesorio->update();

            DB::commit();
            return response(['data'=> new SolicitudAccesorioResource($solicitudAccesorio),'code' => 200]);

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
     * @param  \App\Models\SolicitudAccesorio  $solicitudAccesorio
     * @return \Illuminate\Http\Response
     */
    public function update(SolicitudAccesorioFormRequest $request, SolicitudAccesorio $solicitudAccesorio)
    {
        try 
        {
            DB::beginTransaction();
            $solicitudAccesorio = SolicitudAccesorio::findOrFail($request->id);
            $solicitudAccesorio->update($request->all());
            DB::commit();
            return response(['data'=> new SolicitudAccesorioResource($solicitudAccesorio),'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SolicitudAccesorio  $solicitudAccesorio
     * @return \Illuminate\Http\Response
     */
    public function destroy(SolicitudAccesorio $solicitudAccesorio)
    {
        //
    }
}
