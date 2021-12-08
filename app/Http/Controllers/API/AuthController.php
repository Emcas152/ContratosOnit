<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\UserInvoice;
use App\Models\Apartamento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Resources\LoginResource;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterUser;
use App\Mail\ForgotUser;
use DB;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $newUser = new User;
            $newUser->name = $request->name;
            $newUser->password = Hash::make($request->password);
            $newUser->email = $request->email;
            $newUser->id_condominio = $request->id_condominio;
            $newUser->estado = $request->estado;
            $newUser->save();

            $apartamento = [];

            if(trim($request->id_apartamento) != ''){
                $apartamento = Apartamento::findOrfail($request->id_apartamento);
                $apartamento->id_inquilino = $newUser->id;
                $apartamento->update();
            }
            
            $newUser->assignRole('client');
            $accessToken = $newUser->createToken('authToken')->accessToken;

            UserInvoice::create([
                'id_usuario' => $newUser->id,
                'nit' => 'C/F',
                'estado' => 'ACT'
            ]);

            $data = [
                'nombre' => $newUser->name
            ];

            if ($newUser->email) {
                Mail::to($newUser->email)
                        ->send(new RegisterUser($data, "Registro - Nuevo Usuario", ''));
            }

            DB::commit();
            return response(['data'=> $newUser, 'access_token' => $accessToken,'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al realiar el registro','code' => 500]);   
        }
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['email' => 'Verifique credenciales'], 400);
        }

        if(auth()->user()->estado != 'ACT')
        {
            return response(['message' => 'El usuario se encuentra deshabilitado, check your details'], 401);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        //$user = User::with('roles')->find(auth()->id());

        return response(['userData' => new LoginResource(auth()->user()), 'access_token' => $accessToken]);
     
    }

    public function forgot(Request $request)
    {
        try {
            DB::beginTransaction();
            $newPassword = rand();
            $user = User::where('email', '=', $request->email)->first();
            $user->password = Hash::make($newPassword);
            $user->update();
            if ($user->email) {
                $data = $user->toArray();
                $data['newPassword'] = $newPassword;
                Mail::to($user->email)
                        ->send(new ForgotUser($data, "Restablecimiento de Contraseña", ''));
            }
            DB::commit();

            return response(['data' => 'Se ha enviado el correo']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['data' => 'Error de envío'], 500);
        }
        
    }
}
