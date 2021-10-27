<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use App\Http\Resources\UsersResource;
use App\Http\Resources\UsuarioSelectResource;
use App\Http\Requests\UsersFormRequest;
use Illuminate\Support\Facades\Hash;
use DB;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('id','DESC')->get();

        if (!count($users)) 
        {
           return response(['data' => '','code'=>204]);  
        }

        return response(['data'=> UsersResource::collection($users),'code' => 200]);
    }

    public function show(){

        $users = User::all();

        if (!count($users)) 
        {
           return response(['data' => '','code'=>204]);  
        }
        return response(['data'=> UsuarioSelectResource::collection($users),'code' => 200]);
    }

    public function store(UsersFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $newUser = new User;
            $newUser->name = $request->get('name');
            $newUser->password = Hash::make($request->get('password'));
            $newUser->email = $request->get('email');
            $newUser->id_condominio = $request->id_condominio;
            $newUser->estado = 'ACT';
            $newUser->save();
            
            $newUser->assignRole(User::getStoredRole($request->get('role_id'))->name);
            $accessToken = $newUser->createToken('authToken')->accessToken;

            DB::commit();
            return response(['data'=> $newUser, 'access_token' => $accessToken,'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear usuario','code' => 500]);   
        }
    }
    public function update(Request $request, $id_user)
    {
        try
        {
            $user = User::findOrFail($id_user);
            DB::beginTransaction();
            if($user->password != $request->get('password'))
            {
                $user->password = Hash::make($request->get('password'));
            }
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->id_condominio = $request->id_condominio;
            $user->update();
            DB::commit();
            return response(['data'=> new UsersResource($user),'code' => 200]);
            
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos del usuario','code' => 500]); 
        }
    }

    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $user = User::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial',$user->estado],['tabla','users']])->firstOrFail();
            $user->estado = $estado->sts_final;
            $user->save();
            DB::commit();
            return response(['data'=> new UsersResource($user),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }


}
