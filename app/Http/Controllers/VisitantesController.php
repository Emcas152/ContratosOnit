<?php

namespace App\Http\Controllers;

use App\Models\Visitantes;
use Illuminate\Http\Request;
use App\Http\Resources\VisitantesResource;


class VisitantesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visitantes = Visitantes::get();

        if (!count($visitantes)) 
        {
           return response(['data' => '','code'=>204]);   
        }
        return response(['data'=> VisitantesResource::collection($visitantes),'code' => 200]);
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
     * @param  \App\Models\Visitantes  $visitantes
     * @return \Illuminate\Http\Response
     */
    public function show(Visitantes $visitantes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Visitantes  $visitantes
     * @return \Illuminate\Http\Response
     */
    public function edit(Visitantes $visitantes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visitantes  $visitantes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visitantes $visitantes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Visitantes  $visitantes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visitantes $visitantes)
    {
        //
    }
}
