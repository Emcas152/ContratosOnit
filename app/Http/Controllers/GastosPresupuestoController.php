<?php

namespace App\Http\Controllers;

use App\Models\GastosPresupuesto;
use App\Models\EstadosProcesos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\GastosPresupuestoResource;
use App\Http\Requests\GastosPresupuestoFormRequest;
use DB;

class GastosPresupuestoController extends Controller
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
        $presupuesto = $request->id_presupuesto;
        $gastoResult = GastosPresupuesto::where([['descripcion', 'LIKE', '%'.$queryUrl.'%'],['id_presupuesto', '=', $presupuesto]])
                                        ->orWhere([['numero_factura', 'LIKE', '%'.$queryUrl.'%'],['id_presupuesto', '=', $presupuesto]])
                                        ->orWhere([['serie_factura', 'LIKE', '%'.$queryUrl.'%'],['id_presupuesto', '=', $presupuesto]])
                                        ->orWhere([['proveedor', 'LIKE', '%'.$queryUrl.'%'],['id_presupuesto', '=', $presupuesto]])
                                        ->orWhere([['total', 'LIKE', '%'.$queryUrl.'%'],['id_presupuesto', '=', $presupuesto]])
                                        ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%'],['id_presupuesto', '=', $presupuesto]])
                                        ->orderBy('id','DESC')
                                        ->paginate($pagination);
        if (!count($gastoResult)) {
            return response(['data' => [],'code'=>204]);
        }
        return response(['data'=> GastosPresupuestoResource::collection($gastoResult),'per_page' => $gastoResult->perPage(),'total' => $gastoResult->total()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GastosPresupuestoFormRequest $request)
    {
        try 
        {
            $data = $request->all();
            DB::beginTransaction();
            if ($request->hasFile('path_imagen')) {
                $request->validate([
                    'path_imagen' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
                ]);
                $imageName = time().'.'.$request->path_imagen->extension();
                $request->path_imagen->storeAs('/public', $imageName);
                $url = Storage::url($imageName);
                $data['path_imagen'] = $url;
            } else {
                $data['path_imagen'] = '/storage/amenidad.png';
            }
            $gastoPresupuesto = GastosPresupuesto::create($data);
            DB::commit();
            return response(['data'=> new GastosPresupuestoResource($gastoPresupuesto),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear el gasto presupuesto','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GastosPresupuesto  $gastosPresupuesto
     * @return \Illuminate\Http\Response
     */
    public function show(GastosPresupuesto $gastosPresupuesto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GastosPresupuesto  $gastosPresupuesto
     * @return \Illuminate\Http\Response
     */
    public function update(GastosPresupuestoFormRequest $request)
    {
        try 
        {
            $data = $request->all();
            DB::beginTransaction();
            $gastoPresupuesto = GastosPresupuesto::findOrFail($request->id);
            $data['path_imagen'] = $gastoPresupuesto->path_imagen;
            if ($request->hasFile('path_imagen')) {
                $request->validate([
                    'path_imagen' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
                ]);
                $imageName = time().'.'.$request->path_imagen->extension();
                $request->path_imagen->storeAs('/public', $imageName);
                $url = Storage::url($imageName);
                $data['path_imagen'] = $url;
            }
            $gastoPresupuesto->update($data);
            DB::commit();
            return response(['data'=> new GastosPresupuestoResource($gastoPresupuesto), 'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GastosPresupuesto  $gastosPresupuesto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $gastoPresupuesto = GastosPresupuesto::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial', $gastoPresupuesto->estado],['tabla','gastos_presupuesto']])->firstOrFail();
            $gastoPresupuesto->estado = $estado->sts_final;
            $gastoPresupuesto->update();
            DB::commit();
            return response(['data'=> new GastosPresupuestoResource($gastoPresupuesto),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }
}
