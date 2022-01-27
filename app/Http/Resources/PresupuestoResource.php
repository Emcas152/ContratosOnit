<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PresupuestoResource extends JsonResource
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
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'id_condominio' => $this->id_condominio,
            'condominio_descripcion' => $this->condominio !== null ? $this->condominio->nombre : '',
            'descripcion' => $this->descripcion,
            'presupuesto' => $this->presupuesto,
            'fecha_edicion' => $this->fecha_edicion,
            'usuario_creo' => $this->usuario_creo,
            'nombre_creo' => $this->usuarioCreo !== null ? $this->usuarioCreo->name : '',
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoPresupuesto !== null ? $this->estadoPresupuesto->descripcion_det : '',
        ];
    }
}
