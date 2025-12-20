<?php

namespace App\Http\Controllers;

use App\Models\Contratos;
use App\Models\ContratosApartamento;
use App\Models\ContratosInternetEmp;
use App\Models\ContratosAguaEm;
use App\Models\ContratosEnergiaEmp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PDF;
use DB;

class ContratosApartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $contratosApartamentos = ContratosApartamento::where([['id_empresa', '=', $request->id_empresa], ['estado', '=', 'NUEVO']])->get();

        return response()->json(['data' => $contratosApartamentos, 'code' => 200], 200);
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
            $info = $request->all();
           

            if (!empty($info['fecha_traslado'])) {
                try {
                    $info["fecha_traslado"] = Carbon::parse($info['fecha_traslado'])->format('Y-m-d');
                } catch (\Exception $ex) {
                    $info["fecha_traslado"] = null;
                }
            } else {
                $info["fecha_traslado"] = null;
            }

         

            $dataSave = $info; //array_merge($info);
            DB::beginTransaction();

            $documento = ContratosApartamento::create($dataSave);
            DB::commit();
            return response(['data'=> $documento,'code' => 201], 201);

        } catch (\Exception $e) 
        {
            Log::error($e);
            DB::rollBack();
            return response(['data'=> $e->getMessage(), 'code' => 500], 500);  
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        try 
        { 
            $info = $request->all();
            
            /* Fecha para la vista */
            $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
            $fecha = Carbon::now();
            $mes = $meses[($fecha->format('n')) - 1];
            $plan['fecha_texto'] = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

            /* $info["fecha_registro"] = $fecha; */
            $dataSave = [];
            try {
                $dataSave = array_merge($info["data"] ? $info["data"] : $info, $plan, $info["servicio"] ? $info["servicio"] : []);
            } catch (\Throwable $th) {
                $dataSave = array_merge($info, $plan);
            }
            
            DB::beginTransaction();



            /* Nombres Contratos */
            $nameFilePlan2 = "empresa-contrato-energia-".time().".pdf";
            $nameFilePlanAgua = "empresa-contrato-agua-".time().".pdf";
            $nameFilePlanInternet = "empresa-contrato-internet".time().".pdf";

            if ($request["tipo"] == "ENERGIA") {
                $dataSave["file_name_contrato"] = $nameFilePlan2;
              

            } elseif($request["tipo"] == "AGUA" && $dataSave["tipo_proyecto"] == 'VIVO 4') {
                $dataSave["file_name_agua"] = $nameFilePlanAgua;
             

            } elseif($request["tipo"] == "INTERNET") {
                $dataSave["file_name_internet"] = $nameFilePlanInternet;
             
            } else {
                return response(['data'=> [],'code' => 400], 400);
            }

            switch ($request["tipo"]) {
                case 'INTERNET':
                    $contratoInternet = ContratosInternetEmp::orderBy('id', 'desc')->first();
                    $correlativoInternet = $contratoInternet->correlativo_internet; 
                    $nuevoValor = $correlativoInternet + 1;
                    $dataSave["correlativo_internet"] = $nuevoValor;
                    break;
                case 'AGUA':
                    $contratoAgua = ContratosAguaEm::orderBy('id', 'desc')->first();
                    $correlativoAgua = $contratoAgua->correlativo_agua; 
                    $nuevoValor1 = $correlativoAgua + 1;
                    $dataSave["correlativo_agua"] = $nuevoValor1;
                    break;
                case 'ENERGIA':
                    $contratoEnergia = ContratosEnergiaEmp::orderBy('id', 'desc')->first();
                    $correlativoEnergia = $contratoEnergia->correlativo_energia; 
                    $nuevoValors = $correlativoEnergia + 1;
                    $dataSave["correlativo_energia"] = $nuevoValors;
                    break;
                default:
                  
                    break;
            }

            $contratoEmpresarial = ContratosApartamento::findOrfail($dataSave["id"]);

            $usuario = User::findOrfail($contratoEmpresarial->id_empresa);

            $datosEmpresa = Contratos::where([[ 'email', '=', $usuario->email ]])->first();

            $contratoEmpresarial->update($dataSave);

            /* Calculo de Edad */
            $actual = Carbon::now();
            //$actual->diffForHumans($datosEmpresa->fecha_nacimiento, $actual);

            //return response();

            $dataView = [
                'empresa' => $datosEmpresa,
                'apartamento' => $contratoEmpresarial,
                'edad' => $actual->diffInYears(Carbon::parse($datosEmpresa["fecha_nacimiento"])->format('Y-m-d'), $actual->format('Y-m-d')),
                'fecha_texto' => $plan['fecha_texto']
            ];

            /* $nameFilePlan3 = "contrato-internet-".time().".pdf";
            $dataSave["file_name"] = ($plan["tipo_plan"] == 'PLAN3' || $plan["tipo_plan"] == 'PLAN1') ? $nameFilePlan3 : '';
            
            $dataSave["file_name_contrato"] = ($plan["tipo_plan"] == 'PLAN3' || $plan["tipo_plan"] == 'PLAN2') ? $nameFilePlan2 : ''; */

            
            if ($request["tipo"] == "ENERGIA") {
                if ($dataSave["tipo_proyecto"] == 'VIVO 4') {
                    $urlFile = storage_path("app/public")."/$nameFilePlan2";
                    $pdf = PDF::loadView("pdf.contrato-vivo4-energia-empresa", $dataView);
                    $pdf->save($urlFile);
                }

                if ($dataSave["tipo_proyecto"] == 'VIAGGIO') {
                    $urlFile = storage_path("app/public")."/$nameFilePlan2";
                    $pdf = PDF::loadView("pdf.contrato-viaggio-energia-empresa", $dataView);
                    $pdf->save($urlFile);
                }
            } elseif($request["tipo"] == "AGUA" && $dataSave["tipo_proyecto"] == 'VIVO 4') {
                if ($dataSave["tipo_proyecto"] == 'VIVO 4') {
                    $urlFile = storage_path("app/public")."/$nameFilePlanAgua";
                    $pdf = PDF::loadView("pdf.contrato-agua-empresarial", $dataView);
                    $pdf->save($urlFile);
                }
            } elseif($request["tipo"] == "INTERNET") {
                $urlFile = storage_path("app/public")."/$nameFilePlanInternet";
                $pdf = PDF::loadView("pdf.contrato-internet-empresarial", $dataView);
                $pdf->save($urlFile);
            } else {
                return response(['data'=> $dataView,'code' => 400], 400);
            }

            DB::commit();
            return response(['data'=> $dataView,'code' => 200], 200);

         } catch (\Exception $e) 
        {
            Log::error($e);
            DB::rollBack();
            return response(['data'=> $e->getMessage(), 'code' => 500], 500);  
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContratosApartamento  $contratosApartamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try 
        {
            $info = $request->all();
            
            /* Fecha para la vista */

            $info["fecha_traslado"] = Carbon::parse($info['fecha_traslado'])->format('Y-m-d');

            $dataSave = $info; 
            DB::beginTransaction();

            /* $nameFilePlan3 = "contrato-internet-".time().".pdf";
            $dataSave["file_name"] = ($plan["tipo_plan"] == 'PLAN3' || $plan["tipo_plan"] == 'PLAN1') ? $nameFilePlan3 : '';
            $nameFilePlan2 = "contrato-agua-y-energia-".time().".pdf";
            $dataSave["file_name_contrato"] = ($plan["tipo_plan"] == 'PLAN3' || $plan["tipo_plan"] == 'PLAN2') ? $nameFilePlan2 : ''; */
            $documento = ContratosApartamento::findOrfail($dataSave["id"]);
            
            $documento->update($dataSave);

            DB::commit();
            return response(['data'=> $documento,'code' => 200], 200);

        } catch (\Exception $e) 
        {
            Log::error($e);
            DB::rollBack();
            return response(['data'=> $e->getMessage(), 'code' => 500], 500);  
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContratosApartamento  $contratosApartamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContratosApartamento $contratosApartamento)
    {
        //
    }
}
