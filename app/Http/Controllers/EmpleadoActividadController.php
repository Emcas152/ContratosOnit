<?php

namespace App\Http\Controllers;

use App\Models\EmpleadoActividad;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EmpleadoActividadFormRequest;
use DB;

class EmpleadoActividadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $actividades = EmpleadoActividad::where([['id_empleado', '=', $request->id_empleado]])
                                    ->get();

        if (!count($actividades)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> $actividades,'code'=>200]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmpleadoActividadFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $data = $request->all();
            $actividad = EmpleadoActividad::create($data);
            DB::commit();
            return response(['data'=> $actividad,'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear Actividad','code' => 500]);   
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmpleadoActividad  $empleadoActividad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmpleadoActividad $empleadoActividad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmpleadoActividad  $empleadoActividad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try 
        {
            DB::beginTransaction();
            $actividad = EmpleadoActividad::findOrfail($request->id);
            $actividad->delete();
            DB::commit();
            return response(['data'=> $actividad,'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al eliminar Actividad','code' => 500]);   
        }
    }
}
