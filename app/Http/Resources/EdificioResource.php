<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EdificioResource extends JsonResource
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
            'condominio' =>$this->condominios !== null ? $this->condominios->nombre : '',
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'niveles' => $this->niveles,
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoEdificios !== null ? $this->estadoEdificios->descripcion_det : '',
            'amenidades' => collect($this->amenidades)->pluck('id'),
        ];
    }
}
