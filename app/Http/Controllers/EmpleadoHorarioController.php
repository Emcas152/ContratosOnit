<?php

namespace App\Http\Controllers;

use App\Models\EmpleadoHorario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EmpleadoHorarioFormRequest;
use DB;

class EmpleadoHorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $horarios = EmpleadoHorario::where([['id_empleado', '=', $request->id_empleado]])
                                    ->get();

        if (!count($horarios)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> $horarios,'code'=>200]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmpleadoHorarioFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $data = $request->all();
            $horario = EmpleadoHorario::create($data);
            DB::commit();
            return response(['data'=> $horario,'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear Horario','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmpleadoHorario  $empleadoHorario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try 
        {
            DB::beginTransaction();
            $empleadoHorario = EmpleadoHorario::findOrfail($request->id);
            $empleadoHorario->delete();
            DB::commit();
            return response(['data'=> $horario,'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al eliminar Horario','code' => 500]);   
        }
    }
}
