<?php

namespace App\Http\Controllers;

use App\Models\EstadoCuenta;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\EstadoCuentaResource;
use App\Http\Requests\EstadoCuentaFormRequest;
use Carbon\Carbon;
use DB;

class EstadoCuentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = $request->role;
        $condominio = $request->id_condominio;
        $queryUrl=trim($request->searchText);
        $paginacion = $request->paginate;
        
        $estadoCuentaResult = [];
        if($role == 'admin' || $role == 'superadmin'){
            $estadoCuentaResult = EstadoCuenta::where([['id_condominio','=', $condominio]])
                                                ->where(function($query) use($queryUrl){
                                                    return $query
                                                    ->orWhere([['nombre_pago', 'LIKE', '%'.$queryUrl.'%']])
                                                    ->orWhere([['tipo', 'LIKE', '%'.$queryUrl.'%']])
                                                    ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%']]);
                                                });
                                                
        } else if($role == 'client') {
            $estadoCuentaResult = EstadoCuenta::where([['id_condominio','=', $condominio],['id_apartamento', '=', $request->id_apartamento]])
                                                ->where(function($query) use($queryUrl){
                                                    return $query
                                                    ->orWhere([['nombre_pago', 'LIKE', '%'.$queryUrl.'%']])
                                                    ->orWhere([['tipo', 'LIKE', '%'.$queryUrl.'%']])
                                                    ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%']]);
                                                });
        }

        $estadoCuentaResult->orderBy('id','DESC');
        $estadoCuenta = $estadoCuentaResult->paginate($paginacion);

        if (!count($estadoCuenta)) 
        {
           return response(['data' => [],'code'=>204]);
        }
        return response(['data'=> EstadoCuentaResource::collection($estadoCuenta), 'per_page' => $estadoCuenta->perPage(), 'total' => $estadoCuenta->total()]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EstadoCuenta  $estadoCuenta
     * @return \Illuminate\Http\Response
     */
    public function show(EstadoCuenta $estadoCuenta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EstadoCuenta  $estadoCuenta
     * @return \Illuminate\Http\Response
     */
    public function update(EstadoCuentaFormRequest $request)
    {
        try 
        {
            $data = $request->all();
            DB::beginTransaction();
            $estadoCuenta = EstadoCuenta::findOrFail($request->id);
            $data['path_comprobante'] = $estadoCuenta->path_comprobante;
            if ($request->hasFile('path_comprobante')) {
                $request->validate([
                    'path_comprobante' => 'image|mimes:jpeg,png,jpg,gif|max:10000',
                ]);
                $documentName = rand(1235,35515).time().'.'.$request->path_comprobante->extension();
                $request->path_comprobante->storeAs('/public', $documentName);
                $url = Storage::url($documentName);
                $data['path_comprobante'] = $url;
                $data['fecha_subida'] = Carbon::now();
                $data["estado"] = "ENV";
            }
            $estadoCuenta->update($data);
            DB::commit();
            return response(['data'=> new EstadoCuentaResource($estadoCuenta), 'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    public function procesar(EstadoCuentaFormRequest $request)
    {
        try 
        {
            $data = $request->all();
            DB::beginTransaction();
            $estadoCuenta = EstadoCuenta::findOrFail($request->id);
            $data["estado"] = $request->proceso == 'validar' ? 'PAG' : 'RECH';
            $data["id_usuario_aprobo"] = $request->id_usuario_proceso > 0 ? $request->id_usuario_proceso : 0;
            $data["fecha_aprobado"] = $request->proceso == 'validar' && $request->id_usuario_proceso > 0 ? Carbon::now() : null;
            $estadoCuenta->update($data);
            DB::commit();
            return response(['data'=> new EstadoCuentaResource($estadoCuenta), 'code' => 200], 200);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EstadoCuenta  $estadoCuenta
     * @return \Illuminate\Http\Response
     */
    public function destroy(EstadoCuenta $estadoCuenta)
    {
        //
    }
}
