<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitantesResource extends JsonResource
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
            'usuario_creo_descripcion' => $this->usuarioCreo !== null ? $this->usuarioCreo->name : '',
            'id_inquilino' => $this->id_inquilino,
            'inquilino_descripcion' => $this->usuarioInquilino !== null ? $this->usuarioInquilino->name : '',
            'apartamento' => $this->inquilinoApartamento !== null ? $this->inquilinoApartamento->nombre : '',
            'dpi_visita' => $this->dpi_visita,
            'nombre_visita' => $this->nombre_visita,
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoVisitante !== null ? $this->estadoVisitante->descripcion_det : ''
        ];
    }
}
