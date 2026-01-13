<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contratos;
use App\Models\ContratosInternet;
use App\Models\ContratosEnergia;
use App\Models\ContratosAgua;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\EstadosProcesos;
use PDF;
use App\Mail\sendContrato;
use DB;
use App\Http\Resources\ContratosNuevosResource;
class NuevosContratosController extends Controller
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
    public function index(Request $request)
    {   
        $queryUrl = trim($request->searchText);
        $pagination = $request->paginate;
        $contratos = Contratos::where([['nombre', 'LIKE', '%'.$queryUrl.'%'],['empresa', '=', 'N']])
                    ->orWhere([['tipo_proyecto', 'LIKE', '%'.$queryUrl.'%'],['empresa', '=', 'N']])
                    ->orWhere([['numero_apartamento', 'LIKE', '%'.$queryUrl.'%'],['empresa', '=', 'N']])
                    ->orWhere([['email', 'LIKE', '%'.$queryUrl.'%'],['empresa','N']])
                    ->orWhere([['nit', 'LIKE', '%'.$queryUrl.'%'],['empresa', '=', 'N']]) 
                    ->orderBy('id','DESC')            
                    ->paginate($pagination);
                    if (!count($contratos)) {
                        return response(['data' => [],'code'=>204]);  
                        
                    }
                    return response(['data'=> ContratosNuevosResource::collection($contratos),'per_page' => $contratos->perPage(),'total' => $contratos->total()]);
    }


    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $contrato = Contratos::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial',$contrato->status],['tabla','contratos']])->firstOrFail();
            $contrato->status = $estado->sts_final;
            $contrato->update();
            DB::commit();
            return response(['data'=> new ContratosNuevosResource($contrato),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
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
            $plan = $request->tipo_plan;
            $tipo_proyecto = $request->tipo_proyecto;
            $torre = $request->torre;
            $tipo_apartamento = $request->tipo_apartamento;
            $facturacion = $request->facturacion;
            $servicio_agua = $request->servicio_agua;

            // Normalizar inputs que a veces vienen como JSON strings o valores simples
            $normalize = function ($v) {
                if (is_array($v)) return $v;
                if (is_string($v)) {
                    $decoded = json_decode($v, true);
                    return is_array($decoded) ? $decoded : [$v];
                }
                return is_null($v) ? [] : (is_object($v) ? (array) $v : [$v]);
            };

            $info = is_array($info) ? $info : (is_object($info) ? (array)$info : (is_string($info) ? json_decode($info, true) ?? [] : []));
            $servicio = $normalize($servicio);
            $tipo_proyecto = $normalize($tipo_proyecto);
            $torre = $normalize($torre);
            $tipo_apartamento = $normalize($tipo_apartamento);
            $facturacion = $normalize($facturacion);
            $servicio_agua = $normalize($servicio_agua);

            // Capturar velocidad y precio antes de que se procesen
            $velocidad_original = isset($servicio['velocidad']) ? trim($servicio['velocidad']) : '';
            $precio_original = isset($servicio['precio']) ? trim($servicio['precio']) : '';

            // Asegurar que $plan sea un string (algunas requests lo envían como array)
            if (is_array($plan)) {
                $plan = isset($plan['tipo_plan']) ? $plan['tipo_plan'] : (count($plan) ? reset($plan) : '');
            }

            $condicion1 = !empty($servicio['tipo_servicio']);
            $condicion2 = !empty($servicio_agua['servicio_agua']);
            $condicion3 = $plan == 'PLAN3' || $plan == 'PLAN2' || $plan == 'PLAN5' || $plan == 'PLAN7'; 
            
            if ($condicion1 || $condicion2 || $condicion3) {
                if ($condicion1) {
                    $contratoInternet = ContratosInternet::orderBy('id', 'desc')->first();
                    $correlativoInternet = $contratoInternet->correlativo_internet; 
                    $nuevoValor = $correlativoInternet + 1;
                    $info["correlativo_internet"] = $nuevoValor;
                }
            
                if ($condicion2) {
                    $contratoAgua = ContratosAgua::orderBy('id', 'desc')->first();
                    $correlativoAgua = $contratoAgua->correlativo_agua; 
                    $nuevoValor1 = $correlativoAgua + 1;
                    $info["correlativo_agua"] = $nuevoValor1;
                }
            
                 if ($condicion3) {
                    $contratoEnergia = ContratosEnergia::orderBy('id', 'desc')->first();
                    $correlativoEnergia = $contratoEnergia->correlativo_energia; 
                    $nuevoValors = $correlativoEnergia + 1;
                    $info["correlativo_energia"] = $nuevoValors;
                } 
            }
           
              
            /* Fecha para la vista */

            $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
            $fecha = Carbon::now();
            $mes = $meses[($fecha->format('n')) - 1];
            $servicio['fecha_texto'] = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');
            $servicio['tipo_plan'] = $plan;
            /* Fin Fecha para la vista */
            log::info($info);
            $info["status"] = "PEN";
            if (!empty($info['fecha_traslado'])) {
                try {
                    $info["fecha_traslado"] = Carbon::parse($info['fecha_traslado'])->format('Y-m-d');
                } catch (\Exception $ex) {
                    $info["fecha_traslado"] = null;
                }
            } else {
                $info["fecha_traslado"] = null;
            }
            $info["fecha_registro"] = $fecha;
           
            $dataSave = array_merge($info,$servicio,$tipo_proyecto,$tipo_apartamento,$torre,$facturacion,$servicio_agua);

            // Sanitizar $dataSave: convertir arrays simples de un solo valor a scalars
            $flatten = function ($value) {
                if (!is_array($value)) return $value;
                // associative single-key arrays: ['key' => 'val']
                if (count($value) === 1) {
                    $first = reset($value);
                    if (!is_array($first)) return $first;
                }
                // numeric single-element arrays: ['val']
                if (count($value) === 1 && isset($value[0]) && !is_array($value[0])) {
                    return $value[0];
                }
                return $value;
            };

            foreach ($dataSave as $k => $v) {
                $dataSave[$k] = $flatten($v);
            }
            DB::beginTransaction();

            $nameInternet = "contrato-internet-".time().".pdf";
            $nameAgua = "contrato-agua-".time().".pdf";
            $nameEnergia = "contrato-energia-".time().".pdf";

            $dataSave["file_name"] = ($plan == 'PLAN3' || $plan == 'PLAN1' || $plan == 'PLAN6' || $plan == 'PLAN7') ? $nameInternet : '';
            $dataSave["file_name_agua"] = ($plan == 'PLAN2' || $plan == 'PLAN3' || $plan == 'PLAN4' || $plan == 'PLAN6') ? $nameAgua : '';
            $dataSave["file_name_energia"] = ($plan == 'PLAN2' || $plan == 'PLAN3' || $plan == 'PLAN5' || $plan == 'PLAN7') ? $nameEnergia : '';


            
            $dataSave["file_name_contrato"] = '';

            // Asegurar valores por defecto que usan las vistas PDF
            $defaults = [
                'tabla0' => '',
                'tabla1' => '',
                'tabla2' => '',
                'tabla3' => '',
                'tabla4' => '',
                'nombre_factura' => '',
                'nit_factura' => '',
                'numero_apartamento' => $dataSave['numero_apartamento'] ?? '',
                'tipo_servicio' => $dataSave['tipo_servicio'] ?? '',
                'tipo_plan' => $dataSave['tipo_plan'] ?? $plan,
                'nacionalidad' => $dataSave['nacionalidad'] ?? '',
                'sexo' => $dataSave['sexo'] ?? '',
                'edad' => $dataSave['edad'] ?? '',
                'velocidad' => $velocidad_original,
                'precio' => $precio_original,
            ];

            $dataSave = array_merge($defaults, $dataSave);

            $documento = Contratos::create($dataSave);

            $dataSave["id"] = $documento->id;
            $dataSave["correlativoInternet"] = $documento->correlativo_internet;
            $dataSave["correlativoAgua"] = $documento->correlativo_agua;
            $dataSave["correlativoEnergia"] = $documento->correlativo_energia;
    
                if ($plan == 'PLAN3' || $plan == 'PLAN1' || $plan == 'PLAN6' || $plan == 'PLAN7') {
                    $urlFile2 = storage_path("app/public")."/$nameInternet";
                    $pdf = PDF::loadView("pdf.contrato-internet", $dataSave);
                    $pdf->save($urlFile2);
                    $dataSave["file_name"] =  $nameInternet;
                }

                if ($plan == 'PLAN3' || $plan == 'PLAN2' || $plan == 'PLAN4' || $plan == 'PLAN6') {
                    $urlFile3 = storage_path("app/public")."/$nameAgua";
                    $pdf = PDF::loadView("pdf.contrato-solo-agua", $dataSave);
                    $pdf->save($urlFile3);
                    $dataSave["file_name_agua"] =  $nameAgua;
                }

                if ($plan == 'PLAN3' || $plan == 'PLAN2' || $plan == 'PLAN5' || $plan == 'PLAN7') {
                    $urlFile1 = storage_path("app/public")."/$nameEnergia";
                    $pdf = PDF::loadView("pdf.contrato-vivo4-solo-energia", $dataSave);
                    $pdf->save($urlFile1);
                    $dataSave["file_name_energia"] = $nameEnergia;
                }

                $email =  $dataSave["email"];
               
                if($email !='')
                {
                    Mail::to($email)
                            ->send(new sendContrato($dataSave, "Contratos Generados", $dataSave));
                }
          

            DB::commit();
            return response(['data'=> $dataSave,'code' => 201], 201);

              } catch (\Exception $e) 
        {
            Log::error($e);
            DB::rollBack();
            return response(['data'=> $e->getMessage(),'code' => 500], 500);   
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
