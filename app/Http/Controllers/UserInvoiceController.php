<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInvoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\UsuarioInvoiceResource;
use App\Http\Requests\UsuarioInvoiceFormRequest;
use DB;

class UserInvoiceController extends Controller
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
        $userInvoice = UserInvoice::join('users','users.id','users_invoice.id_usuario')
                                    ->select('users_invoice.*')
                                    ->where([['nombre', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->where([['direccion', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->where([['nit', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['users.telefono', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['users.name', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orWhere([['users_invoice.estado', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                    ->orderBy('users_invoice.id','DESC')
                                    ->paginate($pagination);
        
        if (!count($userInvoice)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> UsuarioInvoiceResource::collection($userInvoice),'per_page' => $userInvoice->perPage(),'total' => $userInvoice->total()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserInvoice  $userInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $role = $request->role;
        $usuario = $request->usuario_id;
        $condominio = $request->id_condominio;
        $userInvoice = UserInvoice::join('users','users.id','users_invoice.id_usuario')
                                    ->select('users_invoice.*')
                                    ->where([['users_invoice.id_usuario', '=', $request->id],['users.id_condominio', '=', $condominio]])
                                    ->get();
        
        if (!count($userInvoice)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> UsuarioInvoiceResource::collection($userInvoice)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserInvoice  $userInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(UsuarioInvoiceFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $userInvoice = UserInvoice::findOrFail($request->id);
            $userInvoice->nombre = $request->nombre;
            $userInvoice->direccion = $request->direccion;
            $userInvoice->nit = $request->nit;
            $userInvoice->dpi = $request->dpi;
            $userInvoice->save();


            $user = User::findOrFail($userInvoice->id_usuario);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->telefono = $request->telefono;
            $pathImgOld = $user->path_img;
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
            if($user->password != $request->password  && trim($request->password) != '')
            {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            
            DB::commit();
            return response(['data'=> new UsuarioInvoiceResource($userInvoice),'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar Datos de Cliente Facturacion','code' => 500]);   
        }
    }

}
