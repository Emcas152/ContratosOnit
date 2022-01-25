<?php

namespace App\Http\Controllers;

use App\Models\Accesorio;
use App\Http\Controllers\Controller;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\AccesorioResource;
use App\Http\Requests\AccesorioFormRequest;
use DB;

class AccesorioController extends Controller
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
        $condominio = $request->id_condominio;
        $accesorioResult = Accesorio::where([['nombre', 'LIKE', '%'.$queryUrl.'%'],['id_condominio', '=', $condominio]])
                                        ->orWhere([['descripcion', 'LIKE', '%'.$queryUrl.'%'],['id_condominio', '=', $condominio]])
                                        ->orWhere([['categoria', 'LIKE', '%'.$queryUrl.'%'],['id_condominio', '=', $condominio]])
                                        ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%'],['id_condominio', '=', $condominio]])
                                        ->orderBy('id','DESC')
                                        ->paginate($pagination);
        if (!count($accesorioResult)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> AccesorioResource::collection($accesorioResult),'per_page' => $accesorioResult->perPage(),'total' => $accesorioResult->total()]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccesorioFormRequest $request)
    {
        try 
        {
            $data = $request->all();
            DB::beginTransaction();
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
                ]);
                $imageName = time().'.'.$request->image->extension();
                $request->image->storeAs('/public', $imageName);
                $url = Storage::url($imageName);
                $data['image'] = $url;
            } else {
                $data['image'] = '/storage/amenidad.png';
            }
            if(trim($request->icon) == ''){
                $data['icon'] = 'book';
            }
            $accesorio = Accesorio::create($data);
            DB::commit();
            return response(['data'=> new AccesorioResource($accesorio),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear el accesorio','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accesorio  $accesorio
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $accesorios = Accesorio::where([['id_condominio', '=', $request->id_condominio]])
                                    ->whereNotIn('estado',['MSTS', 'ANU'])
                                    ->get(['id','nombre']);

        if (!count($accesorios)) 
        {
           return response(['data' => [],'code'=>204]);   
        }
        return response(['data'=> $accesorios,'code' => 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accesorio  $accesorio
     * @return \Illuminate\Http\Response
     */
    public function byCategories(Request $request)
    {
        $queryUrl = trim($request->searchText);
        $pagination = $request->paginate;
        $accesorios = Accesorio::where([['id_condominio', '=', $request->id_condominio],['categoria', '=', $request->id]])
                                ->whereNotIn('estado',['MSTS', 'ANU'])
                                ->where(function($query) use($queryUrl) {
                                    $query->orWhere([['descripcion', 'LIKE', '%'.$queryUrl.'%']])
                                    ->orWhere([['nombre', 'LIKE', '%'.$queryUrl.'%']]);
                                })
                                ->orderBy('id','DESC')
                                ->paginate($pagination);

        if (!count($accesorios)) 
        {
           return response(['data' => [],'code'=>204]);   
        }
        return response(['data'=> AccesorioResource::collection($accesorios),'per_page' => $accesorios->perPage(),'total' => $accesorios->total()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accesorio  $accesorio
     * @return \Illuminate\Http\Response
     */
    public function update(AccesorioFormRequest $request, Accesorio $accesorio)
    {
        try 
        {
            $data = $request->all();
            DB::beginTransaction();
            $accesorio = Accesorio::findOrFail($request->id);
            $data['image'] = $accesorio->image;
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
                ]);
                $imageName = time().'.'.$request->image->extension();
                $request->image->storeAs('/public', $imageName);
                $url = Storage::url($imageName);
                $data['image'] = $url;
            }
            if(trim($request->icon) != ''){
                $data['icon'] = $request->icon;
            }
            $accesorio->update($data);
            DB::commit();
            return response(['data'=> new AccesorioResource($accesorio), 'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accesorio  $accesorio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $accesorio = Accesorio::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial', $accesorio->estado],['tabla','accesorios']])->firstOrFail();
            $accesorio->estado = $estado->sts_final;
            $accesorio->update();
            DB::commit();
            return response(['data'=> new AccesorioResource($accesorio),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }
}
