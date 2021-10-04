<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\LoginResource;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);


        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken], 201);
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
}
