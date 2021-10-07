<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AmenidadResource extends JsonResource
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
            'color' => $this->color,
            'color_descripcion' => $this->colorAmenidad !== null ? $this->colorAmenidad->descripcion_det : '',
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoAmenidad !== null ? $this->estadoAmenidad->descripcion_det : '',
        ];
    }
}
