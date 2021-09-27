<?php

namespace App\Http\Controllers;

use App\Models\Amenidades;
use Illuminate\Http\Request;
use App\Http\Resources\AmenidadesFormResource;
use DB;

use App\Http\Resources\AmenidadesFormResourceName;

class AmenidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $amenidades = Amenidades::get();

        if (!count($amenidades)) 
        {
           return response(['data' => '','code'=>204]);   
        }
        return response(['data'=> AmenidadesFormResource::collection($amenidades),'code' => 200]);
    }

    public function getName()
    {
        $amenidades = Amenidades::get();

        if (!count($amenidades)) 
        {
           return response(['data' => '','code'=>204]);   
        }
        return response(['data'=> AmenidadesFormResourceName::collection($amenidades),'code' => 200]);
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
            $amenidades = Amenidades::create($request->all());
            DB::commit();
            return response(['data'=> $amenidades,'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear Evento','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Amenidades  $amenidades
     * @return \Illuminate\Http\Response
     */
    public function show(Amenidades $amenidades)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Amenidades  $amenidades
     * @return \Illuminate\Http\Response
     */
    public function edit(Amenidades $amenidades)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Amenidades  $amenidades
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Amenidades $amenidades)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Amenidades  $amenidades
     * @return \Illuminate\Http\Response
     */
    public function destroy(Amenidades $amenidades)
    {
        //
    }
}
