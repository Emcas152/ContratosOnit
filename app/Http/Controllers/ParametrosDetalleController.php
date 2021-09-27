<?php

namespace App\Http\Controllers;

use App\Models\ParametrosDetalle;
use Illuminate\Http\Request;
use App\Http\Resources\ParametrosDetalleResource;
use App\Http\Requests\ParametrosDetalleIdFormRequest;

class ParametrosDetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ParametrosDetalleIdFormRequest $request)
    {
        $parametros = ParametrosDetalle::where('id_parametro','=',$request->input('id'))
        ->get();
        if (!count($parametros)) 
        {
           return response(['data' => '','code'=>204]);   
        }
        return response(['data'=> ParametrosDetalleResource::collection($parametros),'code' => 200]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ParametrosDetalle  $parametrosDetalle
     * @return \Illuminate\Http\Response
     */
    public function show(ParametrosDetalle $parametrosDetalle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ParametrosDetalle  $parametrosDetalle
     * @return \Illuminate\Http\Response
     */
    public function edit(ParametrosDetalle $parametrosDetalle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ParametrosDetalle  $parametrosDetalle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ParametrosDetalle $parametrosDetalle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ParametrosDetalle  $parametrosDetalle
     * @return \Illuminate\Http\Response
     */
    public function destroy(ParametrosDetalle $parametrosDetalle)
    {
        //
    }
}
