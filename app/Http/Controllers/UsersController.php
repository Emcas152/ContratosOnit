<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInvoice;
use App\Models\Roles;
use App\Models\Apartamento;
use App\Models\ViewParametros;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use App\Http\Resources\UsersResource;
use App\Http\Resources\UsuarioSelectResource;
use App\Http\Requests\UsersFormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivateUser;
use DB;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $except = ViewParametros::where([['codigo_enc', '=', 'ROLEMP']])->pluck('codigo_det');
        $exceptRoles = Roles::whereIn('name', $except)->pluck('id');
        $queryUrl = trim($request->searchText);
        $pagination = $request->paginate;
        $condominio = $request->id_condominio;
        $users = User::join('model_has_roles','model_has_roles.model_id','users.id')
                        ->select('users.*')
                        ->whereNotIn('model_has_roles.role_id', $exceptRoles)
                        ->whereNotIn('model_has_roles.role_id', [4])
                        ->where(function($query) use($queryUrl,$condominio) {
                            $query->where([['name', 'LIKE', '%'.$queryUrl.'%'],['id_condominio', '=', $condominio]])
                            ->orWhere([['email', 'LIKE', '%'.$queryUrl.'%'],['id_condominio', '=', $condominio]])
                            ->orWhere([['telefono', 'LIKE', '%'.$queryUrl.'%'],['id_condominio', '=', $condominio]]);
                        })
                        ->orderBy('id','DESC')
                        ->paginate($pagination);

        if (!count($users)) 
        {
           return response(['data' => [],'code'=>204]);  
        }

        return response(['data'=> UsersResource::collection($users),'per_page' => $users->perPage(),'total' => $users->total(), 'code' => 200]);
    }

    public function show(Request $request){

        $users = User::all();

        if (!count($users)) 
        {
           return response(['data' => [],'code'=>204]);  
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
            $newUser->telefono = $request->telefono;
            $newUser->estado = 'ACT';
            $newUser->save();

            $apartamento = [];

            if(trim($request->id_apartamento) != ''){
                $apartamento = Apartamento::findOrfail($request->id_apartamento);
                $apartamento->id_inquilino = $newUser->id;
                $apartamento->update();
            }
            
            $newUser->assignRole(User::getStoredRole($request->get('role_id'))->name);
            $accessToken = $newUser->createToken('authToken')->accessToken;

            if (User::getStoredRole($request->get('role_id'))->name == 'client') {
                UserInvoice::create([
                    'id_usuario' => $newUser->id,
                    'nit' => 'C/F',
                    'estado' => 'ACT'
                ]);
            }

            DB::commit();
            return response(['data'=> new UsersResource($newUser), 'access_token' => $accessToken,'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear usuario','code' => 500]);   
        }
    }
    public function update(Request $request)
    {
        try
        {
            $user = User::findOrFail($request->id);
            DB::beginTransaction();
            if($user->password != $request->get('password'))
            {
                $user->password = Hash::make($request->get('password'));
            }
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->id_condominio = $request->id_condominio;
            $user->telefono = $request->telefono;
            $user->update();

            $userRole = User::join('model_has_roles','model_has_roles.model_id','users.id')
            ->select('users.*','model_has_roles.role_id')
            ->findOrFail($user->id);

            $userRole->removeRole(User::getStoredRole($userRole->role_id)->name);

            $userRole->assignRole(User::getStoredRole($request->role_id)->name);

            $apartamento = [];
            $apartamento = Apartamento::where('id_inquilino','=',$user->id)->first();
            
            if (trim($request->id_apartamento) == '' && $apartamento != null) 
            {
                $apartamento->id_inquilino = null;
                $apartamento->estado = 'ACT';
                $apartamento->update();
            } else if(trim($request->id_apartamento) != '') {
                $apartamentoCambio = Apartamento::findOrfail($request->id_apartamento);
                $apartamentoCambio->id_inquilino = $user->id;
                $apartamentoCambio->estado = 'RSV';
                $apartamentoCambio->update();
            }
            
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
            if ($user->estado == 'PEN') {
                $data = [
                    'nombre' => $user->name
                ];
                if ($user->email) {
                    Mail::to($user->email)
                            ->send(new ActivateUser($data, "Usuario Activado LIFE", ''));
                }
            }
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
