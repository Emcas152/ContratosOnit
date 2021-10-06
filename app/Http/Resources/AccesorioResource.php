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
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'categoria' => $this->categoria,
            'categoria_descripcion' => $this->categoriaAccesorio !== null ? $this->categoriaAccesorio->descripcion_det : '',
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoAccesorio !== null ? $this->estadoAccesorio->descripcion_det : '',
        ];
    }
}
