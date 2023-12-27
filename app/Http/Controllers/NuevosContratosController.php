<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contratos;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
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


            /* Fecha para la vista */

            $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
            $fecha = Carbon::now();
            $mes = $meses[($fecha->format('n')) - 1];
            $servicio['fecha_texto'] = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');
            $servicio['tipo_plan'] = $plan;
            /* Fin Fecha para la vista */
            $info["status"] = "PEN";
            $info["fecha_traslado"] = Carbon::parse($info['fecha_traslado'])->format('Y-m-d');
            $info["fecha_registro"] = $fecha;
           
            $dataSave = array_merge($info,$servicio,$tipo_proyecto,$tipo_apartamento,$torre,$facturacion,$servicio_agua);
            DB::beginTransaction();

            $nameInternet = "contrato-internet-".time().".pdf";
            $nameAgua = "contrato-agua-".time().".pdf";
            $nameEnergia = "contrato-energia-".time().".pdf";

            $dataSave["file_name"] = ($plan == 'PLAN3' || $plan == 'PLAN1' || $plan == 'PLAN6' || $plan == 'PLAN7') ? $nameInternet : '';
            $dataSave["file_name_agua"] = ($plan == 'PLAN2' || $plan == 'PLAN3' || $plan == 'PLAN4' || $plan == 'PLAN6') ? $nameAgua : '';
            $dataSave["file_name_energia"] = ($plan == 'PLAN2' || $plan == 'PLAN3' || $plan == 'PLAN5' || $plan == 'PLAN7') ? $nameEnergia : '';



          /*   if ($dataSave["tipo_proyecto"] == 'VIVO 4') {

                $dataSave["file_name_agua"] = ($plan == 'PLAN3' || $plan == 'PLAN2' || $plan == 'PLAN4') ? $nameFilePlan4 : '';
                $dataSave["file_name_energia"] = ($plan == 'PLAN3' || $plan == 'PLAN2' || $plan == 'PLAN5') ? $nameFilePlan5 : '';

            } else {
                $dataSave["file_name_agua"] = ($plan == 'PLAN4') ? $nameFilePlan4 : '';
                $dataSave["file_name_energia"] = ($plan == 'PLAN3' || $plan == 'PLAN5') ? $nameFilePlan5 : '';
            }
 */
            $dataSave["file_name_contrato"] = '';

            $documento = Contratos::create($dataSave);

            $dataSave["id"] = $documento->id;
    
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
