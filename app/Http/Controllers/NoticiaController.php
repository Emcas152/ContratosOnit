<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\EstadosProcesos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\NoticiaResource;
use App\Http\Requests\NoticiaFormRequest;
use DB;

class NoticiaController extends Controller
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
        $noticiaResult = Noticia::where([['nombre', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['descripcion', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['tipo_noticia', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['prioridad', 'LIKE', '%'.$queryUrl.'%']])
                                        ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%']])
                                        ->paginate($pagination);
        if (!count($noticiaResult)) {
            return response(['data' => '','code'=>204]);  
        }
        return response(['data'=> NoticiaResource::collection($noticiaResult),'per_page' => $noticiaResult->perPage(),'total' => $noticiaResult->total()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoticiaFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $noticia = Noticia::create($request->all());
            DB::commit();
            return response(['data'=> new NoticiaResource($noticia),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear el noticia','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Noticia  $noticia
     * @return \Illuminate\Http\Response
     */
    public function show(Noticia $noticia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Noticia  $noticia
     * @return \Illuminate\Http\Response
     */
    public function update(NoticiaFormRequest $request, Noticia $noticia)
    {
        try 
        {
            DB::beginTransaction();
            $noticia = Noticia::findOrFail($request->id);
            $noticia->update($request->all());
            DB::commit();
            return response(['data'=> new NoticiaResource($noticia),'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Noticia  $noticia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $noticia = Noticia::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial',$noticia->estado],['tabla','noticias']])->firstOrFail();
            $noticia->estado = $estado->sts_final;
            $noticia->update();
            DB::commit();
            return response(['data'=> new NoticiaResource($noticia),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }
}
