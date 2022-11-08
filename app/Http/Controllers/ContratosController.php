<?php

namespace App\Http\Controllers;

use App\Models\Contratos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use DB;

class ContratosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            $info = $request->datos;
            $servicio = $request->servicio;

            $info["fecha_traslado"] = Carbon::parse($info['fecha_traslado'])->format('Y-m-d');
            $dataSave = array_merge($info,$servicio);
            DB::beginTransaction();

            $nameFile = "contrato-".time().".pdf";
            $dataSave["file_name"] = $nameFile;
            $documento = Contratos::create($dataSave);

            $urlFile = storage_path("app/public")."/$nameFile";

            $pdf = PDF::loadView("pdf.contrato", $dataSave);
            $pdf->save($urlFile);

            DB::commit();
            return response(['data'=> $documento,'code' => 201], 201);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear el documento','code' => 500], 500);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contratos  $contratos
     * @return \Illuminate\Http\Response
     */
    public function show(Contratos $contratos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contratos  $contratos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contratos $contratos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contratos  $contratos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contratos $contratos)
    {
        //
    }
}
