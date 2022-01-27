<?php

namespace App\Http\Controllers;

use App\Models\DetallePresupuesto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DetallePresupuestoResource;
use App\Http\Requests\DetallePresupuestoFormRequest;
use Carbon\Carbon;
use DB;

class DetallePresupuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = $request->role;
        $encabezado = $request->id_encabezado;
        $queryUrl=trim($request->searchText);
        $paginacion = $request->paginate;
        $detallePresupuestoResult = [];

        $detallePresupuestoResult = DetallePresupuesto::where([['subcategoria', 'LIKE', '%'.$queryUrl.'%'],['id_encabezado', '=', $encabezado]])
                                            ->orWhere([['subtotal', 'LIKE', '%'.$queryUrl.'%'],['id_encabezado', '=', $encabezado]])
                                            ->orderBy('id','DESC')
                                            ->paginate($paginacion);

        if (!count($detallePresupuestoResult)) 
        {
           return response(['data' => [],'code'=>204]); 
        }
        return response(['data'=> DetallePresupuestoResource::collection($detallePresupuestoResult),'per_page' => $detallePresupuestoResult->perPage(),'total' => $detallePresupuestoResult->total()]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DetallePresupuestoFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $detallePresupuesto = DetallePresupuesto::create($request->all());
            DB::commit();
            return response(['data'=> new DetallePresupuestoResource($detallePresupuesto),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetallePresupuesto  $detallePresupuesto
     * @return \Illuminate\Http\Response
     */
    public function show(DetallePresupuesto $detallePresupuesto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetallePresupuesto  $detallePresupuesto
     * @return \Illuminate\Http\Response
     */
    public function update(DetallePresupuestoFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $detallePresupuesto = DetallePresupuesto::findOrFail($request->id);
            $detallePresupuesto->subcategoria = $request->subcategoria;
            $detallePresupuesto->presupuesto = $request->presupuesto;
            $detallePresupuesto->update();
            DB::commit();
            return response(['data'=> new DetallePresupuestoResource($detallePresupuesto),'code' => 200]);
        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]);  
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetallePresupuesto  $detallePresupuesto
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetallePresupuesto $detallePresupuesto)
    {
        //
    }
}
