<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Http\Controllers\Controller;
use App\Models\EstadosProcesos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\DocumentosResource;
use App\Http\Requests\DocumentoFormRequest;
use DB;

class DocumentController extends Controller
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
        $documentoResult = Document::where([['nombre', 'LIKE', '%'.$queryUrl.'%'],['id_condominio', '=', $condominio]])
                                        ->orWhere([['descripcion', 'LIKE', '%'.$queryUrl.'%'],['id_condominio', '=', $condominio]])
                                        ->orWhere([['estado', 'LIKE', '%'.$queryUrl.'%'],['id_condominio', '=', $condominio]])
                                        ->orderBy('id','DESC')
                                        ->paginate($pagination);
        if (!count($documentoResult)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> DocumentosResource::collection($documentoResult),'per_page' => $documentoResult->perPage(),'total' => $documentoResult->total()]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentoFormRequest $request)
    {
        try 
        {
            $data = $request->all();
            DB::beginTransaction();
            if ($request->hasFile('path')) {
                $request->validate([
                    'path' => 'required|mimes:pdf|max:10000',
                ]);
                $documentName = 'document'.time().'.'.$request->path->extension();
                $request->path->storeAs('/public', $documentName);
                $url = Storage::url($documentName);
                $data['path'] = $url;
            } else {
                $data['path'] = '/storage/documento.pdf';
            }
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
                ]);
                $imageName = time().'.'.$request->image->extension();
                $request->image->storeAs('/public', $imageName);
                $url = Storage::url($imageName);
                $data['image'] = $url;
            } else {
                $data['image'] = '/storage/documento.png';
            }
            $documento = Document::create($data);
            DB::commit();
            return response(['data'=> new DocumentosResource($documento),'code' => 201]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear el documento','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentoFormRequest $request, Document $document)
    {
        try 
        {
            $data = $request->all();
            DB::beginTransaction();
            $documento = Document::findOrFail($request->id);
            $data['path'] = $documento->path;
            if ($request->hasFile('path')) {
                $request->validate([
                    'path' => 'required|mimes:pdf|max:10000',
                ]);
                $documentName = 'document'.time().'.'.$request->path->extension();
                $request->path->storeAs('/public', $documentName);
                $url = Storage::url($documentName);
                $data['path'] = $url;
            }
            $data['image'] = $documento->image;
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
                ]);
                $imageName = time().'.'.$request->image->extension();
                $request->image->storeAs('/public', $imageName);
                $url = Storage::url($imageName);
                $data['image'] = $url;
            }
            $documento->update($data);
            DB::commit();
            return response(['data'=> new DocumentosResource($documento), 'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $documento = Document::findOrFail($request->id);
            $estado = EstadosProcesos::where([['sts_inicial', $documento->estado],['tabla','documentos']])->firstOrFail();
            $documento->estado = $estado->sts_final;
            $documento->update();
            DB::commit();
            return response(['data'=> new DocumentosResource($documento),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> $e,'code' => 500]); 
        }
    }
}
