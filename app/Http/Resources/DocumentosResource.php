<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'id_condominio' => $this->id_condominio,
            'condominio_descripcion' => $this->condominio !== null ? $this->condominio->nombre : '',
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'path' => $this->path,
            'icon' => $this->icon,
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoDocumento !== null ? $this->estadoDocumento->descripcion_det : '',
        ];
    }
}
