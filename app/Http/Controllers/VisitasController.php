<?php

namespace App\Http\Controllers;

use App\Models\Visitas;
use Illuminate\Http\Request;
use DB;
use App\Http\Resources\VisitasResource;
use Carbon\Carbon;

class VisitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $query=trim($request->get('searchText'));
        $paginacion = $request->get('paginate');
        $visitasQuery = Visitas::where([ ['placa_vehiculo','LIKE','%'.$query.'%']]);
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
           return response(['data' => '','code'=>204]);   
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

    public function change_state($id,$state)
    {
        try 
        {
            $mytime = Carbon::now('America/Guatemala');
            DB::beginTransaction();

            $visitas = Visitas::findOrFail($id);
            $visitas->estado = $state;
            if($state == 2)
            {
                $visitas->fecha_ingreso = $mytime->format('Y-m-d H:i:s');
            }
            elseif($state == 3)
            {
                $visitas->fecha_egreso = $mytime->format('Y-m-d H:i:s');
            }
            $visitas->update();

            DB::commit();
            return response(['data'=> $visitas,'code' => 200]);

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
            return response(['data'=> $visitas,'code' => 201]);

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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Visitas  $visitas
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $visita = Visitas::findOrFail($id);
        return response(['data'=> $visita,'code' => 200]);
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
            $data = $request->all();
            $data['fecha_visita'] = date('Y-m-d',strtotime($request->get('fecha_visita')));
            $visita = Visitas::findOrFail($id);
            $visita->update($data);
            DB::commit();
            return response(['data'=> $visita,'code' => 200]);

        } catch (Throwable $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar Visita','code' => 500]);   
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
