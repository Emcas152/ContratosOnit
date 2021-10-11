<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NoticiaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
        [
            'id' => $this->id,
            'id_usuario_creo' => $this->id_usuario_creo,
            'usuario_creo_descripcion' => $this->usuarioNoticia !== null ? $this->usuarioNoticia->name : '',
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'tipo_noticia' => $this->tipo_noticia,
            'tipo_noticia_descripcion' => $this->tipoNoticia !== null ? $this->tipoNoticia->descripcion_det : '',
            'prioridad' => $this->prioridad,
            'prioridad_descripcion' => $this->prioridadNoticia !== null ? $this->prioridadNoticia->descripcion_det : '',
            'fecha_publicacion' => $this->fecha_publicacion,
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoNoticia !== null ? $this->estadoNoticia->descripcion_det : ''
        ];
    }
}
