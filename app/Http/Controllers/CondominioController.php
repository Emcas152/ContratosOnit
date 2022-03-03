<?php

namespace App\Http\Controllers;

use App\Models\Condominio;
use App\Models\User;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CondominioResource;
use App\Http\Resources\CondominioSelectResource;
use App\Http\Requests\CondominioFormRequest;
use DB;

class CondominioController extends Controller
{
    private function getB64Image($base64_image){  
        // Obtener el String base-64 de los datos         
        $image_service_str = substr($base64_image, strpos($base64_image, ",")+1);
        // Decodificar ese string y devolver los datos de la imagen        
        $image = base64_decode($image_service_str);   
        // Retornamos el string decodificado
        return $image;
    }

    private function getB64Extension($base64_image, $full=null){  
        // Obtener mediante una expresión regular la extensión imagen y guardarla
        // en la variable "img_extension"        
        preg_match("/^data:image\/(.*);base64/i",$base64_image, $img_extension);   
        // Dependiendo si se pide la extensión completa o no retornar el arreglo con
        // los datos de la extensión en la posición 0 - 1
        return ($full) ?  $img_extension[0] : $img_extension[1];  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $usuario = trim($request->usuario_id);
        $queryUrl = trim($request->searchText);
        $pagination = $request->paginate;
        $condominioResult = Condominio::where([['nombre', 'LIKE', '%'.$queryUrl.'%'],['id_usuario', '=', $usuario]])
                                        ->orWhere([['parqueo_visitantes', 'LIKE', '%'.$queryUrl.'%'],['id_usuario', '=', $usuario]])
                                        ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%'],['id_usuario', '=', $usuario]])
                                        ->orderBy('id','DESC')
                                        ->paginate($pagination);
        if (!count($condominioResult)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> CondominioResource::collection($condominioResult),'per_page' => $condominioResult->perPage(),'total' => $condominioResult->total()]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CondominioFormRequest $request)
    {
        try 
        {
            $user = User::findOrFail($request->id_usuario);
            DB::beginTransaction();
            $data = $request->all();
            try {
                if (!$request->hasFile('path_imagen')) {
                    $image = $this->getB64Image($request->path_imagen);
                    $extension = $this->getB64Extension($request->path_imagen);
                    $imageName = time().'.'.$extension;
                    Storage::disk('public')->put($imageName, $image);
                    $url = Storage::url($imageName);
                    $data["path_imagen"] = $url;
                }
            } catch (\Throwable $th) {
                
            }

            $condominio = Condominio::create($data);
            $user->id_condominio = $condominio->id;
            $user->update();
            DB::commit();
            return response(['data'=> new CondominioResource($condominio),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear el condominio','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Condominio  $condominio
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $condominios = Condominio::all();

        if (!count($condominios)) 
        {
           return response(['data' => [],'code'=>204]);   
        }
        return response(['data'=> CondominioSelectResource::collection($condominios),'code' => 200]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Condominio  $condominio
     * @return \Illuminate\Http\Response
     */
    public function update(CondominioFormRequest $request, Condominio $condominio)
    {
        try 
        {
            DB::beginTransaction();
            $condominio = Condominio::findOrFail($request->id);
            $data = $request->all();
            try {
                if (!$request->hasFile('path_imagen')) {
                    $image = $this->getB64Image($request->path_imagen);
                    $extension = $this->getB64Extension($request->path_imagen);
                    $imageName = time().'.'.$extension;
                    Storage::disk('public')->put($imageName, $image);
                    $url = Storage::url($imageName);
                    $data["path_imagen"] = $url;
                }
            } catch (\Throwable $th) {
                
            }

            $condominio->update($data);
            DB::commit();
            return response(['data'=> new CondominioResource($condominio),'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Condominio  $condominio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $condominio = Condominio::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial',$condominio->estado],['tabla','condominios']])->firstOrFail();
            $condominio->estado = $estado->sts_final;
            $condominio->update();
            DB::commit();
            return response(['data'=> new CondominioResource($condominio),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }
}
