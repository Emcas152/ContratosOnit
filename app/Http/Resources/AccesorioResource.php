<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccesorioResource extends JsonResource
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
            'categoria' => $this->categoria,
            'categoria_descripcion' => $this->categoriaAccesorio !== null ? $this->categoriaAccesorio->descripcion_det : '',
            'image' => $this->image,
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoAccesorio !== null ? $this->estadoAccesorio->descripcion_det : '',
        ];
    }
}
