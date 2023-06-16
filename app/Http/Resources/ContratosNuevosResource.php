<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContratosNuevosResource extends JsonResource
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
            'nombre' => $this->nombre,
            'identificacion' => $this->identificacion,
            'tipo_proyecto' => $this->tipo_proyecto,
            'numero_apartamento' => $this->numero_apartamento,
            'celular' => $this->celular,
            'email' => $this->email,
            'nit' => $this->nit,
            'file_name' => $this->file_name,
            'file_name_energia' => $this->file_name_energia,
            'file_name_agua' => $this->file_name_agua,
            'fecha_registro' => $this->fecha_registro,
            'status' => $this->status

         
        ];
    }
}
