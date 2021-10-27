<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\ViewNoticias;
use App\Models\Apartamento;
use App\Models\EstadosProcesos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\NoticiaResource;
use App\Http\Requests\NoticiaFormRequest;
use Carbon\Carbon;
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
        $condominio = $request->id_condominio;
        $noticiaResult = Noticia::join('users','users.id','noticias.id_usuario_creo')
                                    ->select('noticias.id', 'noticias.id_usuario_creo', 'noticias.nombre', 'noticias.descripcion', 'noticias.tipo_noticia', 'noticias.prioridad', 'noticias.fecha_publicacion', 'noticias.estado')
                                        ->where([['nombre', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                        ->orWhere([['descripcion', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                        ->orWhere([['tipo_noticia', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                        ->orWhere([['prioridad', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                        ->orWhere([['noticias.estado', 'LIKE', '%'.$queryUrl.'%'],['users.id_condominio', '=', $condominio]])
                                        ->orderBy('id','DESC')
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
            $edificios = $request->edificios;
            $data = $request->all();
            $data['fecha_publicacion'] = Carbon::now();
            DB::beginTransaction();
            $noticia = Noticia::create($data);
            if(count($edificios)){
                foreach ($edificios as $edificio) {
                    $noticia->edificios()->attach($edificio, ['id_condominio' => $request->id_condominio]);
                }
            } else {
                $noticia->condominios()->attach($request->id_condominio);
            }

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
    public function show(Request $request)
    {
        $id_usuario = $request->user;
        $role = $request->role;
        $condominio = $request->id_condominio;
        $queryUrl = trim($request->searchText);
        $pagination = $request->paginate;

        $noticias = [];

        if($role == "admin") {
            $noticias = ViewNoticias::where([['estado', '=', 'ACT'],['id_condominio', '=', $condominio]])
                                    ->where(function($query) use($queryUrl) {
                                            $query->orWhere([['descripcion', 'LIKE', '%'.$queryUrl.'%']])
                                            ->orWhere([['usuario_creo', 'LIKE', '%'.$queryUrl.'%']])
                                            ->orWhere([['titulo', 'LIKE', '%'.$queryUrl.'%']])
                                            ->orWhere([['nombre', 'LIKE', '%'.$queryUrl.'%']])
                                            ->orWhere([['tipo_noticia_descripcion', 'LIKE', '%'.$queryUrl.'%']])
                                            ->orWhere([['prioridad_descripcion', 'LIKE', '%'.$queryUrl.'%']]);
                                    })->orderBy('fecha_publicacion', 'DESC')
                                    ->paginate($pagination);
        } else {
            $edificioApartamento = Apartamento::where([['id_inquilino', '=', $id_usuario]])
                                                ->get(['id_edificio','nivel']);

            $niveles = $edificioApartamento->pluck('nivel');
            $noticiasPropias = ViewNoticias::where([['estado', '=', 'ACT'],['id_condominio', '=', $condominio]])
                                ->where(function($query) use($queryUrl) {
                                    $query->orWhere([['descripcion', 'LIKE', '%'.$queryUrl.'%']])
                                    ->orWhere([['usuario_creo', 'LIKE', '%'.$queryUrl.'%']])
                                    ->orWhere([['titulo', 'LIKE', '%'.$queryUrl.'%']])
                                    ->orWhere([['nombre', 'LIKE', '%'.$queryUrl.'%']])
                                    ->orWhere([['tipo_noticia_descripcion', 'LIKE', '%'.$queryUrl.'%']])
                                    ->orWhere([['prioridad_descripcion', 'LIKE', '%'.$queryUrl.'%']]);
                                })->whereIn('id_edificio', $edificioApartamento->pluck('id_edificio'))
                                ->where(function($query) use($niveles) {
                                                $query->whereIn('nivel', $niveles)
                                                ->orWhere([['nivel', '=', 0]]);
                                        });
            
            $noticias = ViewNoticias::where([['estado', '=', 'ACT'],['id_condominio', '=', $condominio],['id_edificio','<', 1]])
                                ->where(function($query) use($queryUrl) {
                                    $query->orWhere([['descripcion', 'LIKE', '%'.$queryUrl.'%']])
                                    ->orWhere([['usuario_creo', 'LIKE', '%'.$queryUrl.'%']])
                                    ->orWhere([['titulo', 'LIKE', '%'.$queryUrl.'%']])
                                    ->orWhere([['nombre', 'LIKE', '%'.$queryUrl.'%']])
                                    ->orWhere([['tipo_noticia_descripcion', 'LIKE', '%'.$queryUrl.'%']])
                                    ->orWhere([['prioridad_descripcion', 'LIKE', '%'.$queryUrl.'%']]);
                                })->union($noticiasPropias)
                                ->orderBy('fecha_publicacion', 'DESC')
                                        ->paginate($pagination);                                    
        }
        
        if (!count($noticias)) {
            return response(['data' => '','code'=>204]);  
        }

        return response(['data'=> $noticias,'per_page' => $noticias->perPage(),'total' => $noticias->total(),'code' => 200]);
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
            $edificios = $request->edificios;
            DB::beginTransaction();
            $noticia = Noticia::findOrFail($request->id);
            $noticia->update($request->all());
            $noticia->condominios()->wherePivot('id_condominio', $request->id_condominio)->detach();
            if(count($edificios)){
                foreach ($edificios as $edificio) {
                    $noticia->edificios()->attach($edificio, ['id_condominio' => $request->id_condominio]);
                }
            } else {
                $noticia->condominios()->attach($request->id_condominio);
            }
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
