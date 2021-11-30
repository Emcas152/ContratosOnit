<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProveedorResource;
use App\Http\Requests\ProveedorFormRequest;
use Carbon\Carbon;
use DB;

class SupplierController extends Controller
{
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
    public function store(ProveedorFormRequest $request)
    {
        try 
        {
            $mytime = Carbon::now();
            DB::beginTransaction();
            $newUser = new User;
            $newUser->name = $request->nombre;
            $newUser->password = Hash::make($request->password);
            $newUser->email = $request->email;
            $newUser->id_condominio = $request->id_condominio;
            $newUser->telefono = $request->telefono;
            $newUser->estado = 'ACT';
            $newUser->save();
        
            $newUser->assignRole('proveedor');
            $accessToken = $newUser->createToken('authToken')->accessToken;

            $imgPerfilOld = $proveedor->img_perfil;
            if ($request->hasFile('img_perfil')) {
                $request->validate([
                    'img_perfil' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
                ]);
                $imageName = time().'.'.$request->img_perfil->extension();
                $request->img_perfil->storeAs('/public', $imageName);
                $url = Storage::url($imageName);
                $imgPerfilOld = $url;
            }
            $imgHeaderOld = $proveedor->img_header;
            if ($request->hasFile('img_header')) {
                $request->validate([
                    'img_header' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
                ]);
                $imageName = time().'.'.$request->img_header->extension();
                $request->img_header->storeAs('/public', $imageName);
                $url = Storage::url($imageName);
                $imgHeaderOld = $url;
            }

            $proveedor = Supplier::create([
                'id_usuario' => $newUser->id,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'direccion' => $request->direccion,
                'informacion_general' => $request->informacion_general,
                'fecha_creacion' => $mytime->format('Y-m-d'),
                'img_perfil' => $imgPerfilOld,
                'img_header' => $imgHeaderOld,
                'pagina_web' => $request->pagina_web,
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
    public function update(ProveedorFormRequest $request)
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
            $proveedor->puesto = $request->puesto;
            $imgPerfilOld = $proveedor->img_perfil;
            if ($request->hasFile('img_perfil')) {
                $request->validate([
                    'img_perfil' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
                ]);
                $imageName = time().'.'.$request->img_perfil->extension();
                $request->img_perfil->storeAs('/public', $imageName);
                $url = Storage::url($imageName);
                $proveedor->img_perfil = $url;
            }
            $imgHeaderOld = $proveedor->img_header;
            if ($request->hasFile('img_header')) {
                $request->validate([
                    'img_header' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
                ]);
                $imageName = time().'.'.$request->img_header->extension();
                $request->img_header->storeAs('/public', $imageName);
                $url = Storage::url($imageName);
                $proveedor->img_header = $url;
            }
            $proveedor->save();


            $user = User::findOrFail($proveedor->id_usuario);
            $user->name = $request->nombre;
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
