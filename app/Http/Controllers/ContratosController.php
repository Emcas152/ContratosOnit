<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contratos;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use DB;

class ContratosController extends Controller
{
    private function getB64Image($base64_image){  
        // Obtener el String base-64 de los datos         
        $image_service_str = substr($base64_image, strpos($base64_image, ",")+1);
        // Decodificar ese string y devolver los datos de la imagen        
        $image = base64_decode($image_service_str);   
        // Retornamos el string decodificado
        return $image;
    }

    private function uploadDocument(Request $request, $info = [], $campo = '')
    {
        $estado = false;
        if (!$request->hasFile("$campo") && trim($request->$campo) != '' && trim(strpos($request->$campo,"storage")) == "") {
            $image = $this->getB64Image($request->$campo);
            $extension = 'pdf';
            $imageName = $campo.rand(1, 100).time().'.'.$extension;
            Storage::disk('public')->put($imageName, $image);
            $url = Storage::url($imageName);
            $info["$campo"] = $imageName;
            $estado = true;
        }

        return $estado ? $url : '';
    }

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
                    $pdf = PDF::loadView("pdf.contrato-vivo4", $dataSave);
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

    public function register(Request $request)
    {
        try {
            $data = $request->all();
            DB::beginTransaction();

            $newUser = new User;
            $newUser->name = $request->nombre;
            $newUser->password = Hash::make($request->password);
            $newUser->email = $request->email;
            $newUser->estado = 'ACT';
            $newUser->save();

            $newUser->assignRole('client');
            $accessToken = $newUser->createToken('authToken')->accessToken;

            $data["fecha_nacimiento"] = Carbon::parse($data['fecha_nacimiento'])->format('Y-m-d');
            $data["path_representacion"] = $this->uploadDocument($request, $data, 'path_representacion');

            $data["path_copia_dpi"] = $this->uploadDocument($request, $data, 'path_copia_dpi');

            $documento = Contratos::create($data);

            DB::commit();
            return response()->json(['data'=> $documento, 'user'=> $newUser, 'access_token' => $accessToken, 'code' => 201], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['data'=> 'Error al registrarse', 'code' => 500], 500);
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
    
}
