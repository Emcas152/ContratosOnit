<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApartamentoResource extends JsonResource
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
            'id_edificio' => $this->id_edificio,
            'edificio' =>$this->edificios !== null ? $this->edificios->nombre : 'null',
            'id_inquilino' => $this->id_inquilino,
            'inquilino' => $this->inquilinos !== null ? $this->inquilinos->name : '',
            'telefono' => $this->inquilinos !== null ? $this->inquilinos->telefono : '',
            'correo' => $this->inquilinos !== null ? $this->inquilinos->email : '',
            'nombre' => $this->nombre,
            'nivel' => $this->nivel,
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoApartamento !== null ? $this->estadoApartamento->descripcion_det : ''
        ];
    }
}
