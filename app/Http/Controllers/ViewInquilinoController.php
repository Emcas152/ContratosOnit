<?php

namespace App\Http\Controllers;

use App\Models\ViewInquilino;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ViewInquilinoController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ViewInquilino  $viewInquilino
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $role = $request->role;
        $usuario = $request->id_usuario;
        $condominio = $request->id_condominio;

        $inqulinos = [];

        $inqulinos = ViewInquilino::where([['id_condominio', '=', $condominio]])->get(['id', 'nombre']);

        if (!count($inqulinos)) {
            return response(['data' => [],'code'=>204]);  
        }

        return response(['data'=> $inqulinos, 'code' => 200]);  
    }
}
