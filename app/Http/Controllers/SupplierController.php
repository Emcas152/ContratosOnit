<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProveedorResource;
use App\Http\Requests\ProveedorFormCreateRequest;
use App\Http\Requests\ProveedorFormUpdateRequest;
use Carbon\Carbon;
use DB;

class SupplierController extends Controller
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
        $proveedores = Supplier::join('users','users.id','proveedores.id_usuario')
                                    ->select('proveedores.*')
                                    ->where([['descripcion', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['users.telefono', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['users.name', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['proveedores.nombre', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['proveedores.direccion', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['proveedores.informacion_general', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['proveedores.fecha_creacion', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['proveedores.pagina_web', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['proveedores.estado', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orderBy('proveedores.id','DESC')
                                    ->paginate($pagination);
        
        if (!count($proveedores)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> ProveedorResource::collection($proveedores),'per_page' => $proveedores->perPage(),'total' => $proveedores->total()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProveedorFormCreateRequest $request)
    {
        try 
        {
            $mytime = Carbon::now();
            DB::beginTransaction();
            $newUser = new User;
            $newUser->name = $request->name;
            $newUser->password = Hash::make($request->password);
            $newUser->email = $request->email;
            $newUser->id_condominio = $request->id_condominio;
            $newUser->telefono = $request->telefono;
            $newUser->estado = 'ACT';
            $newUser->save();
        
            $newUser->assignRole('proveedor');
            $accessToken = $newUser->createToken('authToken')->accessToken;

            $proveedor = Supplier::create([
                'id_usuario' => $newUser->id,
                'nombre' => $request->nombre,
                'fecha_creacion' => $mytime->format('Y-m-d'),
                'estado' => 'ACT'
            ]);

            DB::commit();
            return response(['data'=> new ProveedorResource($proveedor), 'access_token' => $accessToken,'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear Proveedor','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $condominio = $request->id_condominio;
        $proveedor = Supplier::join('users','users.id','proveedores.id_usuario')
                                    ->select('proveedores.*')
                                    ->where([['users.id_condominio', '=', $condominio],['proveedores.id_usuario', '=', $request->usuario_id]])
                                    ->get();
        
        if (!count($proveedor)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> ProveedorResource::collection($proveedor)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(ProveedorFormUpdateRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $proveedor = Supplier::findOrFail($request->id);
            $proveedor->nombre = $request->nombre;
            $proveedor->descripcion = $request->descripcion;
            $proveedor->direccion = $request->direccion;
            $proveedor->informacion_general = $request->informacion_general;
            $proveedor->pagina_web = $request->pagina_web;
            $pathPerfilOld = $proveedor->path_perfil;
            try {
                if (!$request->hasFile('path_perfil')) {
                    $image = $this->getB64Image($request->path_perfil);
                    $extension = $this->getB64Extension($request->path_perfil);
                    $imageName = time().'.'.$extension;
                    Storage::disk('public')->put($imageName, $image);
                    $url = Storage::url($imageName);
                    $proveedor->path_perfil = $url;
                }
            } catch (\Throwable $th) {
                
            }
            $pathHeaderOld = $proveedor->path_header;
            try {
                if (!$request->hasFile('path_header')) {
                    $image = $this->getB64Image($request->path_header);
                    $extension = $this->getB64Extension($request->path_header);
                    $imageName = time().'.'.$extension;
                    Storage::disk('public')->put($imageName, $image);
                    $url = Storage::url($imageName);
                    $proveedor->path_header = $url;
                }
            } catch (\Throwable $th) {
                
            }
            $proveedor->save();

            $user = User::findOrFail($proveedor->id_usuario);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->telefono = $request->telefono;
            if($user->password != $request->password)
            {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            
            DB::commit();
            return response(['data'=> new ProveedorResource($proveedor),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar Proveedor','code' => 500]);   
        }
    }

    public function change_state(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $action = $request->action;
            $proveedor = Supplier::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial',$proveedor->estado],
                                                ['proceso',$action],
                                                ['tabla','proveedores']])->firstOrFail();
            $proveedor->estado = $estado->sts_final;
            $proveedor->save();

            $user = User::findOrFail($proveedor->id_usuario);
            $estado = EstadosProcesos::where([['sts_inicial',$user->estado],
                                                ['proceso',$action],
                                                ['tabla','users']])->firstOrFail();
            $user->estado = $estado->sts_final;
            $user->save();
            DB::commit();
            return response(['data'=> new ProveedorResource($proveedor),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
