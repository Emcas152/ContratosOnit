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
                                    ->where([['users_invoice.id', '=', $request->id],['users.id_condominio', '=', $condominio]])
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
            if ($request->hasFile('path_img')) {
                $request->validate([
                    'path_img' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
                ]);
                $imageName = time().'.'.$request->path_img->extension();
                $request->path_img->storeAs('/public', $imageName);
                $url = Storage::url($imageName);
                $user->path_img = $url;
            }
            if($user->password != $request->password)
            {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            
            DB::commit();
            return response(['data'=> new UsuarioInvoiceResource($userInvoice),'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar Datos de Cliente','code' => 500]);   
        }
    }

}
