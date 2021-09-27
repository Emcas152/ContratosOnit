<?php

namespace App\Http\Controllers;

use App\Models\Apartamentos;
use Illuminate\Http\Request;
use App\Http\Resources\ApartamentosResource;
use DB;
use App\Http\Requests\ApartamentosFormRequest;

class ApartamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apartment = Apartamentos::with('proyectos')->get();

        if (!count($apartment)) 
        {
           return response(['data' => '','code'=>204]);   
        }
        return response(['data'=> ApartamentosResource::collection($apartment),'code' => 200]);
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
    public function store(ApartamentosFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $apartment = Apartamentos::create($request->all());
            DB::commit();
            return response(['data'=> $apartment,'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear Apartamentos','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Apartamentos  $apartamentos
     * @return \Illuminate\Http\Response
     */
    public function show(Apartamentos $apartamentos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Apartamentos  $apartamentos
     * @return \Illuminate\Http\Response
     */
    public function edit(Apartamentos $apartamentos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Apartamentos  $apartamentos
     * @return \Illuminate\Http\Response
     */
    public function update(ApartamentosFormRequest $request, $id)
    {
        try 
        {
            DB::beginTransaction();
            $apartment = Apartamentos::findOrFail($id);
            $apartment->update($request->all());
            DB::commit();
            return response(['data'=> $apartment,'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Apartamentos  $apartamentos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartamentos $apartamentos)
    {
        //
    }
}
