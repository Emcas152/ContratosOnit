<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CondominioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'parqueo_visitantes' => $this->parqueo_visitantes,
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoCondominio !== null ? $this->estadoCondominio->descripcion_det : '',
            'id_usuario' => $this->id_usuario
        ];
    }
}
