<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use App\Models\EstadosProcesos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\EmpleadosResource;
use App\Http\Requests\EmpleadosFormCreateRequest;
use App\Http\Requests\EmpleadosFormUpdateRequest;
use DB;

class EmpleadoController extends Controller
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
        $queryUrl = trim($request->searchText);
        $pagination = $request->paginate;
        $role = $request->role;
        $usuario = $request->usuario_id;
        $condominio = $request->id_condominio;
        $empleados = Empleado::join('users','users.id','empleados.id_usuario')
                                    ->select('empleados.*')
                                    ->where([['puesto', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['users.telefono', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['users.name', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['empleados.estado', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orderBy('empleados.id','DESC')
                                    ->paginate($pagination);
        
        if (!count($empleados)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> EmpleadosResource::collection($empleados),'per_page' => $empleados->perPage(),'total' => $empleados->total()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmpleadosFormCreateRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $newUser = new User;
            $newUser->name = $request->name;
            $newUser->password = Hash::make($request->password);
            $newUser->email = $request->email;
            $newUser->id_condominio = $request->id_condominio;
            $newUser->telefono = $request->telefono;
            $newUser->estado = 'ACT';
            $newUser->save();
        
            $newUser->assignRole($request->rol);
            $accessToken = $newUser->createToken('authToken')->accessToken;

            $empleado = Empleado::create([
                'id_usuario' => $newUser->id,
                'puesto' => $request->puesto,
                'rol' => $request->rol,
                'estado' => 'ACT'
            ]);

            DB::commit();
            return response(['data'=> new EmpleadosResource($empleado), 'access_token' => $accessToken,'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear usuario','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function showById(Request $request)
    {
        $condominio = $request->id_condominio;
        $empleado = Empleado::join('users','users.id','empleados.id_usuario')
                                    ->select('empleados.*')
                                    ->where([['users.id_condominio', '=', $condominio],['empleados.id', '=', $request->id]])
                                    ->get();
        
        if (!count($empleado)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> EmpleadosResource::collection($empleado)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $queryUrl = trim($request->searchText);
        $pagination = $request->paginate;
        $role = $request->role;
        $usuario = $request->usuario_id;
        $condominio = $request->id_condominio;
        $empleados = Empleado::join('users','users.id','empleados.id_usuario')
                                    ->select('empleados.*')
                                    ->where([['puesto', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio],['empleados.estado', '=', 'ACT']])
                                    ->orWhere([['empleados.estado', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio],['empleados.estado', '=', 'ACT']])
                                    ->orderBy('empleados.id','DESC')
                                    ->paginate($pagination);
        
        if (!count($empleados)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> EmpleadosResource::collection($empleados),'per_page' => $empleados->perPage(),'total' => $empleados->total()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(EmpleadosFormUpdateRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $empleado = Empleado::findOrFail($request->id);
            $oldRole = $empleado->rol;
            $empleado->rol = $request->rol;
            $empleado->puesto = $request->puesto;
            $empleado->save();


            $user = User::findOrFail($empleado->id_usuario);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->telefono = $request->telefono;

            try {
                if (!$request->hasFile('path_img')) {
                    $image = $this->getB64Image($request->path_img);
                    $extension = $this->getB64Extension($request->path_img);
                    $imageName = time().'.'.$extension;
                    Storage::disk('public')->put($imageName, $image);
                    $url = Storage::url($imageName);
                    $user->path_img = $url;
                }
            } catch (\Throwable $th) {
                
            }

            $user->save();
            
            if($oldRole !== $empleado->rol){
                $user->removeRole($oldRole);
                $user->assignRole($request->rol);
            }

            

            DB::commit();
            return response(['data'=> new EmpleadosResource($empleado),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear usuario','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $empleado = Empleado::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial',$empleado->estado],['tabla','empleados']])->firstOrFail();
            $empleado->estado = $estado->sts_final;
            $empleado->save();

            $user = User::findOrFail($empleado->id_usuario);
            $estado = EstadosProcesos::where([['sts_inicial',$user->estado],['tabla','users']])->firstOrFail();
            $user->estado = $estado->sts_final;
            $user->save();
            DB::commit();
            return response(['data'=> new EmpleadosResource($empleado),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }
}
