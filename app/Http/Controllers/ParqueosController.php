<?php

namespace App\Http\Controllers;

use App\Models\Parqueos;
use Illuminate\Http\Request;
use App\Http\Resources\ParqueosResource;
use App\Http\Requests\ParqueosFormRequest;
use DB;

class ParqueosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parking = Parqueos::with('proyectos')->get();
        
        if (!count($parking)) 
        {
           return response(['data' => '','code'=>204]);   
        }
        return response(['data'=> ParqueosResource::collection($parking),'code' => 200]);
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
    public function store(ParqueosFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $parking = Parqueos::create($request->all());
            DB::commit();
            return response(['data'=> $parking,'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear Parqueo','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Parqueos  $parqueos
     * @return \Illuminate\Http\Response
     */
    public function show(Parqueos $parqueos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Parqueos  $parqueos
     * @return \Illuminate\Http\Response
     */
    public function edit(Parqueos $parqueos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Parqueos  $parqueos
     * @return \Illuminate\Http\Response
     */
    public function update(ParqueosFormRequest $request, $id)
    {
        try 
        {
            DB::beginTransaction();
            $parking = Parqueos::findOrFail($id);
            $parking->update($request->all());
            DB::commit();
            return response(['data'=> $parking,'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Parqueos  $parqueos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parqueos $parqueos)
    {
        //
    }
}
