<?php

namespace App\Http\Controllers;

use App\Models\Proyectos;
use Illuminate\Http\Request;
use App\Http\Resources\ProyectosResource;
use App\Http\Resources\ProyectosActResource;
use App\Http\Requests\ProyectosFormRequest;
use DB;

class ProyectosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project = Proyectos::all();
        if (!count($project)) 
        {
           return response(['data' => '','code'=>204]);   
        }
        return response(['data'=> ProyectosResource::collection($project),'code' => 200]);
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
    public function store(ProyectosFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $project = Proyectos::create($request->all());
            DB::commit();
            return response(['data'=> $project,'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear Proyecto','code' => 500]);   
        }
    }

    public function projectAct()
    {
        $projectAct = Proyectos::where('estado','=','ACT')->get();
        if (!count($projectAct)) 
        {
           return response(['data' => '','code'=>204]);   
        }
        return response(['data'=> ProyectosActResource::collection($projectAct),'code' => 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proyectos  $proyectos
     * @return \Illuminate\Http\Response
     */
    public function show(Proyectos $proyectos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proyectos  $proyectos
     * @return \Illuminate\Http\Response
     */
    public function edit(Proyectos $proyectos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proyectos  $proyectos
     * @return \Illuminate\Http\Response
     */
    public function update(ProyectosFormRequest $request, $id)
    {
        try 
        {
            DB::beginTransaction();
            $project = Proyectos::findOrFail($id);
            $project->update($request->all());
            DB::commit();
            return response(['data'=> $project,'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proyectos  $proyectos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proyectos $proyectos)
    {
        //
    }
}
