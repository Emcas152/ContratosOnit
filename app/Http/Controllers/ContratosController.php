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
            $plan = $request->plan;

            /* Fecha para la vista */

            $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
            $fecha = Carbon::now();
            $mes = $meses[($fecha->format('n')) - 1];
            $plan['fecha_texto'] = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

            /* Fin Fecha para la vista */

            $info["fecha_traslado"] = Carbon::parse($info['fecha_traslado'])->format('Y-m-d');
            $info["fecha_registro"] = $fecha;
            $dataSave = array_merge($info,$servicio,$plan);
            DB::beginTransaction();

            $nameFilePlan3 = "contrato-internet-".time().".pdf";
            $dataSave["file_name"] = ($plan["tipo_plan"] == 'PLAN3' || $plan["tipo_plan"] == 'PLAN1') ? $nameFilePlan3 : '';
            $nameFilePlan2 = "contrato-agua-y-energia-".time().".pdf";
            $dataSave["file_name_contrato"] = ($plan["tipo_plan"] == 'PLAN3' || $plan["tipo_plan"] == 'PLAN2') ? $nameFilePlan2 : '';
            $documento = Contratos::create($dataSave);

            $dataSave["id"] = $documento->id;

            if ($dataSave["tipo_proyecto"] == 'VIVO 4') {
                if ($plan["tipo_plan"] == 'PLAN3' || $plan["tipo_plan"] == 'PLAN2') {
                    $urlFile = storage_path("app/public")."/$nameFilePlan2";
                    $pdf = PDF::loadView("pdf.contrato", $dataSave);
                    $pdf->save($urlFile);
                }
    
                if ($plan["tipo_plan"] == 'PLAN3' || $plan["tipo_plan"] == 'PLAN1') {
                    $urlFile2 = storage_path("app/public")."/$nameFilePlan3";
                    $pdf = PDF::loadView("pdf.contrato-internet", $dataSave);
                    $pdf->save($urlFile2);
                }
            } else {
                if ($plan["tipo_plan"] == 'PLAN3' || $plan["tipo_plan"] == 'PLAN2') {
                    $urlFile = storage_path("app/public")."/$nameFilePlan2";
                    $pdf = PDF::loadView("pdf.contrato-viaggio", $dataSave);
                    $pdf->save($urlFile);
                }
    
                if ($plan["tipo_plan"] == 'PLAN3' || $plan["tipo_plan"] == 'PLAN1') {
                    $urlFile2 = storage_path("app/public")."/$nameFilePlan3";
                    $pdf = PDF::loadView("pdf.contrato-internet", $dataSave);
                    $pdf->save($urlFile2);
                }
            }

            DB::commit();
            return response(['data'=> $dataSave,'code' => 201], 201);

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
